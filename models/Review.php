<?php
/**
 * Review Model
 * Handles customer ratings and comments for tour packages.
 * Only customers with a Confirmed booking can submit a review.
 */
class Review {
    private $conn;
    private $table_name = "reviews";

    public $id;
    public $customer_id;
    public $package_id;
    public $rating;
    public $comment;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Submit a new review (one per customer per package)
     */
    public function create() {
        // Verify customer has a confirmed booking before inserting
        $query = "INSERT INTO " . $this->table_name . "
                  SET customer_id=:customer_id, package_id=:package_id,
                      rating=:rating, comment=:comment";
        $stmt = $this->conn->prepare($query);

        $this->customer_id = (int)$this->customer_id;
        $this->package_id  = (int)$this->package_id;
        $this->rating      = max(1, min(5, (int)$this->rating));
        $this->comment     = htmlspecialchars(strip_tags($this->comment));

        $stmt->bindParam(':customer_id', $this->customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':package_id',  $this->package_id,  PDO::PARAM_INT);
        $stmt->bindParam(':rating',      $this->rating,      PDO::PARAM_INT);
        $stmt->bindParam(':comment',     $this->comment);

        if ($stmt->execute()) {
            $this->updatePackageRating($this->package_id);
            return true;
        }
        return false;
    }

    /**
     * Get all reviews for a specific package (with customer name)
     */
    public function readByPackage() {
        $query = "SELECT r.*, c.name as customer_name
                  FROM " . $this->table_name . " r
                  LEFT JOIN customers c ON r.customer_id = c.id
                  WHERE r.package_id = :package_id
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':package_id', $this->package_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Get all reviews (for admin management)
     */
    public function readAll() {
        $query = "SELECT r.*, c.name as customer_name, p.title as package_title
                  FROM " . $this->table_name . " r
                  LEFT JOIN customers c ON r.customer_id = c.id
                  LEFT JOIN tour_packages p ON r.package_id = p.id
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Get average rating for a package
     */
    public function getAverageRating($package_id) {
        $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews
                  FROM " . $this->table_name . " WHERE package_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $package_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            'avg'   => $row['avg_rating'] ? round($row['avg_rating'], 1) : 0,
            'total' => (int)$row['total_reviews']
        ];
    }

    /**
     * Check if a customer has already reviewed a package
     */
    public function hasReviewed($customer_id, $package_id) {
        $query = "SELECT id FROM " . $this->table_name . "
                  WHERE customer_id = ? AND package_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $package_id,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Check if a customer has a confirmed booking for this package
     */
    public function canReview($customer_id, $package_id) {
        $query = "SELECT id FROM bookings
                  WHERE customer_id = ? AND package_id = ?
                  AND booking_status = 'Confirmed' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $package_id,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a review (admin action)
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = (int)$this->id;
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $this->updatePackageRating($this->package_id);
            return true;
        }
        return false;
    }

    /**
     * Recalculate and update the cached average rating on tour_packages
     */
    private function updatePackageRating($package_id) {
        $query = "UPDATE tour_packages
                  SET rating = (SELECT COALESCE(AVG(rating),0) FROM reviews WHERE package_id = ?)
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $package_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $package_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>
