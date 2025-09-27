<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Health Check Controller
 * 
 * Provides health check endpoints for Docker container monitoring
 */
class Health extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Basic health check endpoint
     * Returns HTTP 200 if application is running
     */
    public function index()
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'healthy',
                'timestamp' => date('Y-m-d H:i:s'),
                'service' => 'virtual-university'
            ]));
    }

    /**
     * Database health check endpoint
     * Tests database connectivity and returns status
     */
    public function database()
    {
        try {
            // Test database connection
            $this->db->query('SELECT 1');
            
            $response = [
                'status' => 'healthy',
                'database' => 'connected',
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
                
        } catch (Exception $e) {
            $response = [
                'status' => 'unhealthy',
                'database' => 'disconnected',
                'error' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $this->output
                ->set_status_header(503)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }

    /**
     * Comprehensive health check
     * Tests all critical components
     */
    public function full()
    {
        $health_status = 'healthy';
        $http_status = 200;
        $checks = [];

        // Check database connectivity
        try {
            $this->db->query('SELECT 1');
            $checks['database'] = [
                'status' => 'healthy',
                'message' => 'Database connection successful'
            ];
        } catch (Exception $e) {
            $health_status = 'unhealthy';
            $http_status = 503;
            $checks['database'] = [
                'status' => 'unhealthy',
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
        }

        // Check file system permissions
        $upload_path = APPPATH . '../uploads';
        if (is_writable($upload_path) || !file_exists($upload_path)) {
            $checks['filesystem'] = [
                'status' => 'healthy',
                'message' => 'File system writable'
            ];
        } else {
            $health_status = 'degraded';
            $checks['filesystem'] = [
                'status' => 'warning',
                'message' => 'Upload directory not writable'
            ];
        }

        // Check session functionality
        try {
            $this->load->library('session');
            $checks['session'] = [
                'status' => 'healthy',
                'message' => 'Session system operational'
            ];
        } catch (Exception $e) {
            $health_status = 'degraded';
            $checks['session'] = [
                'status' => 'warning',
                'message' => 'Session system issue: ' . $e->getMessage()
            ];
        }

        $response = [
            'status' => $health_status,
            'timestamp' => date('Y-m-d H:i:s'),
            'checks' => $checks
        ];

        $this->output
            ->set_status_header($http_status)
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}