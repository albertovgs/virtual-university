#!/bin/bash

# Simple database initialization test script
# This is a lightweight version for quick validation

echo "Testing database initialization..."

# Check if containers are running
if ! docker ps | grep -q "virtual-university"; then
    echo "❌ Containers are not running. Please start with: docker-compose up -d"
    exit 1
fi

# Test database connection
if docker exec virtual-university-db-1 mysql -u learning_user -p'learning_pass' sw15_update -e "SELECT 1;" > /dev/null 2>&1; then
    echo "✅ Database connection successful"
else
    echo "❌ Database connection failed"
    exit 1
fi

# Test if tables exist
if docker exec virtual-university-db-1 mysql -u learning_user -p'learning_pass' sw15_update -e "SHOW TABLES;" | grep -q "coordinators"; then
    echo "✅ Database tables initialized"
else
    echo "❌ Database tables not found"
    exit 1
fi

echo "✅ Database initialization test passed!"