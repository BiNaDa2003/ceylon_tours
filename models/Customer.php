<?php
class Customer {
    private $conn;
    private $table_name = "customers";

    public $id;
    public $name;
    public $email;
    public $phone;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Register a new customer.
     */
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, email=:email, phone=:phone, password=:password";
        $stmt  = $this->conn->prepare($query);

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

    /**
     * Login customer by email and password (password must be a PHP password_hash() hash).
     */
    public function login($email, $password) {
        $query = "SELECT id, name, email, password FROM " . $this->table_name . "
                  WHERE email = :email
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $email = htmlspecialchars(strip_tags(trim($email)));
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $this->id   = $row['id'];
                $this->name = $row['name'];
                return true;
            }
        }
        return false;
    }

    /**
     * Read all customers (admin use).
     */
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Delete a customer by ID.
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt  = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>
