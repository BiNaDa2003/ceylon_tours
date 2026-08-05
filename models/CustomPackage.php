<?php
/**
 * CustomPackage Model
 * Allows customers to build customized tour requests.
 * Admin can approve or reject these requests.
 */
class CustomPackage {
    private $conn;
    private $table_name = "custom_packages";

    public $id;
    public $customer_id;
    public $destination;
    public $duration;
    public $activities;
    public $notes;
    public $estimated_price;
    public $status;
    public $admin_notes;
    public $created_at;

    // Price constants for estimation (in Rs.)
    const BASE_PRICE_PER_DAY  = 5000;
    const ACTIVITY_PRICE_EACH = 2000;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create a new custom package request.
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET customer_id=:customer_id, destination=:destination,
                      duration=:duration, activities=:activities,
                      notes=:notes, estimated_price=:estimated_price, status='Pending'";
        $stmt = $this->conn->prepare($query);

        $this->customer_id     = (int)$this->customer_id;
        $this->destination     = htmlspecialchars(strip_tags($this->destination));
        $this->duration        = (int)$this->duration;
        $this->activities      = htmlspecialchars(strip_tags($this->activities));
        $this->notes           = htmlspecialchars(strip_tags($this->notes));
        $this->estimated_price = (float)$this->estimated_price;

        $stmt->bindParam(':customer_id',     $this->customer_id,     PDO::PARAM_INT);
        $stmt->bindParam(':destination',     $this->destination);
        $stmt->bindParam(':duration',        $this->duration,        PDO::PARAM_INT);
        $stmt->bindParam(':activities',      $this->activities);
        $stmt->bindParam(':notes',           $this->notes);
        $stmt->bindParam(':estimated_price', $this->estimated_price);

        return $stmt->execute();
    }

    /**
     * Get all custom package requests by a specific customer.
     */
    public function readByCustomer() {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE customer_id = :customer_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':customer_id', $this->customer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Get all custom package requests (for admin).
     */
    public function readAll() {
        $query = "SELECT cp.*, c.name as customer_name, c.email as customer_email
                  FROM " . $this->table_name . " cp
                  LEFT JOIN customers c ON cp.customer_id = c.id
                  ORDER BY cp.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Get count of pending custom package requests (for dashboard badge).
     */
    public function countPending() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = 'Pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    /**
     * Admin: Approve or reject a request.
     */
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . "
                  SET status=:status, admin_notes=:admin_notes
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->status      = htmlspecialchars(strip_tags($this->status));
        $this->admin_notes = htmlspecialchars(strip_tags($this->admin_notes));

        $stmt->bindParam(':status',      $this->status);
        $stmt->bindParam(':admin_notes', $this->admin_notes);
        $stmt->bindParam(':id',          $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Calculate estimated price based on duration and activities count.
     */
    public static function calculatePrice($duration, $activity_count) {
        $base    = $duration * self::BASE_PRICE_PER_DAY;
        $extras  = $activity_count * self::ACTIVITY_PRICE_EACH;
        return $base + $extras;
    }
}
?>
