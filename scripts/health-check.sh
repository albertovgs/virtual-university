#!/bin/bash

# Health Check Script for Virtual University Docker Environment
# This script provides comprehensive health monitoring for the application

set -e

# Configuration
WEB_URL="${WEB_URL:-http://localhost:8080}"
DB_HOST="${DB_HOST:-localhost}"
DB_PORT="${DB_PORT:-3306}"
DB_USER="${DB_USER:-learning_user}"
DB_PASS="${DB_PASS:-learning_pass}"
DB_NAME="${DB_NAME:-sw15_update}"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    local status=$1
    local message=$2
    case $status in
        "OK")
            echo -e "${GREEN}✓${NC} $message"
            ;;
        "WARNING")
            echo -e "${YELLOW}⚠${NC} $message"
            ;;
        "ERROR")
            echo -e "${RED}✗${NC} $message"
            ;;
    esac
}

# Function to check web service health
check_web_service() {
    echo "Checking web service health..."
    
    # Basic connectivity check
    if curl -f -s "$WEB_URL/health" > /dev/null 2>&1; then
        print_status "OK" "Web service is responding"
    else
        print_status "ERROR" "Web service is not responding"
        return 1
    fi
    
    # Database connectivity through web service
    local db_response=$(curl -s "$WEB_URL/health/database" | grep -o '"status":"[^"]*"' | cut -d'"' -f4)
    if [ "$db_response" = "healthy" ]; then
        print_status "OK" "Database connectivity through web service is healthy"
    else
        print_status "ERROR" "Database connectivity through web service failed"
        return 1
    fi
    
    # Full health check
    local full_response=$(curl -s "$WEB_URL/health/full")
    local overall_status=$(echo "$full_response" | grep -o '"status":"[^"]*"' | cut -d'"' -f4)
    
    case $overall_status in
        "healthy")
            print_status "OK" "Full health check passed"
            ;;
        "degraded")
            print_status "WARNING" "Health check shows degraded performance"
            ;;
        *)
            print_status "ERROR" "Full health check failed"
            return 1
            ;;
    esac
}

# Function to check database service directly
check_database_service() {
    echo "Checking database service health..."
    
    # Check if MySQL is accepting connections
    if command -v mysqladmin > /dev/null 2>&1; then
        if mysqladmin ping -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" --silent; then
            print_status "OK" "Database is accepting connections"
        else
            print_status "ERROR" "Database is not accepting connections"
            return 1
        fi
        
        # Check if database exists
        if mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" -e "USE $DB_NAME;" 2>/dev/null; then
            print_status "OK" "Database '$DB_NAME' exists and is accessible"
        else
            print_status "ERROR" "Database '$DB_NAME' is not accessible"
            return 1
        fi
    else
        print_status "WARNING" "MySQL client not available for direct database check"
    fi
}

# Function to check Docker containers
check_docker_containers() {
    echo "Checking Docker containers..."
    
    if command -v docker > /dev/null 2>&1; then
        # Check if containers are running
        local web_status=$(docker compose ps --services --filter "status=running" | grep -c "web" || echo "0")
        local db_status=$(docker compose ps --services --filter "status=running" | grep -c "db" || echo "0")
        
        if [ "$web_status" -eq 1 ]; then
            print_status "OK" "Web container is running"
        else
            print_status "ERROR" "Web container is not running"
        fi
        
        if [ "$db_status" -eq 1 ]; then
            print_status "OK" "Database container is running"
        else
            print_status "ERROR" "Database container is not running"
        fi
        
        # Check container health status
        local web_health=$(docker compose ps --format "table {{.Service}}\t{{.Status}}" | grep "web" | grep -o "healthy\|unhealthy\|starting" || echo "unknown")
        local db_health=$(docker compose ps --format "table {{.Service}}\t{{.Status}}" | grep "db" | grep -o "healthy\|unhealthy\|starting" || echo "unknown")
        
        case $web_health in
            "healthy")
                print_status "OK" "Web container health check is passing"
                ;;
            "unhealthy")
                print_status "ERROR" "Web container health check is failing"
                ;;
            "starting")
                print_status "WARNING" "Web container is still starting up"
                ;;
            *)
                print_status "WARNING" "Web container health status unknown"
                ;;
        esac
        
        case $db_health in
            "healthy")
                print_status "OK" "Database container health check is passing"
                ;;
            "unhealthy")
                print_status "ERROR" "Database container health check is failing"
                ;;
            "starting")
                print_status "WARNING" "Database container is still starting up"
                ;;
            *)
                print_status "WARNING" "Database container health status unknown"
                ;;
        esac
    else
        print_status "WARNING" "Docker not available for container status check"
    fi
}

# Main execution
main() {
    echo "Virtual University Health Check"
    echo "=============================="
    echo
    
    local exit_code=0
    
    # Run all health checks
    check_web_service || exit_code=1
    echo
    check_database_service || exit_code=1
    echo
    check_docker_containers || exit_code=1
    
    echo
    if [ $exit_code -eq 0 ]; then
        print_status "OK" "All health checks passed"
    else
        print_status "ERROR" "Some health checks failed"
    fi
    
    exit $exit_code
}

# Run main function if script is executed directly
if [ "${BASH_SOURCE[0]}" == "${0}" ]; then
    main "$@"
fi