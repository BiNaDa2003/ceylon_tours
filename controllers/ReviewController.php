<?php
require_once 'config/Database.php';
require_once 'models/Review.php';

class ReviewController {
    private $db;
    private $review;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->review = new Review($this->db);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    /**
     * Store a new review (POST only, logged-in customers with confirmed bookings).
     */
    public function store() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?route=packages");
            exit();
        }

        $package_id  = (int)($_POST['package_id'] ?? 0);
        $customer_id = (int)$_SESSION['customer_id'];

        // Permission check: must have a confirmed booking
        if (!$this->review->canReview($customer_id, $package_id)) {
            $_SESSION['error_msg'] = "You can only review packages you have completed a confirmed booking for.";
            header("Location: index.php?route=package_details&id={$package_id}");
            exit();
        }

        // Duplicate check
        if ($this->review->hasReviewed($customer_id, $package_id)) {
            $_SESSION['error_msg'] = "You have already submitted a review for this package.";
            header("Location: index.php?route=package_details&id={$package_id}");
            exit();
        }

        $this->review->customer_id = $customer_id;
        $this->review->package_id  = $package_id;
        $this->review->rating      = $_POST['rating'] ?? 5;
        $this->review->comment     = $_POST['comment'] ?? '';

        if ($this->review->create()) {
            $_SESSION['success_msg'] = "Thank you! Your review has been submitted.";
        } else {
            $_SESSION['error_msg'] = "Failed to submit review. Please try again.";
        }
        header("Location: index.php?route=package_details&id={$package_id}#reviews");
        exit();
    }

    /**
     * Delete a review (admin only).
     */
    public function delete($id) {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?route=admin_login");
            exit();
        }
        $this->review->id = (int)$id;
        if ($this->review->delete()) {
            $_SESSION['success_msg'] = "Review deleted.";
        } else {
            $_SESSION['error_msg'] = "Failed to delete review.";
        }
        header("Location: index.php?route=admin_reviews");
        exit();
    }
}
?>
