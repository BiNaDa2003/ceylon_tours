<?php
require_once 'config/Database.php';
require_once 'models/Booking.php';
require_once 'models/Package.php';

class BookingController {
    private $db;
    private $booking;
    private $package;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->booking = new Booking($this->db);
        $this->package = new Package($this->db);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    /**
     * Show the booking form for a specific package.
     */
    public function showBookingForm($package_id) {
        if (!isset($_SESSION['customer_id'])) {
            $_SESSION['error_msg'] = "Please login to book a package.";
            header("Location: index.php?route=login");
            exit();
        }
        $this->package->id = $package_id;
        if ($this->package->readOne()) {
            require_once 'views/public/booking.php';
        } else {
            header("Location: index.php?route=packages");
            exit();
        }
    }

    /**
     * Process booking submission and redirect to confirmation page.
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['customer_id'])) {
            $package_id = (int)($_POST['package_id'] ?? 0);
            $travelers  = (int)($_POST['travelers'] ?? 1);

            // Get package price to calculate total
            $this->package->id = $package_id;
            $this->package->readOne();
            $total_price = $travelers * $this->package->price;

            $this->booking->customer_id     = $_SESSION['customer_id'];
            $this->booking->package_id      = $package_id;
            $this->booking->travel_date     = $_POST['travel_date'];
            $this->booking->travelers       = $travelers;
            $this->booking->special_requests = $_POST['special_requests'] ?? '';
            $this->booking->total_price     = $total_price;

            if ($this->booking->create()) {
                $booking_id = $this->db->lastInsertId();
                $_SESSION['last_booking_id'] = $booking_id;
                $_SESSION['success_msg'] = "Booking submitted successfully!";
                header("Location: index.php?route=booking_confirmation&id=" . $booking_id);
            } else {
                $_SESSION['error_msg'] = "Failed to submit booking. Please try again.";
                header("Location: index.php?route=book&id=" . $package_id);
            }
            exit();
        }
        header("Location: index.php?route=packages");
        exit();
    }

    /**
     * Show booking confirmation page.
     */
    public function showConfirmation($booking_id) {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit();
        }
        $booking_id = (int)$booking_id;
        $query = "SELECT b.*, p.title as package_title, p.destination, p.image, p.duration, p.price,
                         p.category, c.name as customer_name, c.email as customer_email
                  FROM bookings b
                  LEFT JOIN tour_packages p ON b.package_id = p.id
                  LEFT JOIN customers c ON b.customer_id = c.id
                  WHERE b.id = ? AND b.customer_id = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $booking_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $_SESSION['customer_id'], PDO::PARAM_INT);
        $stmt->execute();
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            header("Location: index.php?route=my_bookings");
            exit();
        }
        require_once 'views/public/booking_confirmation.php';
    }

    /**
     * Show the customer's booking list.
     */
    public function myBookings() {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit();
        }
        $this->booking->customer_id = $_SESSION['customer_id'];
        $stmt = $this->booking->readByCustomer();
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/public/my_bookings.php';
    }

    /**
     * Cancel a booking (customer — only Pending bookings).
     */
    public function cancel($id) {
        if (!isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=login");
            exit();
        }
        $this->booking->id          = (int)$id;
        $this->booking->customer_id = $_SESSION['customer_id'];

        if ($this->booking->cancelBooking()) {
            $_SESSION['success_msg'] = "Booking cancelled successfully.";
        } else {
            $_SESSION['error_msg'] = "Cannot cancel confirmed or already cancelled bookings.";
        }
        header("Location: index.php?route=my_bookings");
        exit();
    }
}
?>
