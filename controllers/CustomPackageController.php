<?php
require_once 'config/Database.php';
require_once 'models/CustomPackage.php';

class CustomPackageController {
    private $db;
    private $customPackage;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->customPackage = new CustomPackage($this->db);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    /**
     * Show the multi-step custom tour builder form.
     */
    public function showBuilder() {
        if (!isset($_SESSION['customer_id'])) {
            $_SESSION['error_msg'] = "Please login to build a custom tour.";
            header("Location: index.php?route=login");
            exit();
        }
        require_once 'views/public/custom_package.php';
    }

    /**
     * Store a new custom package request.
     */
    public function store() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?route=custom_package");
            exit();
        }

        $activities = $_POST['activities'] ?? [];
        $activity_count = count($activities);

        $this->customPackage->customer_id     = (int)$_SESSION['customer_id'];
        $this->customPackage->destination     = $_POST['destination'] ?? '';
        $this->customPackage->duration        = (int)($_POST['duration'] ?? 1);
        $this->customPackage->activities      = implode(', ', array_map('strip_tags', $activities));
        $this->customPackage->notes           = $_POST['notes'] ?? '';
        $this->customPackage->estimated_price = CustomPackage::calculatePrice(
            $this->customPackage->duration,
            $activity_count
        );

        if ($this->customPackage->create()) {
            $_SESSION['success_msg'] = "Your custom tour request has been submitted! Our team will review it shortly.";
            header("Location: index.php?route=my_custom_packages");
        } else {
            $_SESSION['error_msg'] = "Failed to submit your request. Please try again.";
            header("Location: index.php?route=custom_package");
        }
        exit();
    }

    /**
     * Show the customer's custom package requests.
     */
    public function myCustomPackages() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit();
        }
        $this->customPackage->customer_id = (int)$_SESSION['customer_id'];
        $stmt = $this->customPackage->readByCustomer();
        $custom_packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/public/my_custom_packages.php';
    }
}
?>
