<?php
require_once 'config/Database.php';
require_once 'models/Wishlist.php';

class WishlistController {
    private $db;
    private $wishlist;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->wishlist = new Wishlist($this->db);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    /**
     * Toggle wishlist status via AJAX (returns JSON) or redirect.
     * Called from package cards / detail page heart button.
     */
    public function toggle() {
        if (!isset($_SESSION['customer_id'])) {
            // Return JSON for AJAX, or redirect
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Please login first.', 'redirect' => 'index.php?route=login']);
            } else {
                $_SESSION['error_msg'] = "Please login to save favorites.";
                header("Location: index.php?route=login");
            }
            exit();
        }

        $package_id = (int)($_POST['package_id'] ?? $_GET['id'] ?? 0);
        if (!$package_id) {
            header("Location: index.php?route=packages");
            exit();
        }

        $this->wishlist->customer_id = (int)$_SESSION['customer_id'];
        $this->wishlist->package_id  = $package_id;

        $action = $this->wishlist->toggle();
        $inWishlist = ($action === 'added');

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success'    => true,
                'action'     => $action,
                'inWishlist' => $inWishlist,
                'message'    => $inWishlist ? 'Added to favorites!' : 'Removed from favorites.'
            ]);
            exit();
        }

        $_SESSION['success_msg'] = $inWishlist ? "Added to your favorites!" : "Removed from favorites.";
        $ref = $_SERVER['HTTP_REFERER'] ?? "index.php?route=packages";
        header("Location: " . $ref);
        exit();
    }

    /**
     * Show the customer's wishlist page.
     */
    public function myWishlist() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit();
        }
        $this->wishlist->customer_id = (int)$_SESSION['customer_id'];
        $stmt = $this->wishlist->getByCustomer();
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/public/wishlist.php';
    }

    private function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
?>
