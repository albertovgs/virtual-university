# Health Checks and Monitoring

This document describes the health check and monitoring system implemented for the Virtual University Docker environment.

## Overview

The Virtual University application includes comprehensive health monitoring at multiple levels:

1. **Docker Container Health Checks** - Built into Docker Compose
2. **Application Health Endpoints** - Custom CodeIgniter controller
3. **System Monitoring Scripts** - Bash scripts for comprehensive monitoring
4. **Production Monitoring Service** - Systemd service for continuous monitoring

## Health Check Endpoints

### Basic Health Check
- **URL**: `http://localhost:8080/health`
- **Purpose**: Basic application availability check
- **Response**: JSON with status and timestamp

```json
{
  "status": "healthy",
  "timestamp": "2024-01-15 10:30:00",
  "service": "virtual-university"
}
```

### Database Health Check
- **URL**: `http://localhost:8080/health/database`
- **Purpose**: Database connectivity verification
- **Response**: JSON with database status

```json
{
  "status": "healthy",
  "database": "connected",
  "timestamp": "2024-01-15 10:30:00"
}
```

### Comprehensive Health Check
- **URL**: `http://localhost:8080/health/full`
- **Purpose**: Complete system health verification
- **Response**: JSON with detailed component status

```json
{
  "status": "healthy",
  "timestamp": "2024-01-15 10:30:00",
  "checks": {
    "database": {
      "status": "healthy",
      "message": "Database connection successful"
    },
    "filesystem": {
      "status": "healthy",
      "message": "File system writable"
    },
    "session": {
      "status": "healthy",
      "message": "Session system operational"
    }
  }
}
```

## Docker Health Checks

### Web Container
- **Test**: `curl -f http://localhost/health`
- **Interval**: 30 seconds
- **Timeout**: 10 seconds
- **Retries**: 5
- **Start Period**: 60 seconds

### Database Container
- **Test**: `mysqladmin ping -h localhost -u root -p[password]`
- **Interval**: 30 seconds
- **Timeout**: 20 seconds
- **Retries**: 5
- **Start Period**: 120 seconds

## Monitoring Scripts

### Health Check Script
Location: `scripts/health-check.sh`

```bash
# Run comprehensive health check
./scripts/health-check.sh

# Check specific components
WEB_URL=http://localhost:8080 ./scripts/health-check.sh
```

### Continuous Monitoring Script
Location: `scripts/monitor.sh`

```bash
# Run single monitoring cycle
./scripts/monitor.sh single

# Run continuous monitoring
./scripts/monitor.sh continuous
```

## Configuration

### Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `HEALTH_CHECK_INTERVAL` | 30 | Health check interval in seconds |
| `HEALTH_CHECK_TIMEOUT` | 10 | Health check timeout in seconds |
| `HEALTH_CHECK_RETRIES` | 5 | Number of retries before marking unhealthy |
| `DISK_THRESHOLD` | 85 | Disk usage alert threshold (%) |
| `MEMORY_THRESHOLD` | 90 | Memory usage alert threshold (%) |
| `AUTO_RESTART` | false | Enable automatic service restart |
| `ALERT_EMAIL` | - | Email address for alerts |
| `SLACK_WEBHOOK` | - | Slack webhook URL for alerts |

### Docker Compose Configurations

#### Development (docker-compose.yml + docker-compose.override.yml)
- Frequent health checks (15-30 seconds)
- Detailed logging
- Development-friendly settings

#### Production (docker-compose.prod.yml)
- Optimized health check intervals (60 seconds)
- Resource limits
- Enhanced security settings
- Production logging configuration

## Production Deployment

### 1. Install Monitoring Service

```bash
# Copy service file
sudo cp scripts/virtual-university-monitor.service /etc/systemd/system/

# Reload systemd
sudo systemctl daemon-reload

# Enable and start service
sudo systemctl enable virtual-university-monitor
sudo systemctl start virtual-university-monitor
```

### 2. Configure Alerts

Update your `.env` file:

```bash
ALERT_EMAIL=admin@yourdomain.com
SLACK_WEBHOOK=https://hooks.slack.com/services/YOUR/SLACK/WEBHOOK
AUTO_RESTART=true
```

### 3. Set Up Log Rotation

Create `/etc/logrotate.d/virtual-university`:

```
/var/log/virtual-university-monitor.log {
    daily
    rotate 30
    compress
    delaycompress
    missingok
    notifempty
    create 644 root root
}
```

## Monitoring Commands

### Check Container Status
```bash
# View container health status
docker compose ps

# View detailed container information
docker compose ps --format "table {{.Service}}\t{{.Status}}\t{{.Ports}}"

# Check container logs
docker compose logs web
docker compose logs db
```

### Manual Health Checks
```bash
# Test web service health
curl -f http://localhost:8080/health

# Test database health through application
curl -s http://localhost:8080/health/database | jq .

# Run comprehensive health check
curl -s http://localhost:8080/health/full | jq .
```

### Monitor System Resources
```bash
# Check disk usage
df -h

# Check memory usage
free -h

# Check Docker resource usage
docker stats
```

## Troubleshooting

### Common Issues

#### Health Check Failing
1. Check if containers are running: `docker compose ps`
2. Check container logs: `docker compose logs [service]`
3. Verify network connectivity: `docker network ls`
4. Test health endpoints manually: `curl http://localhost:8080/health`

#### Database Connection Issues
1. Verify database container is healthy: `docker compose ps db`
2. Check database logs: `docker compose logs db`
3. Test database connection: `docker compose exec db mysql -u root -p`
4. Verify environment variables: `docker compose config`

#### High Resource Usage
1. Check container resource usage: `docker stats`
2. Review application logs for errors
3. Consider scaling or optimizing queries
4. Monitor disk space: `df -h`

### Log Locations

- **Application Logs**: `docker compose logs web`
- **Database Logs**: `docker compose logs db`
- **Monitoring Logs**: `/var/log/virtual-university-monitor.log`
- **System Logs**: `/var/log/syslog` or `journalctl -u virtual-university-monitor`

## Best Practices

1. **Regular Monitoring**: Set up automated monitoring in production
2. **Alert Configuration**: Configure email/Slack alerts for critical issues
3. **Log Management**: Implement log rotation and retention policies
4. **Resource Limits**: Set appropriate CPU and memory limits
5. **Backup Strategy**: Regular database backups with health verification
6. **Security Updates**: Keep Docker images and system packages updated
7. **Performance Monitoring**: Monitor response times and database performance

## Integration with External Monitoring

### Prometheus Integration
The health endpoints can be easily integrated with Prometheus for metrics collection:

```yaml
# prometheus.yml
scrape_configs:
  - job_name: 'virtual-university'
    static_configs:
      - targets: ['localhost:8080']
    metrics_path: '/health/full'
    scrape_interval: 30s
```

### Nagios Integration
Create Nagios check commands using the health check script:

```bash
# Nagios command definition
define command{
    command_name    check_virtual_university
    command_line    /opt/virtual-university/scripts/health-check.sh
}
```

### Grafana Dashboard
Use the health endpoints to create comprehensive Grafana dashboards showing:
- Application availability
- Database connectivity
- Response times
- Resource usage
- Error rates