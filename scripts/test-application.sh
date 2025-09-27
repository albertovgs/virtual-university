#!/bin/bash

# CodeIgniter application functionality validation script
# Tests web server, application routes, and core functionality

set -e

echo "=== Virtual University Application Test Script ==="
echo "Testing CodeIgniter application functionality..."

# Configuration
WEB_CONTAINER="virtual-university-web-1"
BASE_URL="http://localhost:8080"
TIMEOUT=10

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
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
        "TEST")
            echo -e "${BLUE}🧪 $message${NC}"
            ;;
    esac
}

# Function to check if web container is running
check_web_container() {
    print_status "INFO" "Checking if web container is running..."
    
    if ! docker ps --format "table {{.Names}}" | grep -q "$WEB_CONTAINER"; then
        print_status "ERROR" "Web container '$WEB_CONTAINER' is not running"
        echo "Please start the containers with: docker-compose up -d"
        exit 1
    fi
    
    print_status "SUCCESS" "Web container is running"
}

# Function to test HTTP connectivity
test_http_connectivity() {
    print_status "TEST" "Testing HTTP connectivity to $BASE_URL..."
    
    local max_attempts=30
    local attempt=1
    
    while [ $attempt -le $max_attempts ]; do
        if curl -s --max-time $TIMEOUT "$BASE_URL" > /dev/null 2>&1; then
            print_status "SUCCESS" "HTTP connectivity successful"
            return 0
        fi
        
        print_status "INFO" "Attempt $attempt/$max_attempts - waiting for web server..."
        sleep 2
        ((attempt++))
    done
    
    print_status "ERROR" "Failed to connect to web server after $max_attempts attempts"
    return 1
}

# Function to test main application page
test_main_page() {
    print_status "TEST" "Testing main application page..."
    
    local response=$(curl -s --max-time $TIMEOUT "$BASE_URL" 2>/dev/null || echo "")
    
    if [[ $response == *"Virtual University"* ]] || [[ $response == *"Learning"* ]] || [[ $response == *"login"* ]]; then
        print_status "SUCCESS" "Main page loads and contains expected content"
    else
        print_status "ERROR" "Main page does not contain expected content"
        echo "Response preview: ${response:0:200}..."
        return 1
    fi
}

# Function to test login page
test_login_page() {
    print_status "TEST" "Testing login page accessibility..."
    
    local login_url="$BASE_URL/Login"
    local response=$(curl -s --max-time $TIMEOUT "$login_url" 2>/dev/null || echo "")
    
    if [[ $response == *"login"* ]] || [[ $response == *"email"* ]] || [[ $response == *"password"* ]]; then
        print_status "SUCCESS" "Login page is accessible and contains form elements"
    else
        print_status "ERROR" "Login page is not accessible or missing form elements"
        return 1
    fi
}

# Function to test health endpoint
test_health_endpoint() {
    print_status "TEST" "Testing health check endpoint..."
    
    local health_url="$BASE_URL/Health"
    local response=$(curl -s --max-time $TIMEOUT "$health_url" 2>/dev/null || echo "")
    
    if [[ $response == *"status"* ]] && [[ $response == *"healthy"* ]]; then
        print_status "SUCCESS" "Health endpoint is working"
    else
        print_status "ERROR" "Health endpoint is not responding correctly"
        return 1
    fi
}

# Function to test PHP configuration
test_php_config() {
    print_status "TEST" "Testing PHP configuration in container..."
    
    # Test PHP version
    local php_version=$(docker exec "$WEB_CONTAINER" php -v | head -n 1)
    if [[ $php_version == *"PHP 7.4"* ]]; then
        print_status "SUCCESS" "PHP 7.4 is installed: $php_version"
    else
        print_status "ERROR" "Expected PHP 7.4, got: $php_version"
        return 1
    fi
    
    # Test required extensions
    local extensions=("mysqli" "pdo_mysql" "json" "mbstring")
    for ext in "${extensions[@]}"; do
        if docker exec "$WEB_CONTAINER" php -m | grep -q "$ext"; then
            print_status "SUCCESS" "PHP extension '$ext' is loaded"
        else
            print_status "ERROR" "PHP extension '$ext' is not loaded"
            return 1
        fi
    done
}

# Function to test Apache configuration
test_apache_config() {
    print_status "TEST" "Testing Apache configuration..."
    
    # Test mod_rewrite
    if docker exec "$WEB_CONTAINER" apache2ctl -M | grep -q "rewrite_module"; then
        print_status "SUCCESS" "Apache mod_rewrite is enabled"
    else
        print_status "ERROR" "Apache mod_rewrite is not enabled"
        return 1
    fi
    
    # Test document root
    local doc_root=$(docker exec "$WEB_CONTAINER" apache2ctl -S | grep "Main DocumentRoot" | awk '{print $3}' | tr -d '"')
    if [[ $doc_root == "/var/www/html" ]]; then
        print_status "SUCCESS" "Apache document root is correctly set to /var/www/html"
    else
        print_status "ERROR" "Apache document root is '$doc_root', expected '/var/www/html'"
        return 1
    fi
}

