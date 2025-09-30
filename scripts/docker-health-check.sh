#!/bin/bash

# Docker health check script for Virtual University
# This script is used by Docker's HEALTHCHECK instruction

set -e

# Configuration
HEALTH_URL="${HEALTH_URL:-http://localhost/health}"
TIMEOUT="${HEALTH_TIMEOUT:-10}"
MAX_RETRIES="${HEALTH_MAX_RETRIES:-3}"

# Function to check web service health
check_web_health() {
    local url="$1"
    local timeout="$2"
    
    # Use curl to check the health endpoint
    if curl -f -s --max-time "$timeout" "$url" > /dev/null 2>&1; then
        return 0
    else
        return 1
    fi
}

# Function to check database connectivity through web service
check_database_health() {
    local url="$1/database"
    local timeout="$2"
    
    local response=$(curl -s --max-time "$timeout" "$url" 2>/dev/null || echo "")
    
    if echo "$response" | grep -q '"status":"healthy"'; then
        return 0
    else
        return 1
    fi
}

# Main health check function
main() {
    local retry=0
    
    while [ $retry -lt $MAX_RETRIES ]; do
        # Check basic web service health
        if check_web_health "$HEALTH_URL" "$TIMEOUT"; then
            # If basic health is OK, check database
            if check_database_health "$HEALTH_URL" "$TIMEOUT"; then
                echo "Health check passed"
                exit 0
            else
                echo "Database health check failed (attempt $((retry + 1))/$MAX_RETRIES)"
            fi
        else
            echo "Web service health check failed (attempt $((retry + 1))/$MAX_RETRIES)"
        fi
        
        retry=$((retry + 1))
        
        # Wait before retry (except on last attempt)
        if [ $retry -lt $MAX_RETRIES ]; then
            sleep 2
        fi
    done
    
    echo "Health check failed after $MAX_RETRIES attempts"
    exit 1
}

# Run the health check
main "$@"