<?php
class Customer {
    private $conn;
    private $table_name = "customers";

    public $id;
    public $name;
    public $email;
    public $phone;
    public $password;

    // Initialize database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new customer
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, email=:email, phone=:phone, password=:password";
        $stmt  = $this->conn->prepare($query);

        // Sanitize user input
        $this->name  = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));

        $stmt->bindParam(":name",     $this->name);
        $stmt->bindParam(":email",    $this->email);
        $stmt->bindParam(":phone",    $this->phone);
        $stmt->bindParam(":password", $this->password);

        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // Login using email or username
    public function loginByIdentifier($identifier, $password) {

        $query = "SELECT id, name, password FROM " . $this->table_name . "
                  WHERE email = :id1 OR name = :id2
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);

        // Clean login input
        $identifier = htmlspecialchars(strip_tags(trim($identifier)));

        $stmt->bindParam(':id1', $identifier);
        $stmt->bindParam(':id2', $identifier);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($password, $row['password']) || $password === $row['password']) {
                $this->id   = $row['id'];
                $this->name = $row['name'];
                return true;
            }
        }
        return false;
    }

    // Call login method
    public function login($email, $password) {
        return $this->loginByIdentifier($email, $password);
    }

    // Retrieve all customers
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Delete customer by ID
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt  = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>