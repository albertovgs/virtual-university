#!/bin/bash

# File synchronization and hot-reload test script
# Tests that file changes on host are reflected in container immediately

set -e

echo "=== Virtual University File Synchronization Test Script ==="
echo "Testing file synchronization and hot-reload capabilities..."

# Configuration
WEB_CONTAINER="virtual-university-web"
BASE_URL="http://localhost:8080"
TEST_FILE="Learning/test_sync_file.php"
TEST_CONTROLLER="Learning/application/controllers/Test_sync.php"
TIMEOUT=5

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

# Function to cleanup test files
cleanup() {
    print_status "INFO" "Cleaning up test files..."
    
    # Remove test files if they exist
    [ -f "$TEST_FILE" ] && rm -f "$TEST_FILE"
    [ -f "$TEST_CONTROLLER" ] && rm -f "$TEST_CONTROLLER"
    
    print_status "SUCCESS" "Cleanup completed"
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

# Function to test basic file synchronization
test_basic_file_sync() {
    print_status "TEST" "Testing basic file synchronization..."
    
    # Create a test PHP file on host
    local timestamp=$(date +%s)
    cat > "$TEST_FILE" << EOF
<?php
echo "File sync test - Timestamp: $timestamp";
?>
EOF
    
    print_status "INFO" "Created test file on host with timestamp: $timestamp"
    
    # Wait a moment for file sync
    sleep 2
    
    # Check if file exists in container
    if docker exec "$WEB_CONTAINER" test -f "/var/www/html/test_sync_file.php"; then
        print_status "SUCCESS" "Test file is visible in container"
    else
        print_status "ERROR" "Test file is not visible in container"
        cleanup
        return 1
    fi
    
    # Check file content in container
    local container_content=$(docker exec "$WEB_CONTAINER" cat "/var/www/html/test_sync_file.php")
    if [[ $container_content == *"$timestamp"* ]]; then
        print_status "SUCCESS" "File content is synchronized correctly"
    else
        print_status "ERROR" "File content is not synchronized"
        cleanup
        return 1
    fi
    
    # Test file access via web server
    local web_response=$(curl -s --max-time $TIMEOUT "$BASE_URL/test_sync_file.php" 2>/dev/null || echo "")
    if [[ $web_response == *"$timestamp"* ]]; then
        print_status "SUCCESS" "File is accessible via web server with correct content"
    else
        print_status "ERROR" "File is not accessible via web server or content is incorrect"
        cleanup
        return 1
    fi
}

# Function to test file modification hot-reload
test_file_modification() {
    print_status "TEST" "Testing file modification hot-reload..."
    
    # Modify the existing test file
    local new_timestamp=$(date +%s)
    cat > "$TEST_FILE" << EOF
<?php
echo "Modified file sync test - New Timestamp: $new_timestamp";
?>
EOF
    
    print_status "INFO" "Modified test file with new timestamp: $new_timestamp"
    
    # Wait a moment for file sync
    sleep 2
    
    # Test updated content via web server
    local web_response=$(curl -s --max-time $TIMEOUT "$BASE_URL/test_sync_file.php" 2>/dev/null || echo "")
    if [[ $web_response == *"$new_timestamp"* ]]; then
        print_status "SUCCESS" "File modification is reflected immediately via web server"
    else
        print_status "ERROR" "File modification is not reflected via web server"
        print_status "INFO" "Response: $web_response"
        cleanup
        return 1
    fi
}

# Function to test CodeIgniter controller hot-reload
test_controller_hotreload() {
    print_status "TEST" "Testing CodeIgniter controller hot-reload..."
    
    # Create a test controller
    local controller_timestamp=$(date +%s)
    cat > "$TEST_CONTROLLER" << EOF
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_sync extends CI_Controller {
    
    public function index() {
        echo "Controller sync test - Timestamp: $controller_timestamp";
    }
    
    public function json_test() {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'timestamp' => $controller_timestamp,
            'message' => 'Controller hot-reload working'
        ]);
    }
}
EOF
    
    print_status "INFO" "Created test controller with timestamp: $controller_timestamp"
    
    # Wait a moment for file sync
    sleep 3
    
    # Test controller via web server
    local controller_response=$(curl -s --max-time $TIMEOUT "$BASE_URL/Test_sync" 2>/dev/null || echo "")
    if [[ $controller_response == *"$controller_timestamp"* ]]; then
        print_status "SUCCESS" "Controller is accessible and working"
    else
        print_status "ERROR" "Controller is not accessible or not working"
        print_status "INFO" "Response: $controller_response"
        cleanup
        return 1
    fi
    
    # Test JSON endpoint
    local json_response=$(curl -s --max-time $TIMEOUT "$BASE_URL/Test_sync/json_test" 2>/dev/null || echo "")
    if [[ $json_response == *"$controller_timestamp"* ]] && [[ $json_response == *"success"* ]]; then
        print_status "SUCCESS" "Controller JSON endpoint is working"
    else
        print_status "ERROR" "Controller JSON endpoint is not working"
        print_status "INFO" "JSON Response: $json_response"
        cleanup
        return 1
    fi
}

