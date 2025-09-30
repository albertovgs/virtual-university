#!/bin/bash
set -e

# Docker entrypoint script for Virtual University
# Handles initialization, configuration, and startup

echo "Starting Virtual University Docker container..."

# Function to wait for database
wait_for_database() {
    echo "Waiting for database connection..."
    
    local max_attempts=30
    local attempt=1
    
    while [ $attempt -le $max_attempts ]; do
        if mysqladmin ping -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" --silent; then
            echo "Database is ready!"
            return 0
        fi
        
        echo "Database not ready, attempt $attempt/$max_attempts..."
        sleep 2
        attempt=$((attempt + 1))
    done
    
    echo "ERROR: Database connection timeout after $max_attempts attempts"
    exit 1
}

# Function to setup application directories
setup_directories() {
    echo "Setting up application directories..."
    
    # Create required directories if they don't exist
    mkdir -p /var/www/html/Learning/application/logs
    mkdir -p /var/www/html/Learning/application/cache
    mkdir -p /var/www/html/uploads
    
    # Set proper permissions
    chown -R www-data:www-data /var/www/html
    chmod -R 755 /var/www/html
    chmod -R 777 /var/www/html/Learning/application/logs
    chmod -R 777 /var/www/html/Learning/application/cache
    chmod -R 777 /var/www/html/uploads
    
    echo "Directories setup completed"
}

# Function to configure PHP based on environment
configure_php() {
    echo "Configuring PHP for environment: ${CI_ENV:-development}"
    
    if [ "${CI_ENV}" = "production" ]; then
        # Production PHP configuration
        echo "display_errors=Off" > /usr/local/etc/php/conf.d/env.ini
        echo "log_errors=On" >> /usr/local/etc/php/conf.d/env.ini
        echo "error_reporting=E_ALL & ~E_DEPRECATED & ~E_STRICT" >> /usr/local/etc/php/conf.d/env.ini
        
        # Optimize OPcache for production
        echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini
    else
        # Development PHP configuration
        echo "display_errors=On" > /usr/local/etc/php/conf.d/env.ini
        echo "log_errors=On" >> /usr/local/etc/php/conf.d/env.ini
        echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/env.ini
        
        # Enable OPcache revalidation for development
        echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/opcache.ini
        echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini
    fi
    
    echo "PHP configuration completed"
}

# Function to validate configuration
validate_configuration() {
    echo "Validating configuration..."
    
    # Check required environment variables
    local required_vars=("DB_HOST" "DB_USER" "DB_PASS" "DB_NAME")
    for var in "${required_vars[@]}"; do
        if [ -z "${!var}" ]; then
            echo "ERROR: Required environment variable $var is not set"
            exit 1
        fi
    done
    
    # Check if CodeIgniter files exist
    if [ ! -f "/var/www/html/Learning/index.php" ]; then
        echo "ERROR: CodeIgniter application files not found"
        exit 1
    fi
    
    echo "Configuration validation completed"
}

# Function to run database migrations (if needed)
run_migrations() {
    echo "Checking for database migrations..."
    
    # This is a placeholder - implement actual migration logic if needed
    # For now, we'll just verify the database connection
    if [ "${DB_HOST}" != "localhost" ]; then
        wait_for_database
    fi
    
    echo "Database checks completed"
}

# Function to clear caches
clear_caches() {
    echo "Clearing application caches..."
    
    # Clear CodeIgniter cache
    if [ -d "/var/www/html/Learning/application/cache" ]; then
        find /var/www/html/Learning/application/cache -name "*.php" -type f -delete 2>/dev/null || true
    fi
    
    # Clear OPcache if in development
    if [ "${CI_ENV}" != "production" ]; then
        # OPcache will be cleared automatically on restart
        echo "OPcache will be cleared on startup"
    fi
    
    echo "Cache clearing completed"
}

# Function to setup logging
setup_logging() {
    echo "Setting up logging..."
    
    # Create log files if they don't exist
    touch /var/log/php_errors.log
    chown www-data:www-data /var/log/php_errors.log
    
    # Setup log rotation (basic)
    if [ "${CI_ENV}" = "production" ]; then
        # In production, you might want to setup proper log rotation
        echo "Production logging configured"
    fi
    
    echo "Logging setup completed"
}

# Main initialization function
initialize_application() {
    echo "Initializing Virtual University application..."
    
    validate_configuration
    setup_directories
    configure_php
    setup_logging
    run_migrations
    clear_caches
    
    echo "Application initialization completed successfully!"
}

# Handle different commands
case "${1}" in
    apache2-foreground)
        initialize_application
        echo "Starting Apache web server..."
        exec "$@"
        ;;
    bash|sh)
        # Allow shell access for debugging
        exec "$@"
        ;;
    *)
        # Default behavior
        initialize_application
        echo "Starting with custom command: $@"
        exec "$@"
        ;;
esac