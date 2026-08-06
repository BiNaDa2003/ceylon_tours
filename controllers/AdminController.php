<?php
require_once 'config/Database.php';
require_once 'models/Admin.php';
require_once 'models/Package.php';
require_once 'models/Booking.php';
require_once 'models/Customer.php';
require_once 'models/Review.php';
require_once 'models/Itinerary.php';
require_once 'models/CustomPackage.php';

class AdminController {
    private $db;
    private $admin;
    private $package;
    private $booking;
    private $customer;

    public function __construct() {
        $database = new Database();
        $this->db       = $database->getConnection();
        $this->admin    = new Admin($this->db);
        $this->package  = new Package($this->db);
        $this->booking  = new Booking($this->db);
        $this->customer = new Customer($this->db);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    private function checkAuth() {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?route=login");
            exit();
        }
    }

    // --------------------------------------------------------
    // DASHBOARD
    // --------------------------------------------------------
    public function dashboard() {
        $this->checkAuth();
        $stats = $this->admin->getDashboardStats();
        require_once 'views/admin/dashboard.php';
    }

    // --------------------------------------------------------
    // PACKAGE MANAGEMENT
    // --------------------------------------------------------
    public function managePackages() {
        $this->checkAuth();
        $stmt = $this->package->readAll();
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/admin/manage_packages.php';
    }

    public function addPackage() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->package->title             = $_POST['title'];
            $this->package->destination       = $_POST['destination'];
            $this->package->description       = $_POST['description'];
            $this->package->price             = $_POST['price'];
            $this->package->duration          = $_POST['duration'];
            $this->package->available_slots   = $_POST['available_slots'];
            $this->package->category          = $_POST['category'];
            $this->package->difficulty_level  = $_POST['difficulty_level'];
            $this->package->includes_services = $_POST['includes_services'] ?? '';
            $this->package->excluded_services = $_POST['excluded_services'] ?? '';
            $this->package->is_featured       = isset($_POST['is_featured']) ? 1 : 0;

            // Handle image upload
            $this->package->image = $this->handleImageUpload('image');

