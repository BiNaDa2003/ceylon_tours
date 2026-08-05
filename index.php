<?php
// Start session
session_start();

// Get requested route
$route = isset($_GET['route']) ? $_GET['route'] : 'home';

switch ($route) {

    // Public routes
    case 'home':
        require_once 'controllers/PackageController.php';
        (new PackageController())->index();
        break;

    case 'packages':
        require_once 'controllers/PackageController.php';
        (new PackageController())->list();
        break;

    case 'package_details':
        if (isset($_GET['id'])) {
            require_once 'controllers/PackageController.php';
            (new PackageController())->show($_GET['id']);
        } else {
            header("Location: index.php?route=packages");
        }
        break;

    case 'about':
        require_once 'views/public/about.php';
        break;

    case 'contact':
        require_once 'controllers/ContactController.php';
        $controller = new ContactController();
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $controller->store()
            : $controller->showContactForm();
        break;

    // Authentication routes
    case 'login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $controller->login()
            : $controller->showLogin();
        break;

    case 'register':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $controller->register()
            : $controller->showRegister();
        break;

    case 'logout':
        require_once 'controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    // Redirect admin login to shared login page
    case 'admin_login':
        header("Location: index.php?route=login");
        exit();

    // Booking routes
    case 'book':
        if (isset($_GET['id'])) {
            require_once 'controllers/BookingController.php';
            (new BookingController())->showBookingForm($_GET['id']);
        } else {
            header("Location: index.php?route=packages");
        }
        break;

    case 'store_booking':
        require_once 'controllers/BookingController.php';
        (new BookingController())->store();
        break;

    case 'booking_confirmation':
        if (isset($_GET['id'])) {
            require_once 'controllers/BookingController.php';
            (new BookingController())->showConfirmation($_GET['id']);
        } else {
            header("Location: index.php?route=my_bookings");
        }
        break;

    case 'my_bookings':
        require_once 'controllers/BookingController.php';
        (new BookingController())->myBookings();
        break;

    case 'cancel_booking':
        if (isset($_GET['id'])) {
            require_once 'controllers/BookingController.php';
            (new BookingController())->cancel($_GET['id']);
        } else {
            header("Location: index.php?route=my_bookings");
        }
        break;

    // Review routes
    case 'store_review':
        require_once 'controllers/ReviewController.php';
        (new ReviewController())->store();
        break;

    case 'delete_review':
        if (isset($_GET['id'])) {
            require_once 'controllers/ReviewController.php';
            (new ReviewController())->delete($_GET['id']);
        } else {
            header("Location: index.php?route=admin_reviews");
        }
        break;

    // Wishlist routes
    case 'toggle_wishlist':
        require_once 'controllers/WishlistController.php';
        (new WishlistController())->toggle();
        break;

    case 'wishlist':
        require_once 'controllers/WishlistController.php';
        (new WishlistController())->myWishlist();
        break;

    // Custom package routes
    case 'custom_package':
        require_once 'controllers/CustomPackageController.php';
        (new CustomPackageController())->showBuilder();
        break;

    case 'store_custom_package':
        require_once 'controllers/CustomPackageController.php';
        (new CustomPackageController())->store();
        break;

    case 'my_custom_packages':
        require_once 'controllers/CustomPackageController.php';
        (new CustomPackageController())->myCustomPackages();
        break;

    // Admin routes
    case 'admin_logout':
        require_once 'controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    case 'admin_dashboard':
        require_once 'controllers/AdminController.php';
        (new AdminController())->dashboard();
        break;

    // Package management
    case 'admin_packages':
        require_once 'controllers/AdminController.php';
        (new AdminController())->managePackages();
        break;

    case 'admin_add_package':
        require_once 'controllers/AdminController.php';
        (new AdminController())->addPackage();
        break;

    case 'admin_delete_package':
        if (isset($_GET['id'])) {
            require_once 'controllers/AdminController.php';
            (new AdminController())->deletePackage($_GET['id']);
        }
        break;

    case 'admin_update_package':
        require_once 'controllers/AdminController.php';
        (new AdminController())->updatePackage();
        break;

    case 'admin_delete_package_image':
        if (isset($_GET['id'])) {
            require_once 'controllers/AdminController.php';
            (new AdminController())->deletePackageImage($_GET['id']);
        }
        break;

    // Itinerary management
    case 'admin_add_itinerary':
        require_once 'controllers/AdminController.php';
        (new AdminController())->addItinerary();
        break;

    case 'admin_delete_itinerary':
        if (isset($_GET['id'])) {
            require_once 'controllers/AdminController.php';
            (new AdminController())->deleteItinerary($_GET['id']);
        }
        break;

    // Booking management
    case 'admin_bookings':
        require_once 'controllers/AdminController.php';
        (new AdminController())->manageBookings();
        break;

    case 'admin_update_booking_status':
        require_once 'controllers/AdminController.php';
        (new AdminController())->updateBookingStatus();
        break;

    case 'admin_delete_booking':
        if (isset($_GET['id'])) {
            require_once 'controllers/AdminController.php';
            (new AdminController())->deleteBooking($_GET['id']);
        }
        break;

    // Customer management
    case 'admin_customers':
        require_once 'controllers/AdminController.php';
        (new AdminController())->manageCustomers();
        break;

    case 'admin_delete_customer':
        if (isset($_GET['id'])) {
            require_once 'controllers/AdminController.php';
            (new AdminController())->deleteCustomer($_GET['id']);
        }
        break;

    // Review management
    case 'admin_reviews':
        require_once 'controllers/AdminController.php';
        (new AdminController())->manageReviews();
        break;

    // Custom package management
    case 'admin_custom_packages':
        require_once 'controllers/AdminController.php';
        (new AdminController())->manageCustomPackages();
        break;

    case 'admin_update_custom_package_status':
        require_once 'controllers/AdminController.php';
        (new AdminController())->updateCustomPackageStatus();
        break;

    // Default route
    default:
        require_once 'controllers/PackageController.php';
        (new PackageController())->index();
        break;
}
?>