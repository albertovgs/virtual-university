# Virtual University - Docker Setup Guide

## Quick Start

Get the Virtual University application running in minutes with Docker:

```bash
# Clone the repository
git clone <repository-url>
cd virtual-university

# Start the application
docker-compose up -d

# Access the application
open http://localhost:8080
```

**Default Login Credentials:**
- **Email:** `admin@learning.edu`
- **Password:** `Hacker`
- **User ID:** `10000001`

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Default Credentials](#default-credentials)
- [Troubleshooting](#troubleshooting)
- [Production Deployment](#production-deployment)
- [Monitoring and Health Checks](#monitoring-and-health-checks)
- [Development Workflow](#development-workflow)
- [Backup and Maintenance](#backup-and-maintenance)

## Prerequisites

### Required Software
- **Docker**: Version 20.10 or higher
- **Docker Compose**: Version 2.0 or higher
- **Git**: For cloning the repository

### System Requirements
- **RAM**: Minimum 2GB, recommended 4GB
- **Disk Space**: Minimum 5GB free space
- **Ports**: 8080 (web) and 3306 (database) should be available

### Installation of Prerequisites

#### Ubuntu/Debian
```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo apt-get update
sudo apt-get install docker-compose-plugin

# Add user to docker group
sudo usermod -aG docker $USER
newgrp docker
```

#### macOS
```bash
# Install Docker Desktop
brew install --cask docker

# Or download from: https://www.docker.com/products/docker-desktop
```

#### Windows
Download and install Docker Desktop from: https://www.docker.com/products/docker-desktop

## Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd virtual-university
```

### 2. Environment Configuration
```bash
# Copy the example environment file
cp .env.example .env

# (Optional) Customize environment variables
nano .env
```

### 3. Start the Application
```bash
# Start all services in detached mode
docker-compose up -d

# View startup logs (optional)
docker-compose logs -f
```

### 4. Verify Installation
```bash
# Check container status
docker-compose ps

# Test application access
curl http://localhost:8080

# Test health endpoint
curl http://localhost:8080/health
```

## Configuration

### Environment Variables

The application uses environment variables for configuration. Key variables include:

| Variable | Default | Description |
|----------|---------|-------------|
| `DB_HOST` | `db` | Database hostname |
| `DB_USER` | `learning_user` | Database username |
| `DB_PASS` | `learning_pass` | Database password |
| `DB_NAME` | `sw15_update` | Database name |
| `CI_ENV` | `development` | CodeIgniter environment |
| `APACHE_PORT` | `8080` | Host port for web access |

For a complete list of environment variables, see [ENVIRONMENT-VARIABLES.md](ENVIRONMENT-VARIABLES.md).

### Custom Configuration

#### Change Web Port
```bash
# Edit .env file
echo "APACHE_PORT=9000" >> .env

# Restart services
docker-compose down
docker-compose up -d
```

#### Use External Database
```bash
# Edit .env file
DB_HOST=your-external-db-host.com
DB_USER=your-db-user
DB_PASS=your-db-password
DB_NAME=your-db-name

# Remove database service from docker-compose.yml
# Restart web service only
docker-compose up -d web
```

## Usage

### Accessing the Application

1. **Web Interface**: http://localhost:8080
2. **Health Check**: http://localhost:8080/health
3. **Database Health**: http://localhost:8080/health/database

### Basic Operations

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs web
docker-compose logs db

# Restart a specific service
docker-compose restart web

# Update and restart
git pull
docker-compose down
docker-compose up -d --build
```

### Development Mode

For active development with file watching:

```bash
# Start with logs visible
docker-compose up

# In another terminal, make changes to files in Learning/
# Changes will be reflected immediately
```

## Default Credentials

The application comes with pre-configured users for immediate testing:

### Administrator Account
- **Email:** `admin@learning.edu`
- **Password:** `Hacker`
- **User ID:** `10000001`
- **Role:** Admin
- **Full Name:** Admin Learning

### Database Access
- **Host:** `localhost:3306` (from host machine)
- **Username:** `learning_user`
- **Password:** `learning_pass`
- **Database:** `sw15_update`
- **Root Password:** `root_password`

### Pre-configured Classrooms
The system includes 10 pre-configured classrooms:
- Lab-1, Lab-2, Lab-3, Lab-4
- Class Room-1 through Class Room-6

**⚠️ Security Warning:** Change default passwords before deploying to production!

## Troubleshooting

### Common Issues

#### Port Already in Use
```bash
# Error: port is already allocated
# Solution: Change the port in .env
echo "APACHE_PORT=9000" >> .env
docker-compose down
docker-compose up -d
```

#### Database Connection Failed
```bash
# Check database container status
docker-compose ps db

# Check database logs
docker-compose logs db

# Test database connection
docker-compose exec db mysql -u learning_user -p learning_pass sw15_update
```

#### Permission Denied Errors
```bash
# Fix file permissions (Linux/macOS)
sudo chown -R $USER:$USER Learning/
chmod -R 755 Learning/

# For Docker Desktop on Windows, ensure file sharing is enabled
```

#### Application Not Loading
```bash
# Check web container logs
docker-compose logs web

# Verify container health
docker-compose ps

# Test health endpoint
curl -v http://localhost:8080/health
```

#### Out of Disk Space
```bash
# Clean up Docker resources
docker system prune -a

# Remove unused volumes
docker volume prune

# Check disk usage
df -h
```

### Debugging Commands

```bash
# Enter web container shell
docker-compose exec web bash

# Enter database container shell
docker-compose exec db mysql -u root -p

# View real-time logs
docker-compose logs -f web

# Check container resource usage
docker stats

# Inspect container configuration
docker-compose config
```

### Log Locations

- **Application Logs**: `docker-compose logs web`
- **Database Logs**: `docker-compose logs db`
- **Apache Error Logs**: Inside web container at `/var/log/apache2/error.log`
- **PHP Error Logs**: Check CodeIgniter logs in `Learning/application/logs/`

## Production Deployment

### Security Checklist

Before deploying to production:

- [ ] Change all default passwords
- [ ] Set `CI_ENV=production` in `.env`
- [ ] Generate secure encryption key
- [ ] Use HTTPS with proper SSL certificates
- [ ] Configure firewall rules
- [ ] Set up regular backups
- [ ] Enable monitoring and alerting
- [ ] Review and limit exposed ports

### Production Configuration

1. **Create Production Environment File**
```bash
cp .env.example .env.production

# Edit with production values
nano .env.production
```

2. **Use Production Docker Compose**
```bash
# Use production configuration
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

3. **SSL/HTTPS Setup**
```bash
# Add SSL certificates to nginx or use a reverse proxy
# Example with nginx:
# - Mount certificates to web container
# - Configure Apache for HTTPS
# - Redirect HTTP to HTTPS
```

### External Database Setup

For production, consider using an external database:

```bash
# Update .env for external database
DB_HOST=your-production-db.amazonaws.com
DB_USER=production_user
DB_PASS=secure_production_password
DB_NAME=virtual_university_prod

# Remove database service from production compose
# Use only web service
docker-compose -f docker-compose.prod.yml up -d web
```

### Scaling Considerations

```bash
# Scale web service for high availability
docker-compose up -d --scale web=3

# Use load balancer (nginx example)
# Configure nginx to distribute load across containers
```

## Monitoring and Health Checks

### Built-in Health Checks

The application includes comprehensive health monitoring:

```bash
# Basic health check
curl http://localhost:8080/health

# Database connectivity check
curl http://localhost:8080/health/database

# Comprehensive system check
curl http://localhost:8080/health/full
```

### Container Health Status

```bash
# View container health status
docker-compose ps

# Monitor container resources
docker stats

# Check container logs for errors
docker-compose logs --tail=50 web
```

### Production Monitoring

For production environments, set up:

1. **Automated Health Monitoring**
```bash
# Install monitoring service
sudo cp scripts/virtual-university-monitor.service /etc/systemd/system/
sudo systemctl enable virtual-university-monitor
sudo systemctl start virtual-university-monitor
```

2. **Log Monitoring**
```bash
# Set up log rotation
sudo cp scripts/logrotate.conf /etc/logrotate.d/virtual-university
```

3. **Alerting**
Configure email or Slack alerts in your `.env` file:
```bash
ALERT_EMAIL=admin@yourdomain.com
SLACK_WEBHOOK=https://hooks.slack.com/services/YOUR/WEBHOOK
```

For detailed monitoring setup, see [docs/HEALTH-MONITORING.md](docs/HEALTH-MONITORING.md).

## Development Workflow

### File Synchronization

The Docker setup includes automatic file synchronization:

- **Learning Directory**: Mounted as volume for real-time updates
- **Database Changes**: Require container restart
- **Configuration Changes**: May require service restart

### Development Commands

```bash
# Start development environment
docker-compose up

# Run database migrations (if any)
docker-compose exec web php Learning/index.php migrate

# Access database directly
docker-compose exec db mysql -u learning_user -p sw15_update

# View application logs in real-time
docker-compose logs -f web

# Restart specific service
docker-compose restart web
```

### Code Changes

1. **PHP Files**: Changes are reflected immediately
2. **Database Schema**: Use migration scripts or restart database
3. **Apache Configuration**: Restart web container
4. **Environment Variables**: Restart affected containers

### Testing

The project includes comprehensive testing scripts to validate your Docker environment:

```bash
# Run all tests (recommended)
./scripts/run-all-tests.sh

# Run individual test suites
./scripts/test-database.sh        # Database connectivity and initialization
./scripts/test-application.sh     # Web server and CodeIgniter functionality  
./scripts/test-file-sync.sh       # File synchronization and hot-reload

# Quick database test
./test-db-init.sh
```

#### Test Coverage

1. **Database Tests** (`test-database.sh`)
   - Database connectivity from web container
   - Schema initialization and table structure
   - Default data and admin user creation
   - Character set configuration (utf8mb4)
   - CodeIgniter database configuration

2. **Application Tests** (`test-application.sh`)
   - HTTP connectivity and response times
   - PHP 7.4 and required extensions
   - Apache configuration and mod_rewrite
   - CodeIgniter framework functionality
   - File permissions and error handling
   - Health endpoint validation

3. **File Synchronization Tests** (`test-file-sync.sh`)
   - Basic file synchronization between host and container
   - Real-time file modification detection
   - CodeIgniter controller hot-reload
   - Directory synchronization
   - Permission synchronization

#### Running Tests

```bash
# Make scripts executable (if needed)
chmod +x scripts/*.sh test-db-init.sh

# Run comprehensive test suite
./scripts/run-all-tests.sh

# Expected output for successful tests:
# ✅ All database tests passed!
# ✅ All application tests passed!  
# ✅ All file synchronization tests passed!
```

#### Test Requirements

- Docker containers must be running (`docker-compose up -d`)
- Port 8080 must be accessible
- `curl` command must be available
- Sufficient disk space for test files

#### Troubleshooting Tests

If tests fail:

```bash
# Check container status
docker-compose ps

# View container logs
docker-compose logs web
docker-compose logs db

# Restart containers
docker-compose restart

# Run individual tests for detailed output
./scripts/test-database.sh
```

## Backup and Maintenance

### Database Backup

```bash
# Create database backup
docker-compose exec db mysqldump -u learning_user -p learning_pass sw15_update > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore database backup
docker-compose exec -T db mysql -u learning_user -p learning_pass sw15_update < backup_file.sql
```

### Application Backup

```bash
# Backup application files
tar -czf virtual_university_backup_$(date +%Y%m%d).tar.gz Learning/

# Backup entire Docker setup
tar -czf full_backup_$(date +%Y%m%d).tar.gz . --exclude='.git' --exclude='*.log'
```

### Maintenance Tasks

```bash
# Update Docker images
docker-compose pull
docker-compose up -d --build

# Clean up Docker resources
docker system prune -f

# Update application code
git pull
docker-compose restart web

# Rotate logs
docker-compose logs --tail=0 web > /dev/null
```

### Automated Maintenance

Create a maintenance script:

```bash
#!/bin/bash
# maintenance.sh

# Backup database
docker-compose exec db mysqldump -u learning_user -p learning_pass sw15_update > "backup_$(date +%Y%m%d).sql"

# Clean up old backups (keep last 7 days)
find . -name "backup_*.sql" -mtime +7 -delete

# Update application
git pull
docker-compose up -d --build

# Clean up Docker resources
docker system prune -f

echo "Maintenance completed at $(date)"
```

## Support and Resources

### Documentation
- [Environment Variables](ENVIRONMENT-VARIABLES.md)
- [Health Monitoring](docs/HEALTH-MONITORING.md)
- [Original README](README.md)

### Getting Help

1. **Check Logs**: Always start with `docker-compose logs`
2. **Health Checks**: Use `/health` endpoints to diagnose issues
3. **Community**: Check GitHub issues and discussions
4. **Documentation**: Review all documentation files

### Useful Commands Reference

```bash
# Quick status check
docker-compose ps && curl -s http://localhost:8080/health

# Complete restart
docker-compose down && docker-compose up -d

# Emergency stop
docker-compose kill

# View all logs
docker-compose logs

# Update everything
git pull && docker-compose down && docker-compose up -d --build
```

---

**Need help?** Check the troubleshooting section above or create an issue in the project repository.