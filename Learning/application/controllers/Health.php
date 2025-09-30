<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Health Check Controller - Enhanced Version
 * 
 * Provides comprehensive health check endpoints for Docker container monitoring
 * with performance metrics and detailed diagnostics
 * 
 * @author Virtual University Team
 * @version 2.0
 */
class Health extends CI_Controller {

    private $start_time;
    private $memory_start;

    public function __construct()
    {
        parent::__construct();
        $this->start_time = microtime(true);
        $this->memory_start = memory_get_usage();
        
        // Load required libraries
        $this->load->database();
        $this->load->driver('cache', ['adapter' => 'file']);
        
        // Set JSON headers by default
        $this->output->set_content_type('application/json');
    }

    /**
     * Basic health check endpoint with performance metrics
     * Returns HTTP 200 if application is running
     */
    public function index()
    {
        $response = [
            'status' => 'healthy',
            'timestamp' => date('c'), // ISO 8601 format
            'service' => 'virtual-university',
            'version' => '2.0',
            'environment' => ENVIRONMENT,
            'performance' => $this->_get_performance_metrics()
        ];

        $this->output
            ->set_status_header(200)
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }

    /**
     * Database health check endpoint with connection pool info
     * Tests database connectivity and returns detailed status
     */
    public function database()
    {
        $start_time = microtime(true);
        
        try {
            // Test basic connectivity
            $this->db->query('SELECT 1 as test');
            
            // Test database operations
            $db_info = $this->_get_database_info();
            $query_time = round((microtime(true) - $start_time) * 1000, 2);
            
            $response = [
                'status' => 'healthy',
                'database' => 'connected',
                'timestamp' => date('c'),
                'metrics' => [
                    'query_time_ms' => $query_time,
                    'connection_info' => $db_info
                ]
            ];
            
            $this->output
                ->set_status_header(200)
                ->set_output(json_encode($response, JSON_PRETTY_PRINT));
                
        } catch (Exception $e) {
            $query_time = round((microtime(true) - $start_time) * 1000, 2);
            
            $response = [
                'status' => 'unhealthy',
                'database' => 'disconnected',
                'error' => $e->getMessage(),
                'timestamp' => date('c'),
                'metrics' => [
                    'query_time_ms' => $query_time
                ]
            ];
            
            log_message('error', 'Database health check failed: ' . $e->getMessage());
            
            $this->output
                ->set_status_header(503)
                ->set_output(json_encode($response, JSON_PRETTY_PRINT));
        }
    }

    /**
     * Comprehensive health check with detailed diagnostics
     * Tests all critical components and system resources
     */
    public function full()
    {
        $health_status = 'healthy';
        $http_status = 200;
        $checks = [];
        $metrics = [];

        // Database connectivity check
        $checks['database'] = $this->_check_database();
        if ($checks['database']['status'] === 'unhealthy') {
            $health_status = 'unhealthy';
            $http_status = 503;
        }

        // File system checks
        $checks['filesystem'] = $this->_check_filesystem();
        if ($checks['filesystem']['status'] === 'warning' && $health_status === 'healthy') {
            $health_status = 'degraded';
        }

        // Session system check
        $checks['session'] = $this->_check_session();
        if ($checks['session']['status'] === 'warning' && $health_status === 'healthy') {
            $health_status = 'degraded';
        }

        // Cache system check
        $checks['cache'] = $this->_check_cache();
        if ($checks['cache']['status'] === 'warning' && $health_status === 'healthy') {
            $health_status = 'degraded';
        }

        // System resources check
        $checks['system'] = $this->_check_system_resources();
        if ($checks['system']['status'] === 'warning' && $health_status === 'healthy') {
            $health_status = 'degraded';
        }

        // Application-specific checks
        $checks['application'] = $this->_check_application();
        if ($checks['application']['status'] === 'warning' && $health_status === 'healthy') {
            $health_status = 'degraded';
        }

        $response = [
            'status' => $health_status,
            'timestamp' => date('c'),
            'service' => 'virtual-university',
            'version' => '2.0',
            'environment' => ENVIRONMENT,
            'checks' => $checks,
            'performance' => $this->_get_performance_metrics(),
            'system_info' => $this->_get_system_info()
        ];

        $this->output
            ->set_status_header($http_status)
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }

