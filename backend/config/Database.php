<?php

class Database {
    private static $instance = null;
    private $connection = null;
    
    // Database configuration
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'date_app';
    
    private function __construct() {
        // Private constructor to prevent direct instantiation
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        if ($this->connection === null) {
            try {
                $this->connection = new mysqli(
                    $this->host,
                    $this->username,
                    $this->password,
                    $this->database
                );
                
                if ($this->connection->connect_error) {
                    throw new Exception('Database connection failed: ' . $this->connection->connect_error);
                }
            } catch (Exception $e) {
                // For now, return null if database is not available
                // The app will work with sample data
                return null;
            }
        }
        
        return $this->connection;
    }
    
    public function isConnected() {
        return $this->getConnection() !== null;
    }
}
