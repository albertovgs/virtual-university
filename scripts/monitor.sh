#!/bin/bash

# Continuous Monitoring Script for Virtual University
# This script can be run periodically to monitor the health of the application

set -e

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOG_FILE="${LOG_FILE:-/var/log/virtual-university-monitor.log}"
ALERT_EMAIL="${ALERT_EMAIL:-}"
SLACK_WEBHOOK="${SLACK_WEBHOOK:-}"
CHECK_INTERVAL="${CHECK_INTERVAL:-300}"  # 5 minutes default

# Ensure log directory exists
mkdir -p "$(dirname "$LOG_FILE")"

# Function to log messages
log_message() {
    local level=$1
    local message=$2
    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    echo "[$timestamp] [$level] $message" | tee -a "$LOG_FILE"
}

# Function to send alerts
send_alert() {
    local subject=$1
    local message=$2
    
    # Email alert
    if [ -n "$ALERT_EMAIL" ] && command -v mail > /dev/null 2>&1; then
        echo "$message" | mail -s "$subject" "$ALERT_EMAIL"
        log_message "INFO" "Alert email sent to $ALERT_EMAIL"
    fi
    
    # Slack alert
    if [ -n "$SLACK_WEBHOOK" ] && command -v curl > /dev/null 2>&1; then
        curl -X POST -H 'Content-type: application/json' \
            --data "{\"text\":\"$subject: $message\"}" \
            "$SLACK_WEBHOOK" > /dev/null 2>&1
        log_message "INFO" "Alert sent to Slack"
    fi
}

# Function to check container status
check_containers() {
    local failed_containers=()
    
    if command -v docker > /dev/null 2>&1; then
        # Check if containers are running
        local containers=("web" "db")
        
        for container in "${containers[@]}"; do
            local status=$(docker compose ps --services --filter "status=running" | grep -c "$container" || echo "0")
            if [ "$status" -eq 0 ]; then
                failed_containers+=("$container")
                log_message "ERROR" "Container $container is not running"
            fi
        done
        
        # Check health status
        for container in "${containers[@]}"; do
            local health=$(docker compose ps --format "table {{.Service}}\t{{.Status}}" | grep "$container" | grep -o "unhealthy" || echo "")
            if [ "$health" = "unhealthy" ]; then
                failed_containers+=("$container (unhealthy)")
                log_message "ERROR" "Container $container is unhealthy"
            fi
        done
    fi
    
    if [ ${#failed_containers[@]} -gt 0 ]; then
        local failed_list=$(IFS=', '; echo "${failed_containers[*]}")
        send_alert "Virtual University Container Alert" "Failed containers: $failed_list"
        return 1
    fi
    
    return 0
}

# Function to check application health
check_application() {
    if ! "$SCRIPT_DIR/health-check.sh" > /dev/null 2>&1; then
        log_message "ERROR" "Application health check failed"
        send_alert "Virtual University Health Alert" "Application health check failed. Please check the logs for details."
        return 1
    fi
    
    log_message "INFO" "Application health check passed"
    return 0
}

# Function to check disk space
check_disk_space() {
    local threshold=${DISK_THRESHOLD:-85}  # 85% default threshold
    local usage=$(df / | awk 'NR==2 {print $5}' | sed 's/%//')
    
    if [ "$usage" -gt "$threshold" ]; then
        log_message "WARNING" "Disk usage is at ${usage}% (threshold: ${threshold}%)"
        send_alert "Virtual University Disk Space Alert" "Disk usage is at ${usage}% on the server"
        return 1
    fi
    
    return 0
}

# Function to check memory usage
check_memory() {
    local threshold=${MEMORY_THRESHOLD:-90}  # 90% default threshold
    
    if command -v free > /dev/null 2>&1; then
        local usage=$(free | awk 'NR==2{printf "%.0f", $3*100/$2}')
        
        if [ "$usage" -gt "$threshold" ]; then
            log_message "WARNING" "Memory usage is at ${usage}% (threshold: ${threshold}%)"
            send_alert "Virtual University Memory Alert" "Memory usage is at ${usage}% on the server"
            return 1
        fi
    fi
    
    return 0
}

# Function to restart services if needed
restart_services() {
    if [ "$AUTO_RESTART" = "true" ]; then
        log_message "INFO" "Attempting to restart services"
        
        if docker compose restart; then
            log_message "INFO" "Services restarted successfully"
            send_alert "Virtual University Recovery" "Services were automatically restarted and are now healthy"
        else
            log_message "ERROR" "Failed to restart services"
            send_alert "Virtual University Critical Alert" "Automatic service restart failed. Manual intervention required."
        fi
    fi
}

# Main monitoring function
run_monitoring() {
    log_message "INFO" "Starting monitoring cycle"
    
    local checks_failed=0
    
    # Run all checks
    check_containers || checks_failed=$((checks_failed + 1))
    check_application || checks_failed=$((checks_failed + 1))
    check_disk_space || checks_failed=$((checks_failed + 1))
    check_memory || checks_failed=$((checks_failed + 1))
    
    if [ $checks_failed -gt 0 ]; then
        log_message "WARNING" "$checks_failed checks failed"
        
        # Attempt restart if configured
        if [ "$checks_failed" -le 2 ]; then  # Only restart for minor issues
            restart_services
        fi
    else
        log_message "INFO" "All monitoring checks passed"
    fi
    
    log_message "INFO" "Monitoring cycle completed"
}

# Continuous monitoring mode
continuous_monitoring() {
    log_message "INFO" "Starting continuous monitoring (interval: ${CHECK_INTERVAL}s)"
    
    while true; do
        run_monitoring
        sleep "$CHECK_INTERVAL"
    done
}

# Main execution
main() {
    case "${1:-single}" in
        "continuous")
            continuous_monitoring
            ;;
        "single")
            run_monitoring
            ;;
        *)
            echo "Usage: $0 [single|continuous]"
            echo "  single     - Run monitoring checks once"
            echo "  continuous - Run monitoring checks continuously"
            exit 1
            ;;
    esac
}

# Run main function if script is executed directly
if [ "${BASH_SOURCE[0]}" == "${0}" ]; then
    main "$@"
fi