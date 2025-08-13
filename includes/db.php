<?php
// Simple Database Connection for The Drop of Life

class Database {
    private const DB_HOST = 'bzkd4iprnmfkfobin9cj-mysql.services.clever-cloud.com';
    private const DB_NAME = 'bzkd4iprnmfkfobin9cj';
    private const DB_USER = 'ukvwmh3rzlm2bgbn';
    private const DB_PASS = 'CZiCDHohLI99binN4A5N';
    
    private static $connection = null;
    private static $instance = null;
    
    private function __construct() {}
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        if (self::$connection !== null) {
            return self::$connection;
        }
        
        try {
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
            self::$connection = new PDO($dsn, self::DB_USER, self::DB_PASS);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$connection;
        } catch (PDOException $e) {
            throw new Exception("Database connection failed");
        }
    }
    
    public static function sanitizeInput($input) {
        return trim(strip_tags($input));
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function validatePhone($phone) {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        return strlen($cleanPhone) === 10;
    }
    
    public static function validateBloodType($bloodType) {
        $validTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return in_array($bloodType, $validTypes);
    }
    
    public static function validateDistrict($district) {
        $validDistricts = [
            'Colombo', 'Kandy', 'Galle', 'Jaffna', 'Batticaloa', 
            'Anuradhapura', 'Polonnaruwa', 'Kurunegala', 'Puttalam', 'Ratnapura'
        ];
        return in_array($district, $validDistricts);
    }
    
    public static function escapeHtml($output) {
        return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
    }
    
    public static function validateDonorData($data) {
        $errors = [];
        
        if (empty($data['name'])) $errors[] = "Name is required";
        if (empty($data['email'])) $errors[] = "Email is required";
        if (empty($data['phone'])) $errors[] = "Phone is required";
        if (empty($data['blood_type'])) $errors[] = "Blood type is required";
        if (empty($data['district'])) $errors[] = "District is required";
        
        if (!empty($data['email']) && !self::validateEmail($data['email'])) {
            $errors[] = "Invalid email format";
        }
        
        if (!empty($data['phone']) && !self::validatePhone($data['phone'])) {
            $errors[] = "Invalid phone number";
        }
        
        if (!empty($data['blood_type']) && !self::validateBloodType($data['blood_type'])) {
            $errors[] = "Invalid blood type";
        }
        
        if (!empty($data['district']) && !self::validateDistrict($data['district'])) {
            $errors[] = "Invalid district";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
?>
