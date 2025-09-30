# Environment Variables Documentation

This document provides comprehensive information about all environment variables used in the Virtual University Docker setup.

## Quick Start

1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```

2. Modify the `.env` file as needed for your environment.

3. Start the application:
   ```bash
   docker-compose up -d
   ```

## Environment Variables Reference

### Database Configuration

| Variable | Default Value | Description | Required |
|----------|---------------|-------------|----------|
| `DB_HOST` | `db` | Database hostname (use 'db' for Docker Compose) | Yes |
| `DB_USER` | `learning_user` | Database username | Yes |
| `DB_PASS` | `learning_pass` | Database password | Yes |
| `DB_NAME` | `sw15_update` | Database name | Yes |
| `MYSQL_ROOT_PASSWORD` | `root_password` | MySQL root password for administration | Yes |

### Application Configuration

| Variable | Default Value | Description | Required |
|----------|---------------|-------------|----------|
| `CI_ENV` | `development` | CodeIgniter environment (development/testing/production) | Yes |
| `DEBUG_MODE` | `true` | Enable/disable debugging features | No |
| `LOG_THRESHOLD` | `2` | Logging level (0-4, see details below) | No |
| `BASE_URL` | Auto-detected | Application base URL | No |
| `ENCRYPTION_KEY` | Empty | CodeIgniter encryption key | No* |

*Required for production environments

### Web Server Configuration

| Variable | Default Value | Description | Required |
|----------|---------------|-------------|----------|
| `APACHE_PORT` | `8080` | Host port for Apache (container always uses 80) | No |

### Session Configuration

| Variable | Default Value | Description | Required |
|----------|---------------|-------------|----------|
| `SESSION_DRIVER` | `files` | Session storage driver | No |
| `SESSION_SAVE_PATH` | Empty | Custom session save path | No |

## Environment-Specific Configurations

### Development Environment

```bash
# Development settings
CI_ENV=development
DEBUG_MODE=true
LOG_THRESHOLD=2
APACHE_PORT=8080

# Use default database credentials
DB_HOST=db
DB_USER=learning_user
DB_PASS=learning_pass
DB_NAME=sw15_update
MYSQL_ROOT_PASSWORD=root_password
```

### Testing Environment

```bash
# Testing settings
CI_ENV=testing
DEBUG_MODE=true
LOG_THRESHOLD=3
APACHE_PORT=8081

# Use test database
DB_HOST=db
DB_USER=test_user
DB_PASS=test_password
DB_NAME=sw15_test
MYSQL_ROOT_PASSWORD=test_root_password
```

### Production Environment

```bash
# Production settings
CI_ENV=production
DEBUG_MODE=false
LOG_THRESHOLD=1
APACHE_PORT=80

# Use secure database credentials
DB_HOST=your-production-db-host
DB_USER=your-production-db-user
DB_PASS=your-secure-production-password
DB_NAME=sw15_production
MYSQL_ROOT_PASSWORD=your-secure-root-password

# Required for production
BASE_URL=https://your-domain.com
ENCRYPTION_KEY=your-32-character-encryption-key
```

## Log Threshold Levels

| Level | Description | Use Case |
|-------|-------------|----------|
| `0` | Disables logging | Not recommended |
| `1` | Error Messages only | Production |
| `2` | Debug Messages | Development |
| `3` | Informational Messages | Testing/Staging |
| `4` | All Messages | Debugging |

## Security Considerations

### Development
- Default passwords are acceptable for local development
- Debug mode can be enabled
- Detailed logging is helpful

### Production
- **NEVER** use default passwords in production
- Generate a secure 32-character encryption key
- Set `CI_ENV=production`
- Set `DEBUG_MODE=false`
- Use `LOG_THRESHOLD=1` to log only errors
- Consider using external database services
- Use HTTPS and set appropriate `BASE_URL`

## Generating Secure Values

### Encryption Key
Generate a 32-character encryption key:
```bash
# Using OpenSSL
openssl rand -hex 16

# Using PHP
php -r "echo bin2hex(random_bytes(16));"
```

### Secure Passwords
Generate secure passwords:
```bash
# Using OpenSSL
openssl rand -base64 32

# Using pwgen (if available)
pwgen -s 32 1
```

## External Database Configuration

For production deployments with external databases:

```bash
# External MySQL/MariaDB
DB_HOST=your-db-server.com
DB_USER=your-app-user
DB_PASS=your-secure-password
DB_NAME=your-database-name

# For cloud databases, you might need additional parameters
DB_PORT=3306
DB_SSL_MODE=REQUIRED
```

## Docker Compose Override

For environment-specific Docker configurations, create a `docker-compose.override.yml` file:

```yaml
# docker-compose.override.yml for production
version: '3.8'
services:
  web:
    environment:
      - CI_ENV=production
    ports:
      - "80:80"
  
  db:
    # Use external database in production
    # Remove this service and configure external DB
```

## Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Check `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` values
   - Ensure database container is running: `docker-compose ps`
   - Check database logs: `docker-compose logs db`

2. **Port Already in Use**
   - Change `APACHE_PORT` to an available port
   - Check what's using the port: `lsof -i :8080`

3. **Permission Denied**
   - Ensure proper file permissions on the Learning directory
   - Check Docker daemon is running with appropriate permissions

4. **Application Not Loading**
   - Verify `BASE_URL` is correctly set
   - Check web container logs: `docker-compose logs web`
   - Ensure all required environment variables are set

### Validation Commands

Check environment configuration:
```bash
# View current environment variables
docker-compose config

# Test database connection
docker-compose exec db mysql -u$DB_USER -p$DB_PASS $DB_NAME -e "SELECT 1;"

# Check application status
curl -f http://localhost:${APACHE_PORT:-8080}/
```