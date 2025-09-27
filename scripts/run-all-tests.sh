#!/bin/bash

# Comprehensive test runner for Virtual University Docker environment
# Runs all validation tests in sequence

set -e

echo "=========================================="
echo "Virtual University Docker Test Suite"
echo "=========================================="
echo "Running comprehensive validation tests..."
echo

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
FAILED_TESTS=0

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# Function to print colored output
print_header() {
    echo -e "${BOLD}${BLUE}$1${NC}"
    echo "----------------------------------------"
}

print_status() {
    local status=$1
    local message=$2
    case $status in
        "SUCCESS")
            echo -e "${GREEN}✓ $message${NC}"
            ;;
        "ERROR")
            echo -e "${RED}✗ $message${NC}"
            ;;
        "INFO")
            echo -e "${YELLOW}ℹ $message${NC}"
            ;;
    esac
}

# Function to run a test script
run_test() {
    local test_name=$1
    local test_script=$2
    
    print_header "$test_name"
    
    if [ -f "$test_script" ]; then
        if bash "$test_script"; then
            print_status "SUCCESS" "$test_name completed successfully"
            echo
            return 0
        else
            print_status "ERROR" "$test_name failed"
            echo
            return 1
        fi
    else
        print_status "ERROR" "Test script not found: $test_script"
        echo
        return 1
    fi
}

# Function to check prerequisites
check_prerequisites() {
    print_header "Prerequisites Check"
    
    # Check if Docker is installed and running
    if ! command -v docker &> /dev/null; then
        print_status "ERROR" "Docker is not installed"
        exit 1
    fi
    print_status "SUCCESS" "Docker is installed"
    
    # Check if Docker Compose is available
    if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
        print_status "ERROR" "Docker Compose is not available"
        exit 1
    fi
    print_status "SUCCESS" "Docker Compose is available"
    
    # Check if curl is installed
    if ! command -v curl &> /dev/null; then
        print_status "ERROR" "curl is not installed (required for HTTP tests)"
        exit 1
    fi
    print_status "SUCCESS" "curl is installed"
    
    # Check if we're in the right directory
    if [ ! -f "docker-compose.yml" ]; then
        print_status "ERROR" "docker-compose.yml not found. Please run from project root directory."
        exit 1
    fi
    print_status "SUCCESS" "Running from correct directory"
    
    echo
}

# Function to start containers if not running
ensure_containers_running() {
    print_header "Container Status Check"
    
    # Check if containers are running
    if docker-compose ps | grep -q "Up"; then
        print_status "INFO" "Containers are already running"
    else
        print_status "INFO" "Starting containers..."
        if docker-compose up -d; then
            print_status "SUCCESS" "Containers started successfully"
            
            # Wait for containers to be ready
            print_status "INFO" "Waiting for containers to be ready..."
            sleep 10
        else
            print_status "ERROR" "Failed to start containers"
            exit 1
        fi
    fi
    
    # Show container status
    echo
    print_status "INFO" "Current container status:"
    docker-compose ps
    echo
}

# Function to display test summary
display_summary() {
    echo "=========================================="
    echo -e "${BOLD}Test Summary${NC}"
    echo "=========================================="
    
    if [ $FAILED_TESTS -eq 0 ]; then
        echo -e "${GREEN}${BOLD}🎉 ALL TESTS PASSED! 🎉${NC}"
        echo
        echo "Your Virtual University Docker environment is fully functional!"
        echo
        echo "Quick Start Guide:"
        echo "1. Access the application: http://localhost:8080"
        echo "2. Login with: admin@admin.com / admin123"
        echo "3. Start developing: Edit files in Learning/ directory"
        echo "4. Monitor health: http://localhost:8080/Health"
        echo
        echo "Useful Commands:"
        echo "- View logs: docker-compose logs -f"
        echo "- Stop containers: docker-compose down"
        echo "- Restart containers: docker-compose restart"
        echo "- Access web container: docker exec -it virtual-university-web-1 bash"
        echo "- Access database: docker exec -it virtual-university-db-1 mysql -u learning_user -p sw15_update"
    else
        echo -e "${RED}${BOLD}❌ $FAILED_TESTS TEST(S) FAILED ❌${NC}"
        echo
        echo "Some tests failed. Please check the output above for details."
        echo
        echo "Common troubleshooting steps:"
        echo "1. Check container logs: docker-compose logs"
        echo "2. Restart containers: docker-compose down && docker-compose up -d"
        echo "3. Check Docker resources: docker system df"
        echo "4. Verify port availability: netstat -tulpn | grep :8080"
        echo "5. Check file permissions in Learning/ directory"
    fi
    
    echo "=========================================="
}

# Main execution
main() {
    # Check prerequisites
    check_prerequisites
    
    # Ensure containers are running
    ensure_containers_running
    
    # Run all tests
    echo "Starting test execution..."
    echo
    
    # Test 1: Database connectivity and initialization
    if ! run_test "Database Connectivity & Initialization Test" "$SCRIPT_DIR/test-database.sh"; then
        ((FAILED_TESTS++))
    fi
    
    # Test 2: Application functionality
    if ! run_test "Application Functionality Test" "$SCRIPT_DIR/test-application.sh"; then
        ((FAILED_TESTS++))
    fi
    
    # Test 3: File synchronization and hot-reload
    if ! run_test "File Synchronization & Hot-Reload Test" "$SCRIPT_DIR/test-file-sync.sh"; then
        ((FAILED_TESTS++))
    fi
    
    # Display final summary
    display_summary
    
    # Exit with appropriate code
    if [ $FAILED_TESTS -eq 0 ]; then
        exit 0
    else
        exit 1
    fi
}

# Handle script interruption
trap 'echo -e "\n${YELLOW}Test execution interrupted${NC}"; exit 130' INT

# Run main function
main "$@"