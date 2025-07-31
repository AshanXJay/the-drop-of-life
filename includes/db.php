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
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {}
    
    /**
     * Get database connection using PDO with security configurations
     * 
     * @return PDO Database connection object
     * @throws Exception If connection fails
     */
    public static function getConnection() {
        
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
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            
            // Create DSN (Data Source Name)
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
            
            // Create PDO connection
            self::$connection = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
            
            // Log successful connection (in production, consider removing this)
            error_log("Database connection established successfully");
            
            return self::$connection;
            
        } catch (PDOException $e) {
            // Log error details (never expose database credentials in error messages)
            error_log("Database connection failed: " . $e->getMessage());
            
            // Throw a generic error to avoid exposing sensitive information
            throw new Exception("Database connection failed. Please try again later.");
        }
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
     * Validate phone number (basic validation)
     * 
     * @param string $phone Phone number to validate
     * @return bool True if valid phone number, false otherwise
     */
    public static function validatePhone($phone) {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Check if phone number is between 10-15 digits
        return strlen($phone) == 10;
    }
    
    
    /**
     * Test database connection
     * 
     * @return bool True if connection is successful, false otherwise
     */
    public static function testConnection() {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->query("SELECT 1");
            return $stmt !== false;
        } catch (Exception $e) {
            error_log("Database connection test failed: " . $e->getMessage());
            return false;
        }
    }
}



/**
 * Function to safely execute prepared statements
 * 
 * @param string $query SQL query with placeholders
 * @param array $params Parameters for the query
 * @return PDOStatement|false Executed statement or false on failure
 */
function executeQuery($query, $params = []) {
    try {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($query);
        
        // Sanitize parameters
        $sanitizedParams = [];
        foreach ($params as $key => $value) {
            $sanitizedParams[$key] = Database::sanitizeInput($value);
        }
        
        $stmt->execute($sanitizedParams);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Query execution failed: " . $e->getMessage());
        return false;
    }
}

?>