# Function to test CodeIgniter framework
test_codeigniter_framework() {
    print_status "TEST" "Testing CodeIgniter framework functionality..."
    
    # Test if CodeIgniter is properly loaded
    local ci_test=$(docker exec "$WEB_CONTAINER" php -r "
        if (file_exists('/var/www/html/system/core/CodeIgniter.php')) {
            echo 'CodeIgniter core found';
        } else {
            echo 'CodeIgniter core not found';
            exit(1);
        }
    " 2>/dev/null || echo "error")
    
    if [[ $ci_test == *"CodeIgniter core found"* ]]; then
        print_status "SUCCESS" "CodeIgniter framework files are present"
    else
        print_status "ERROR" "CodeIgniter framework files are missing"
        return 1
    fi
    
    # Test application structure
    local app_dirs=("controllers" "models" "views" "config")
    for dir in "${app_dirs[@]}"; do
        if docker exec "$WEB_CONTAINER" test -d "/var/www/html/application/$dir"; then
            print_status "SUCCESS" "Application directory '$dir' exists"
        else
            print_status "ERROR" "Application directory '$dir' is missing"
            return 1
        fi
    done
}

# Function to test file permissions
test_file_permissions() {
    print_status "TEST" "Testing file permissions..."
    
    # Check if web server can read application files
    if docker exec "$WEB_CONTAINER" test -r "/var/www/html/index.php"; then
        print_status "SUCCESS" "Web server can read application files"
    else
        print_status "ERROR" "Web server cannot read application files"
        return 1
    fi
    
    # Check cache directory permissions (if exists)
    if docker exec "$WEB_CONTAINER" test -d "/var/www/html/application/cache"; then
        if docker exec "$WEB_CONTAINER" test -w "/var/www/html/application/cache"; then
            print_status "SUCCESS" "Cache directory is writable"
        else
            print_status "ERROR" "Cache directory is not writable"
            return 1
        fi
    else
        print_status "INFO" "Cache directory does not exist (this is normal)"
    fi
}

# Function to test error handling
test_error_handling() {
    print_status "TEST" "Testing error handling..."
    
    # Test 404 error handling
    local error_url="$BASE_URL/NonExistentPage"
    local status_code=$(curl -s -o /dev/null -w "%{http_code}" --max-time $TIMEOUT "$error_url" 2>/dev/null || echo "000")
    
    if [[ $status_code == "404" ]]; then
        print_status "SUCCESS" "404 error handling works correctly"
    else
        print_status "ERROR" "Expected 404 status code, got: $status_code"
        return 1
    fi
}

# Function to display application information
display_app_info() {
    echo
    print_status "INFO" "Application Information:"
    echo "- Base URL: $BASE_URL"
    echo "- Login URL: $BASE_URL/Login"
    echo "- Health Check: $BASE_URL/Health"
    echo "- Default Admin: admin@admin.com / admin123"
    echo
    print_status "INFO" "Container Information:"
    echo "- Web Container: $WEB_CONTAINER"
    echo "- PHP Version: $(docker exec "$WEB_CONTAINER" php -v | head -n 1 | awk '{print $2}')"
    echo "- Apache Version: $(docker exec "$WEB_CONTAINER" apache2 -v | head -n 1 | awk '{print $3}')"
}

# Main execution
main() {
    echo
    check_web_container
    echo
    
    local failed_tests=0
    
    test_http_connectivity || ((failed_tests++))
    test_php_config || ((failed_tests++))
    test_apache_config || ((failed_tests++))
    test_codeigniter_framework || ((failed_tests++))
    test_file_permissions || ((failed_tests++))
    test_main_page || ((failed_tests++))
    test_login_page || ((failed_tests++))
    test_health_endpoint || ((failed_tests++))
    test_error_handling || ((failed_tests++))
    
    echo
    if [ $failed_tests -eq 0 ]; then
        print_status "SUCCESS" "All application tests passed! 🎉"
        display_app_info
        exit 0
    else
        print_status "ERROR" "$failed_tests test(s) failed"
        echo
        echo "Troubleshooting tips:"
        echo "1. Check container logs: docker-compose logs web"
        echo "2. Verify container is healthy: docker ps"
        echo "3. Test direct container access: docker exec -it $WEB_CONTAINER bash"
        echo "4. Check Apache error logs: docker exec $WEB_CONTAINER cat /var/log/apache2/error.log"
        exit 1
    fi
}

# Run main function
main "$@"