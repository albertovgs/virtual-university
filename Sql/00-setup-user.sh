#!/bin/bash
# User setup script for Docker MySQL container
# This script runs before the main database initialization

set -e

echo "Setting up database user permissions..."

# Wait for MySQL to be ready
until mysql -h localhost -u root -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; do
  echo "Waiting for MySQL to be ready..."
  sleep 2
done

# Grant proper permissions to the application user
mysql -h localhost -u root -p"$MYSQL_ROOT_PASSWORD" <<-EOSQL
    -- Ensure the database user has proper permissions
    GRANT ALL PRIVILEGES ON \`${MYSQL_DATABASE}\`.* TO '${MYSQL_USER}'@'%';
    GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON \`${MYSQL_DATABASE}\`.* TO '${MYSQL_USER}'@'%';
    
    -- Flush privileges to ensure changes take effect
    FLUSH PRIVILEGES;
    
    -- Set default character set for the database
    ALTER DATABASE \`${MYSQL_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EOSQL

echo "Database user setup completed successfully."