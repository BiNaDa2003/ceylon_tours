<?php
/**
 * Wishlist Model
 * Handles customer favorite packages (add/remove/check/list).
 */
class Wishlist {
    private $conn;
    private $table_name = "wishlist";

    public $id;
    public $customer_id;
    public $package_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Add a package to wishlist. Returns false if already exists (UNIQUE key).
     */
    public function add() {
        $query = "INSERT IGNORE INTO " . $this->table_name . "
                  SET customer_id=:customer_id, package_id=:package_id";
        $stmt = $this->conn->prepare($query);
        $this->customer_id = (int)$this->customer_id;
        $this->package_id  = (int)$this->package_id;
        $stmt->bindParam(':customer_id', $this->customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':package_id',  $this->package_id,  PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Remove a package from wishlist.
     */
    public function remove() {
        $query = "DELETE FROM " . $this->table_name . "
                  WHERE customer_id = :customer_id AND package_id = :package_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':customer_id', $this->customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':package_id',  $this->package_id,  PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Toggle: if in wishlist remove it, else add it.
     * Returns 'added' or 'removed'.
     */
    public function toggle() {
        if ($this->isInWishlist($this->customer_id, $this->package_id)) {
            $this->remove();
            return 'removed';
        }
        $this->add();
        return 'added';
    }

    /**
     * Check if a specific package is in a customer's wishlist.
     */
    public function isInWishlist($customer_id, $package_id) {
        $query = "SELECT id FROM " . $this->table_name . "
                  WHERE customer_id = ? AND package_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $package_id,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Get all wishlist package IDs for a customer (for card highlighting).
     */
    public function getWishlistIds($customer_id) {
        $query = "SELECT package_id FROM " . $this->table_name . " WHERE customer_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $customer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Returns array of package IDs
    }

    /**
     * Get all wishlist packages for a customer (full package details joined).
     */
    public function getByCustomer() {
        $query = "SELECT p.*, w.created_at as wishlisted_at
                  FROM " . $this->table_name . " w
                  LEFT JOIN tour_packages p ON w.package_id = p.id
                  WHERE w.customer_id = :customer_id
                  ORDER BY w.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':customer_id', $this->customer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
?>