            $package_id = $this->package->create();
            if ($package_id) {
                // Handle additional gallery images
                $this->handleGalleryImages($package_id, $this->package->image);
                // Handle itinerary
                $this->handleItinerary($package_id);
                $_SESSION['success_msg'] = "Package added successfully.";
            } else {
                $_SESSION['error_msg'] = "Failed to add package.";
            }
            header("Location: index.php?route=admin_packages");
            exit();
        }
    }

    public function updatePackage() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->package->id               = $_POST['id'];
            $this->package->title            = $_POST['title'];
            $this->package->destination      = $_POST['destination'];
            $this->package->description      = $_POST['description'];
            $this->package->price            = $_POST['price'];
            $this->package->duration         = $_POST['duration'];
            $this->package->available_slots  = $_POST['available_slots'];
            $this->package->category         = $_POST['category'];
            $this->package->difficulty_level = $_POST['difficulty_level'];
            $this->package->includes_services = $_POST['includes_services'] ?? '';
            $this->package->excluded_services = $_POST['excluded_services'] ?? '';
            $this->package->is_featured      = isset($_POST['is_featured']) ? 1 : 0;

            // Only update image if a new one is uploaded
            $uploadedImage = $this->handleImageUpload('image');
            if ($uploadedImage) {
                $this->package->image = $uploadedImage;
            } else {
                $this->package->image = null; // Will not update image column
            }

            if ($this->package->update()) {
                $_SESSION['success_msg'] = "Package updated successfully.";
            } else {
                $_SESSION['error_msg'] = "Failed to update package.";
            }
            header("Location: index.php?route=admin_packages");
            exit();
        }
    }

    public function deletePackage($id) {
        $this->checkAuth();
        $this->package->id = $id;
        if ($this->package->delete()) {
            $_SESSION['success_msg'] = "Package deleted successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to delete package.";
        }
        header("Location: index.php?route=admin_packages");
        exit();
    }

    /**
     * Delete a package gallery image.
     */
    public function deletePackageImage($image_id) {
        $this->checkAuth();
        $this->package->deleteImage($image_id);
        $_SESSION['success_msg'] = "Image deleted.";
        $ref = $_SERVER['HTTP_REFERER'] ?? "index.php?route=admin_packages";
        header("Location: " . $ref);
        exit();
    }

    // --------------------------------------------------------
    // ITINERARY MANAGEMENT
    // --------------------------------------------------------
    public function addItinerary() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itinerary = new Itinerary($this->db);
            $itinerary->package_id  = (int)$_POST['package_id'];
            $itinerary->day_number  = (int)$_POST['day_number'];
            $itinerary->title       = $_POST['title'];
            $itinerary->description = $_POST['description'];
            $itinerary->create()
                ? $_SESSION['success_msg'] = "Itinerary day added."
                : $_SESSION['error_msg'] = "Failed to add itinerary day.";
        }
        $ref = $_SERVER['HTTP_REFERER'] ?? "index.php?route=admin_packages";
        header("Location: " . $ref);
        exit();
    }

    public function deleteItinerary($id) {
        $this->checkAuth();
        $itinerary = new Itinerary($this->db);
        $itinerary->id = (int)$id;
        $itinerary->delete()
            ? $_SESSION['success_msg'] = "Itinerary day deleted."
            : $_SESSION['error_msg'] = "Failed.";
        $ref = $_SERVER['HTTP_REFERER'] ?? "index.php?route=admin_packages";
        header("Location: " . $ref);
        exit();
    }

    // --------------------------------------------------------
    // BOOKING MANAGEMENT
    // --------------------------------------------------------
    public function manageBookings() {
        $this->checkAuth();
        $stmt = $this->booking->readAll();
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/admin/manage_bookings.php';
    }

    public function updateBookingStatus() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->booking->id             = $_POST['booking_id'];
            $this->booking->booking_status = $_POST['booking_status'];
            $this->booking->updateStatus()
                ? $_SESSION['success_msg'] = "Booking status updated."
                : $_SESSION['error_msg'] = "Failed to update status.";
            header("Location: index.php?route=admin_bookings");
            exit();
        }
    }

    public function deleteBooking($id) {
        $this->checkAuth();
        $this->booking->id = $id;
        $this->booking->delete()
            ? $_SESSION['success_msg'] = "Booking deleted."
            : $_SESSION['error_msg'] = "Failed to delete booking.";
        header("Location: index.php?route=admin_bookings");
        exit();
    }

    // --------------------------------------------------------
    // CUSTOMER MANAGEMENT
    // --------------------------------------------------------
    public function manageCustomers() {
        $this->checkAuth();
        $stmt = $this->customer->readAll();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/admin/manage_customers.php';
    }

    public function deleteCustomer($id) {
        $this->checkAuth();
        $this->customer->id = $id;
        $this->customer->delete()
            ? $_SESSION['success_msg'] = "Customer deleted successfully."
            : $_SESSION['error_msg'] = "Failed to delete customer.";
        header("Location: index.php?route=admin_customers");
        exit();
    }

    // --------------------------------------------------------
    // REVIEW MANAGEMENT
    // --------------------------------------------------------
    public function manageReviews() {
        $this->checkAuth();
        $review = new Review($this->db);
        $stmt = $review->readAll();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/admin/manage_reviews.php';
    }

    // --------------------------------------------------------
    // CUSTOM PACKAGES MANAGEMENT
    // --------------------------------------------------------
    public function manageCustomPackages() {
        $this->checkAuth();
        $customPackage = new CustomPackage($this->db);
        $stmt = $customPackage->readAll();
        $custom_packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/admin/manage_custom_packages.php';
    }

    public function updateCustomPackageStatus() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customPackage = new CustomPackage($this->db);
            $customPackage->id          = (int)$_POST['request_id'];
            $customPackage->status      = $_POST['status'];
            $customPackage->admin_notes = $_POST['admin_notes'] ?? '';
            $customPackage->updateStatus()
                ? $_SESSION['success_msg'] = "Request status updated."
                : $_SESSION['error_msg'] = "Failed to update status.";
            header("Location: index.php?route=admin_custom_packages");
            exit();
        }
    }

    // --------------------------------------------------------
    // PRIVATE HELPERS
    // --------------------------------------------------------

    /**
     * Handle a single image upload, returning the filename or null.
     */
    private function handleImageUpload($field_name) {
        if (!isset($_FILES[$field_name]) || $_FILES[$field_name]['error'] !== UPLOAD_ERR_OK) {
            return $_POST['existing_image'] ?? null;
        }
        $file = $_FILES[$field_name];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) return null;

        // Save to assets/ (same folder as existing images)
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
        $dest = 'assets/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $filename;
        }
        return null;
    }

    /**
     * Handle gallery image uploads for a new package.
     */
    private function handleGalleryImages($package_id, $primary_image) {
        // Add primary image to package_images
        if ($primary_image) {
            $this->package->addImage($package_id, $primary_image, '', 1);
        }
        // Handle multiple additional images
        if (isset($_FILES['gallery_images']) && is_array($_FILES['gallery_images']['name'])) {
            foreach ($_FILES['gallery_images']['name'] as $i => $name) {
                if ($_FILES['gallery_images']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $filename = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $name);
                        $dest = 'assets/' . $filename;
                        if (move_uploaded_file($_FILES['gallery_images']['tmp_name'][$i], $dest)) {
                            $caption = $_POST['gallery_captions'][$i] ?? '';
                            $this->package->addImage($package_id, $filename, $caption, 0);
                        }
                    }
                }
            }
        }
    }

    /**
     * Handle itinerary days submitted with the package form.
     */
    private function handleItinerary($package_id) {
        if (empty($_POST['itinerary_day']) || !is_array($_POST['itinerary_day'])) return;
        $itinerary = new Itinerary($this->db);
        foreach ($_POST['itinerary_day'] as $i => $day) {
            if (empty($_POST['itinerary_title'][$i])) continue;
            $itinerary->package_id  = $package_id;
            $itinerary->day_number  = (int)$day;
            $itinerary->title       = $_POST['itinerary_title'][$i];
            $itinerary->description = $_POST['itinerary_desc'][$i] ?? '';
            $itinerary->create();
        }
    }
}
?>
