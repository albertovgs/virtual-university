#!/bin/bash

# Database connectivity and initialization test script
# Tests database connection, schema initialization, and data integrity

set -e

echo "=== Virtual University Database Test Script ==="
echo "Testing database initialization and connectivity..."

# Configuration
DB_CONTAINER="virtual-university-db-1"
WEB_CONTAINER="virtual-university-web-1"
DB_HOST="db"
DB_USER="learning_user"
DB_PASS="learning_pass"
DB_NAME="sw15_update"

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

# Function to check if containers are running
check_containers() {
    print_status "INFO" "Checking if Docker containers are running..."
    
    if ! docker ps --format "table {{.Names}}" | grep -q "$DB_CONTAINER"; then
        print_status "ERROR" "Database container '$DB_CONTAINER' is not running"
        echo "Please start the containers with: docker-compose up -d"
        exit 1
    fi
    
    if ! docker ps --format "table {{.Names}}" | grep -q "$WEB_CONTAINER"; then
        print_status "ERROR" "Web container '$WEB_CONTAINER' is not running"
        echo "Please start the containers with: docker-compose up -d"
        exit 1
    fi
    
    print_status "SUCCESS" "Both containers are running"
}

# Function to test database connectivity
test_db_connectivity() {
    print_status "INFO" "Testing database connectivity..."
    
    # Test connection from web container
    if docker exec "$WEB_CONTAINER" php -r "
        \$connection = new mysqli('$DB_HOST', '$DB_USER', '$DB_PASS', '$DB_NAME');
        if (\$connection->connect_error) {
            echo 'Connection failed: ' . \$connection->connect_error;
            exit(1);
        }
        echo 'Connected successfully';
        \$connection->close();
    " > /dev/null 2>&1; then
        print_status "SUCCESS" "Database connection successful from web container"
    else
        print_status "ERROR" "Failed to connect to database from web container"
        return 1
    fi
}

# Function to test database schema
test_db_schema() {
    print_status "INFO" "Testing database schema initialization..."
    
    # Check if required tables exist
    local tables=("coordinators" "majors" "periods" "students" "professors" "classes")
    
    for table in "${tables[@]}"; do
        if docker exec "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "DESCRIBE $table;" > /dev/null 2>&1; then
            print_status "SUCCESS" "Table '$table' exists and is accessible"
        else
            print_status "ERROR" "Table '$table' does not exist or is not accessible"
            return 1
        fi
    done
}

# Function to test default data
test_default_data() {
    print_status "INFO" "Testing default data initialization..."
    
    # Check if admin user exists
    local admin_count=$(docker exec "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -se "SELECT COUNT(*) FROM coordinators WHERE email='admin@admin.com';")
    
    if [ "$admin_count" -gt 0 ]; then
        print_status "SUCCESS" "Default admin user exists"
    else
        print_status "ERROR" "Default admin user not found"
        return 1
    fi
    
    # Check if there are some initial records
    local total_records=$(docker exec "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -se "SELECT (SELECT COUNT(*) FROM coordinators) + (SELECT COUNT(*) FROM majors) + (SELECT COUNT(*) FROM periods);")
    
    if [ "$total_records" -gt 0 ]; then
        print_status "SUCCESS" "Database contains initial data ($total_records records)"
    else
        print_status "ERROR" "Database appears to be empty"
        return 1
    fi
}

# Function to test database character set
test_db_charset() {
    print_status "INFO" "Testing database character set configuration..."
    
    local charset=$(docker exec "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -se "SELECT DEFAULT_CHARACTER_SET_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME='$DB_NAME';")
    
    if [ "$charset" = "utf8mb4" ]; then
        print_status "SUCCESS" "Database character set is utf8mb4"
    else
        print_status "ERROR" "Database character set is '$charset', expected 'utf8mb4'"
        return 1
    fi
}

# Function to test CodeIgniter database configuration
test_ci_db_config() {
    print_status "INFO" "Testing CodeIgniter database configuration..."
    
    # Test if CodeIgniter can connect to database
    if docker exec "$WEB_CONTAINER" php -r "
        require_once '/var/www/html/system/core/Common.php';
        require_once '/var/www/html/application/config/database.php';
        
        \$db_config = \$db['default'];
        \$connection = new mysqli(
            \$db_config['hostname'],
            \$db_config['username'],
            \$db_config['password'],
            \$db_config['database']
        );
        
        if (\$connection->connect_error) {
            echo 'CodeIgniter DB config failed: ' . \$connection->connect_error;
            exit(1);
        }
        
        echo 'CodeIgniter database configuration is working';
        \$connection->close();
    " > /dev/null 2>&1; then
        print_status "SUCCESS" "CodeIgniter database configuration is working"
    else
        print_status "ERROR" "CodeIgniter database configuration failed"
        return 1
    fi
}

# Main execution
main() {
    echo
    check_containers
    echo
    
    local failed_tests=0
    
    test_db_connectivity || ((failed_tests++))
    test_db_schema || ((failed_tests++))
    test_default_data || ((failed_tests++))
    test_db_charset || ((failed_tests++))
    test_ci_db_config || ((failed_tests++))
    
    echo
    if [ $failed_tests -eq 0 ]; then
        print_status "SUCCESS" "All database tests passed! ✨"
        echo
        echo "Database Summary:"
        echo "- Host: $DB_HOST"
        echo "- Database: $DB_NAME"
        echo "- User: $DB_USER"
        echo "- Character Set: utf8mb4"
        echo "- Default Admin: admin@admin.com / admin123"
        exit 0
    else
        print_status "ERROR" "$failed_tests test(s) failed"
        echo
        echo "Troubleshooting tips:"
        echo "1. Ensure containers are running: docker-compose up -d"
        echo "2. Check container logs: docker-compose logs db"
        echo "3. Verify database initialization: docker-compose logs db | grep 'ready for connections'"
        exit 1
    fi
}

# Run main function
main "$@"