    /**
     * Readiness probe for Kubernetes/Docker orchestration
     */
    public function ready()
    {
        $ready = true;
        $checks = [];

        // Essential services that must be ready
        $database_check = $this->_check_database();
        $checks['database'] = $database_check;
        if ($database_check['status'] !== 'healthy') {
            $ready = false;
        }

        $response = [
            'ready' => $ready,
            'timestamp' => date('c'),
            'checks' => $checks
        ];

        $status_code = $ready ? 200 : 503;
        $this->output
            ->set_status_header($status_code)
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }

    /**
     * Liveness probe for Kubernetes/Docker orchestration
     */
    public function live()
    {
        // Basic liveness check - if we can respond, we're alive
        $response = [
            'alive' => true,
            'timestamp' => date('c'),
            'uptime' => $this->_get_uptime()
        ];

        $this->output
            ->set_status_header(200)
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }

    /**
     * Check database connectivity and performance
     */
    private function _check_database()
    {
        $start_time = microtime(true);
        
        try {
            // Test connection
            $this->db->query('SELECT 1');
            
            // Test a simple table query
            $this->db->limit(1);
            $this->db->get('tb_users');
            
            $query_time = round((microtime(true) - $start_time) * 1000, 2);
            
            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
                'metrics' => [
                    'query_time_ms' => $query_time,
                    'connection_info' => $this->_get_database_info()
                ]
            ];
            
        } catch (Exception $e) {
            log_message('error', 'Database health check failed: ' . $e->getMessage());
            return [
                'status' => 'unhealthy',
                'message' => 'Database connection failed: ' . $e->getMessage(),
                'metrics' => [
                    'query_time_ms' => round((microtime(true) - $start_time) * 1000, 2)
                ]
            ];
        }
    }

    /**
     * Check filesystem permissions and disk space
     */
    private function _check_filesystem()
    {
        $issues = [];
        
        // Check critical directories
        $directories = [
            'uploads' => APPPATH . '../uploads',
            'cache' => APPPATH . 'cache',
            'logs' => APPPATH . 'logs'
        ];

        foreach ($directories as $name => $path) {
            if (!file_exists($path)) {
                if (!mkdir($path, 0755, true)) {
                    $issues[] = "Cannot create {$name} directory: {$path}";
                }
            } elseif (!is_writable($path)) {
                $issues[] = "{$name} directory not writable: {$path}";
            }
        }

        // Check disk space
        $disk_free = disk_free_space('.');
        $disk_total = disk_total_space('.');
        $disk_usage = round((($disk_total - $disk_free) / $disk_total) * 100, 2);

        if ($disk_usage > 90) {
            $issues[] = "Disk usage critical: {$disk_usage}%";
        } elseif ($disk_usage > 80) {
            $issues[] = "Disk usage high: {$disk_usage}%";
        }

        $status = empty($issues) ? 'healthy' : 'warning';
        $message = empty($issues) ? 'Filesystem checks passed' : implode(', ', $issues);

        return [
            'status' => $status,
            'message' => $message,
            'metrics' => [
                'disk_usage_percent' => $disk_usage,
                'disk_free_bytes' => $disk_free,
                'disk_total_bytes' => $disk_total
            ]
        ];
    }

    /**
     * Check session system functionality
     */
    private function _check_session()
    {
        try {
            $this->load->library('session');
            
            // Test session write/read
            $test_key = 'health_check_' . uniqid();
            $test_value = 'test_' . time();
            
            $this->session->set_userdata($test_key, $test_value);
            $retrieved = $this->session->userdata($test_key);
            $this->session->unset_userdata($test_key);
            
            if ($retrieved === $test_value) {
                return [
                    'status' => 'healthy',
                    'message' => 'Session system operational'
                ];
            } else {
                return [
                    'status' => 'warning',
                    'message' => 'Session read/write test failed'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'status' => 'warning',
                'message' => 'Session system issue: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check cache system functionality
     */
    private function _check_cache()
    {
        try {
            $test_key = 'health_check_' . uniqid();
            $test_value = 'cache_test_' . time();
            
            // Test cache write/read
            $this->cache->save($test_key, $test_value, 60);
            $retrieved = $this->cache->get($test_key);
            $this->cache->delete($test_key);
            
            if ($retrieved === $test_value) {
                return [
                    'status' => 'healthy',
                    'message' => 'Cache system operational'
                ];
            } else {
                return [
                    'status' => 'warning',
                    'message' => 'Cache read/write test failed'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'status' => 'warning',
                'message' => 'Cache system issue: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check system resources (memory, CPU)
     */
    private function _check_system_resources()
    {
        $issues = [];
        
        // Memory usage
        $memory_usage = memory_get_usage(true);
        $memory_peak = memory_get_peak_usage(true);
        $memory_limit = $this->_parse_size(ini_get('memory_limit'));
        
        if ($memory_limit > 0) {
            $memory_percent = round(($memory_usage / $memory_limit) * 100, 2);
            if ($memory_percent > 90) {
                $issues[] = "Memory usage critical: {$memory_percent}%";
            } elseif ($memory_percent > 80) {
                $issues[] = "Memory usage high: {$memory_percent}%";
            }
        }

        // Load average (Linux only)
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            if ($load[0] > 5) {
                $issues[] = "High system load: {$load[0]}";
            }
        }

        $status = empty($issues) ? 'healthy' : 'warning';
        $message = empty($issues) ? 'System resources normal' : implode(', ', $issues);

        return [
            'status' => $status,
            'message' => $message,
            'metrics' => [
                'memory_usage_bytes' => $memory_usage,
                'memory_peak_bytes' => $memory_peak,
                'memory_limit_bytes' => $memory_limit,
                'load_average' => function_exists('sys_getloadavg') ? sys_getloadavg() : null
            ]
        ];
    }

    /**
     * Check application-specific functionality
     */
    private function _check_application()
    {
        $issues = [];
        
        try {
            // Check if main tables exist
            $required_tables = ['tb_users', 'tb_people', 'tb_majors'];
            foreach ($required_tables as $table) {
                if (!$this->db->table_exists($table)) {
                    $issues[] = "Required table missing: {$table}";
                }
            }

            // Check configuration
            if (empty($this->config->item('base_url'))) {
                $issues[] = "Base URL not configured";
            }

            if (empty($this->config->item('encryption_key'))) {
                $issues[] = "Encryption key not set";
            }

        } catch (Exception $e) {
            $issues[] = "Application check failed: " . $e->getMessage();
        }

        $status = empty($issues) ? 'healthy' : 'warning';
        $message = empty($issues) ? 'Application checks passed' : implode(', ', $issues);

        return [
            'status' => $status,
            'message' => $message
        ];
    }

    /**
     * Get performance metrics
     */
    private function _get_performance_metrics()
    {
        return [
            'response_time_ms' => round((microtime(true) - $this->start_time) * 1000, 2),
            'memory_usage_bytes' => memory_get_usage(true) - $this->memory_start,
            'memory_peak_bytes' => memory_get_peak_usage(true),
            'included_files_count' => count(get_included_files())
        ];
    }

    /**
     * Get database connection information
     */
    private function _get_database_info()
    {
        try {
            $version_query = $this->db->query("SELECT VERSION() as version");
            $version = $version_query->row()->version ?? 'unknown';
            
            return [
                'driver' => $this->db->dbdriver,
                'version' => $version,
                'hostname' => $this->db->hostname,
                'database' => $this->db->database
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get system information
     */
    private function _get_system_info()
    {
        return [
            'php_version' => PHP_VERSION,
            'codeigniter_version' => CI_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
            'operating_system' => PHP_OS,
            'server_time' => date('c'),
            'timezone' => date_default_timezone_get()
        ];
    }

    /**
     * Get application uptime (approximation)
     */
    private function _get_uptime()
    {
        // This is a simple approximation - in production you might want to store start time
        $cache_key = 'app_start_time';
        $start_time = $this->cache->get($cache_key);
        
        if ($start_time === FALSE) {
            $start_time = time();
            $this->cache->save($cache_key, $start_time, 86400); // 24 hours
        }
        
        return time() - $start_time;
    }

    /**
     * Parse memory size string to bytes
     */
    private function _parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        
        return round($size);
    }
}