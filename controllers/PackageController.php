<?php
require_once 'config/Database.php';
require_once 'models/Package.php';
require_once 'models/Review.php';
require_once 'models/Wishlist.php';
require_once 'models/Itinerary.php';

class PackageController {
    private $db;
    private $package;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->package = new Package($this->db);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    /**
     * Home page — show featured packages.
     */
    public function index() {
        $stmt = $this->package->getFeatured(6);
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/public/home.php';
    }

    /**
     * Packages listing — with advanced search and filter.
     */
    public function list() {
        $keyword      = $_GET['search']       ?? '';
        $destination  = $_GET['destination']  ?? '';
        $max_price    = $_GET['max_price']    ?? '';
        $category     = $_GET['category']     ?? '';
        $min_duration = $_GET['min_duration'] ?? '';
        $max_duration = $_GET['max_duration'] ?? '';
        $min_rating   = $_GET['min_rating']   ?? '';

        $stmt = $this->package->search(
            $keyword, $destination, $max_price,
            $category, $min_duration, $max_duration, $min_rating
        );
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Pass wishlist IDs for heart icon state
        $wishlistIds = [];
        if (isset($_SESSION['customer_id'])) {
            $wishlist = new Wishlist($this->db);
            $wishlistIds = $wishlist->getWishlistIds($_SESSION['customer_id']);
        }

        // Category list for sidebar filter
        $categories = $this->package->getCategories();

        require_once 'views/public/packages.php';
    }

    /**
     * Package detail page — loads images, itinerary, reviews, wishlist state.
     */
    public function show($id) {
        $this->package->id = $id;
        if (!$this->package->readOne()) {
            header("Location: index.php?route=packages");
            exit();
        }

        // Gallery images
        $images = $this->package->getImages($id);

        // Itinerary
        $itinerary_model = new Itinerary($this->db);
        $itinerary_model->package_id = $id;
        $itinerary = $itinerary_model->readByPackage()->fetchAll(PDO::FETCH_ASSOC);

        // Reviews
        $review_model = new Review($this->db);
        $review_model->package_id = $id;
        $reviews = $review_model->readByPackage()->fetchAll(PDO::FETCH_ASSOC);
        $rating_data = $review_model->getAverageRating($id);

        // Wishlist & review permission
        $inWishlist  = false;
        $canReview   = false;
        $hasReviewed = false;
        if (isset($_SESSION['customer_id'])) {
            $wishlist = new Wishlist($this->db);
            $inWishlist  = $wishlist->isInWishlist($_SESSION['customer_id'], $id);
            $canReview   = $review_model->canReview($_SESSION['customer_id'], $id);
            $hasReviewed = $review_model->hasReviewed($_SESSION['customer_id'], $id);
        }

        require_once 'views/public/package_details.php';
    }
}
?>
