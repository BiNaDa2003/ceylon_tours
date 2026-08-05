<?php
class Database {
    // Database credentials
    private $host = "localhost";
    private $db_name = "tour_booking";
    private $username = "root";
    private $password = "root";
    public $conn;

    // Get the database connection
    public function getConnection() {
        $this->conn = null;

        try {
            // Create PDO connection
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            // Enable exception handling
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set default fetch mode
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            // Display connection error
            echo "Database Connection Error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>