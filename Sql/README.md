# Database Initialization

This directory contains the database initialization scripts for the Virtual University Docker setup.

## Files

### Initialization Scripts (executed in alphabetical order)

1. **00-setup-user.sh** - Sets up database user permissions and character set
2. **01-init-database.sql** - Creates all database tables with proper utf8mb4 character set
3. **99-verify-setup.sql** - Verifies the database initialization was successful

### Legacy Files

- **Update_proyect.sql** - Original database schema (kept for reference)

## Character Set Configuration

The database is configured to use:
- **Character Set**: utf8mb4
- **Collation**: utf8mb4_unicode_ci

This ensures proper support for:
- Unicode characters
- Emojis
- International text
- Special characters

## Default Data

The initialization creates:
- **Admin User**: 
  - Email: admin@learning.edu
  - Password: Hacker
  - User ID: 10000001
- **10 Classrooms**: Lab-1 through Lab-4, Class Room-1 through Class Room-6

## Verification

After the containers start, you can verify the initialization by:

1. Checking the database character set:
```sql
SELECT SCHEMA_NAME, DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME 
FROM information_schema.SCHEMATA 
WHERE SCHEMA_NAME = 'sw15_update';
```

2. Counting the created tables:
```sql
SELECT COUNT(*) FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'sw15_update';
```

3. Verifying the admin user:
```sql
SELECT u.email_user, u.type_user, p.name_person 
FROM tb_users u 
JOIN tb_people p ON u.id_user = p.id_person 
WHERE u.type_user = 'Admin';
```

## Troubleshooting

If the database initialization fails:

1. Check Docker logs: `docker compose logs db`
2. Verify file permissions on initialization scripts
3. Ensure the MySQL container has enough time to start (increase start_period in healthcheck)
4. Run the test script: `./test-db-init.sh`

## Environment Variables

The initialization uses these environment variables:
- `MYSQL_ROOT_PASSWORD` - Root password for MySQL
- `MYSQL_DATABASE` - Database name (default: sw15_update)
- `MYSQL_USER` - Application user (default: learning_user)
- `MYSQL_PASSWORD` - Application user password (default: learning_pass)