# Function to test controller modification
test_controller_modification() {
    print_status "TEST" "Testing controller modification hot-reload..."
    
    # Modify the existing controller
    local new_controller_timestamp=$(date +%s)
    cat > "$TEST_CONTROLLER" << EOF
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_sync extends CI_Controller {
    
    public function index() {
        echo "MODIFIED Controller sync test - New Timestamp: $new_controller_timestamp";
    }
    
    public function json_test() {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'modified',
            'timestamp' => $new_controller_timestamp,
            'message' => 'Controller modification hot-reload working'
        ]);
    }
}
EOF
    
    print_status "INFO" "Modified test controller with new timestamp: $new_controller_timestamp"
    
    # Wait a moment for file sync
    sleep 3
    
    # Test modified controller via web server
    local modified_response=$(curl -s --max-time $TIMEOUT "$BASE_URL/Test_sync" 2>/dev/null || echo "")
    if [[ $modified_response == *"$new_controller_timestamp"* ]] && [[ $modified_response == *"MODIFIED"* ]]; then
        print_status "SUCCESS" "Controller modification is reflected immediately"
    else
        print_status "ERROR" "Controller modification is not reflected"
        print_status "INFO" "Response: $modified_response"
        cleanup
        return 1
    fi
}

# Function to test directory synchronization
test_directory_sync() {
    print_status "TEST" "Testing directory synchronization..."
    
    # Create a test directory and file
    local test_dir="Learning/test_sync_dir"
    local test_dir_file="$test_dir/sync_test.php"
    
    mkdir -p "$test_dir"
    
    local dir_timestamp=$(date +%s)
    cat > "$test_dir_file" << EOF
<?php
echo "Directory sync test - Timestamp: $dir_timestamp";
?>
EOF
    
    print_status "INFO" "Created test directory and file"
    
    # Wait for sync
    sleep 2
    
    # Check if directory and file exist in container
    if docker exec "$WEB_CONTAINER" test -d "/var/www/html/test_sync_dir" && \
       docker exec "$WEB_CONTAINER" test -f "/var/www/html/test_sync_dir/sync_test.php"; then
        print_status "SUCCESS" "Directory and file are synchronized to container"
    else
        print_status "ERROR" "Directory or file not synchronized to container"
        rm -rf "$test_dir"
        cleanup
        return 1
    fi
    
    # Test file access via web server
    local dir_response=$(curl -s --max-time $TIMEOUT "$BASE_URL/test_sync_dir/sync_test.php" 2>/dev/null || echo "")
    if [[ $dir_response == *"$dir_timestamp"* ]]; then
        print_status "SUCCESS" "Directory file is accessible via web server"
    else
        print_status "ERROR" "Directory file is not accessible via web server"
        rm -rf "$test_dir"
        cleanup
        return 1
    fi
    
    # Cleanup test directory
    rm -rf "$test_dir"
    
    # Wait and check if directory is removed from container
    sleep 2
    if ! docker exec "$WEB_CONTAINER" test -d "/var/www/html/test_sync_dir"; then
        print_status "SUCCESS" "Directory deletion is synchronized to container"
    else
        print_status "ERROR" "Directory deletion is not synchronized to container"
        return 1
    fi
}

# Function to test file permissions sync
test_permissions_sync() {
    print_status "TEST" "Testing file permissions synchronization..."
    
    # Create a file and set specific permissions
    echo "<?php echo 'Permission test'; ?>" > "$TEST_FILE"
    chmod 644 "$TEST_FILE"
    
    # Wait for sync
    sleep 2
    
    # Check permissions in container
    local container_perms=$(docker exec "$WEB_CONTAINER" stat -c "%a" "/var/www/html/test_sync_file.php" 2>/dev/null || echo "000")
    
    if [[ $container_perms == "644" ]]; then
        print_status "SUCCESS" "File permissions are synchronized correctly (644)"
    else
        print_status "INFO" "File permissions in container: $container_perms (may differ due to mount options)"
    fi
    
    # Test if file is still readable by web server
    local perm_response=$(curl -s --max-time $TIMEOUT "$BASE_URL/test_sync_file.php" 2>/dev/null || echo "")
    if [[ $perm_response == *"Permission test"* ]]; then
        print_status "SUCCESS" "File with synchronized permissions is accessible via web server"
    else
        print_status "ERROR" "File with synchronized permissions is not accessible via web server"
        cleanup
        return 1
    fi
}

# Main execution
main() {
    echo
    check_web_container
    echo
    
    # Set trap to cleanup on exit
    trap cleanup EXIT
    
    local failed_tests=0
    
    test_basic_file_sync || ((failed_tests++))
    test_file_modification || ((failed_tests++))
    test_controller_hotreload || ((failed_tests++))
    test_controller_modification || ((failed_tests++))
    test_directory_sync || ((failed_tests++))
    test_permissions_sync || ((failed_tests++))
    
    echo
    if [ $failed_tests -eq 0 ]; then
        print_status "SUCCESS" "All file synchronization tests passed! 🚀"
        echo
        echo "File Sync Summary:"
        echo "- Basic file sync: ✓ Working"
        echo "- File modification hot-reload: ✓ Working"
        echo "- CodeIgniter controller hot-reload: ✓ Working"
        echo "- Controller modification: ✓ Working"
        echo "- Directory synchronization: ✓ Working"
        echo "- Permission synchronization: ✓ Working"
        echo
        print_status "INFO" "Your development environment supports real-time file changes!"
        exit 0
    else
        print_status "ERROR" "$failed_tests test(s) failed"
        echo
        echo "Troubleshooting tips:"
        echo "1. Ensure Docker volume mounts are working: docker-compose config"
        echo "2. Check container logs: docker-compose logs web"
        echo "3. Verify file system permissions on host"
        echo "4. Test manual file sync: docker exec $WEB_CONTAINER ls -la /var/www/html/"
        exit 1
    fi
}

# Run main function
main "$@"