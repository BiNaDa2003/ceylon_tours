<?php
class Booking {
    private $conn;
    private $table_name = "bookings";

    public $id;
    public $customer_id;
    public $package_id;
    public $travel_date;
    public $travelers;
    public $special_requests;
    public $booking_status;
    public $total_price;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create booking
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET customer_id=:customer_id, package_id=:package_id, travel_date=:travel_date, travelers=:travelers, special_requests=:special_requests, total_price=:total_price, booking_status='Pending'";
        $stmt = $this->conn->prepare($query);

        $this->customer_id     = htmlspecialchars(strip_tags($this->customer_id));
        $this->package_id      = htmlspecialchars(strip_tags($this->package_id));
        $this->travel_date     = htmlspecialchars(strip_tags($this->travel_date));
        $this->travelers       = htmlspecialchars(strip_tags($this->travelers));
        $this->special_requests = htmlspecialchars(strip_tags($this->special_requests ?? ''));
        $this->total_price     = (float)($this->total_price ?? 0);

        $stmt->bindParam(":customer_id",      $this->customer_id);
        $stmt->bindParam(":package_id",       $this->package_id);
        $stmt->bindParam(":travel_date",      $this->travel_date);
        $stmt->bindParam(":travelers",        $this->travelers);
        $stmt->bindParam(":special_requests", $this->special_requests);
        $stmt->bindParam(":total_price",      $this->total_price);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read bookings by customer
    public function readByCustomer() {
        $query = "SELECT b.*, p.title as package_title, p.destination, p.image, p.duration, p.price
                  FROM " . $this->table_name . " b
                  LEFT JOIN tour_packages p ON b.package_id = p.id
                  WHERE b.customer_id = ?
                  ORDER BY b.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $this->customer_id = htmlspecialchars(strip_tags($this->customer_id));
        $stmt->bindParam(1, $this->customer_id);
        $stmt->execute();
        return $stmt;
    }

    // Read all bookings
    public function readAll() {
        $query = "SELECT b.*, p.title as package_title, c.name as customer_name, c.email as customer_email FROM " . $this->table_name . " b LEFT JOIN tour_packages p ON b.package_id = p.id LEFT JOIN customers c ON b.customer_id = c.id ORDER BY b.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update booking status
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " SET booking_status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->booking_status = htmlspecialchars(strip_tags($this->booking_status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":status", $this->booking_status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Update booking details (for customer)
    public function updateDetails() {
        $query = "UPDATE " . $this->table_name . " SET travel_date=:travel_date, travelers=:travelers, special_requests=:special_requests WHERE id = :id AND customer_id = :customer_id AND booking_status = 'Pending'";
        $stmt = $this->conn->prepare($query);

        $this->travel_date = htmlspecialchars(strip_tags($this->travel_date));
        $this->travelers = htmlspecialchars(strip_tags($this->travelers));
        $this->special_requests = htmlspecialchars(strip_tags($this->special_requests));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->customer_id = htmlspecialchars(strip_tags($this->customer_id));

        $stmt->bindParam(":travel_date", $this->travel_date);
        $stmt->bindParam(":travelers", $this->travelers);
        $stmt->bindParam(":special_requests", $this->special_requests);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":customer_id", $this->customer_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete booking
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Cancel booking (by customer)
    public function cancelBooking() {
        $query = "UPDATE " . $this->table_name . " SET booking_status = 'Cancelled' WHERE id = :id AND customer_id = :customer_id AND booking_status != 'Confirmed'";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->customer_id = htmlspecialchars(strip_tags($this->customer_id));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":customer_id", $this->customer_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
