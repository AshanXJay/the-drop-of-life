<?php
/**
 * Database Connection Class for The Drop of Life
 * 
 * This file provides a secure database connection using PDO with prepared statements
 * to prevent SQL injection attacks. It follows PHP best practices for database connectivity.
 * 
 * @author ashan
 * @version 1.0
 * @since 2025-07-31
 */

// Prevent direct access to this file
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

/**
 * Database Configuration Class
 * Handles secure database connections with error handling
 */
class Database {
    
    // Database configuration constants
    private const DB_HOST = 'bzkd4iprnmfkfobin9cj-mysql.services.clever-cloud.com';
    private const DB_NAME = 'bzkd4iprnmfkfobin9cj';
    private const DB_USER = 'ukvwmh3rzlm2bgbn';
    private const DB_PASS = 'CZiCDHohLI99binN4A5N';
    
    // PDO connection instance
    private static $connection = null;
    private static $instance = null;
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {}
    
    /**
     * Get singleton instance of Database class
     * 
     * @return Database Database instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get database connection using PDO with security configurations
     * 
     * @return PDO Database connection object
     * @throws Exception If connection fails
     */
    public function getConnection() {
        
        // Return existing connection if available
        if (self::$connection !== null) {
            return self::$connection;
        }
        
        try {
            // PDO options for security and performance
            $options = [
                // Enable prepared statement emulation for better security
                PDO::ATTR_EMULATE_PREPARES => false,
                
                // Set default fetch mode to associative array
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                
                // Set error mode to exceptions
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                
                // Additional security settings
                PDO::ATTR_AUTOCOMMIT => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode='STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO'",
                PDO::ATTR_TIMEOUT => 30
            ];
            
            // Create DSN (Data Source Name)
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
            
            // Create PDO connection
            self::$connection = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
            
            // Enable autocommit for normal operations
            self::$connection->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
            
            return self::$connection;
            
        } catch (PDOException $e) {
            // Log error details (never expose database credentials in error messages)
            error_log("Database connection failed: " . $e->getMessage());
            
            // Log security event
            self::logSecurityEvent("Database connection failure", [
                'error_code' => $e->getCode(),
                'timestamp' => date('Y-m-d H:i:s'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            
            // Throw a generic error to avoid exposing sensitive information
            throw new Exception("We're experiencing technical difficulties. Please try again later.");
        }
    }
    
    /**
     * Static method to get database connection (for backward compatibility)
     * 
     * @return PDO Database connection object
     * @throws Exception If connection fails
     */
    public static function getConnectionStatic() {
        return self::getInstance()->getConnection();
    }
    
    /**
     * Close database connection
     */
    public static function closeConnection() {
        self::$connection = null;
    }
    
    /**
     * Sanitize input to prevent XSS attacks
     * 
     * @param string $input The input string to sanitize
     * @return string Sanitized string
     */
    public static function sanitizeInput($input) {
        if (is_string($input)) {
            // Remove any potential script tags and convert special characters
            $input = strip_tags($input);
            $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $input = trim($input);
        }
        return $input;
    }
    
    /**
     * Validate email format
     * 
     * @param string $email Email to validate
     * @return bool True if valid, false otherwise
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate phone number format
     * 
     * @param string $phone Phone number to validate
     * @return bool True if valid, false otherwise
     */
    public static function validatePhone($phone) {
        // Remove any non-digit characters for validation
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Check if it's a valid 10-digit Sri Lankan number
        return strlen($cleanPhone) === 10 && preg_match('/^[0-9]{10}$/', $cleanPhone);
    }
    
    /**
     * Validate blood type
     * 
     * @param string $bloodType Blood type to validate
     * @return bool True if valid, false otherwise
     */
    public static function validateBloodType($bloodType) {
        $validTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return in_array($bloodType, $validTypes, true);
    }
    
    /**
     * Validate district
     * 
     * @param string $district District to validate
     * @return bool True if valid, false otherwise
     */
    public static function validateDistrict($district) {
        $validDistricts = [
            'Colombo', 'Kandy', 'Galle', 'Jaffna', 'Batticaloa', 
            'Anuradhapura', 'Polonnaruwa', 'Kurunegala', 'Puttalam', 'Ratnapura'
        ];
        return in_array($district, $validDistricts, true);
    }

    
    /**
     * Sanitize output for HTML display
     * 
     * @param string $output String to sanitize for HTML output
     * @return string Sanitized string
     */
    public static function escapeHtml($output) {
        return htmlspecialchars($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    /**
     * Log security events
     * 
     * @param string $event Event description
     * @param array $context Additional context
     */
    public static function logSecurityEvent($event, $context = []) {
        $logEntry = date('Y-m-d H:i:s') . " - SECURITY: " . $event;
        if (!empty($context)) {
            $logEntry .= " - Context: " . json_encode($context);
        }
        error_log($logEntry);
    }
    
    /**
     * Test database connection
     * 
     * @return bool True if connection successful, false otherwise
     */
    public static function testConnection() {
        try {
            self::getInstance()->getConnection();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Execute a query with error handling and security logging
     * 
     * @param string $sql SQL query
     * @param array $params Parameters for prepared statement
     * @return PDOStatement|false
     */
    public static function executeQuery($sql, $params = []) {
        try {
            $db = self::getInstance()->getConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // Log the actual error for debugging
            error_log("Query execution failed: " . $e->getMessage() . " SQL: " . $sql);
            
            // Log security event for potential injection attempts
            if (strpos($e->getMessage(), 'syntax error') !== false) {
                self::logSecurityEvent("Potential SQL injection attempt detected", [
                    'sql' => $sql,
                    'error' => $e->getMessage(),
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Database operation failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validate and sanitize donor data
     * 
     * @param array $data Donor data to validate
     * @return array Array with 'valid' boolean and 'errors' array
     */
    public static function validateDonorData($data) {
        $errors = [];
        $required = ['name', 'email', 'phone', 'blood_type', 'district'];
        
        // Check required fields
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = ucfirst($field) . " is required.";
            }
        }
        
        // Validate email
        if (!empty($data['email']) && !self::validateEmail($data['email'])) {
            $errors[] = "Please enter a valid email address.";
        }
        
        // Validate phone
        if (!empty($data['phone']) && !self::validatePhone($data['phone'])) {
            $errors[] = "Please enter a valid 10-digit phone number.";
        }
        
        // Validate blood type
        if (!empty($data['blood_type']) && !self::validateBloodType($data['blood_type'])) {
            $errors[] = "Please select a valid blood type.";
        }
        
        // Validate district
        if (!empty($data['district']) && !self::validateDistrict($data['district'])) {
            $errors[] = "Please select a valid district.";
        }
        
        // Validate name length and characters
        if (!empty($data['name'])) {
            if (strlen($data['name']) < 2) {
                $errors[] = "Name must be at least 2 characters long.";
            }
            if (!preg_match('/^[a-zA-Z\s.]+$/', $data['name'])) {
                $errors[] = "Name can only contain letters, spaces, and periods.";
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}

/**
 * Helper function for backward compatibility
 * 
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return PDOStatement|false
 */
function executeQuery($sql, $params = []) {
    return Database::executeQuery($sql, $params);
}

?>
