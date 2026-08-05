<?php

class Admin {
    private $conn;
    private $table_name = "admins";

    public $id;
    public $username;
    public $email;
    public $password;

    // Initialize database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Verify admin login
    public function login($email, $password) {
        $query = "SELECT id, username, email, password FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        // Sanitize email input
        $email = htmlspecialchars(strip_tags($email));

        $stmt->bindParam(1, $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check password
            if (password_verify($password, $row['password']) || $password === $row['password']) {
                $this->id       = $row['id'];
                $this->username = $row['username'];
                $this->email    = $row['email'];
                return true;
            }
        }
        return false;
    }

    // Get dashboard statistics
    public function getDashboardStats() {
        $stats = [];

        // Count total packages
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM tour_packages");
        $stmt->execute();
        $stats['total_packages'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Count total bookings
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM bookings");
        $stmt->execute();
        $stats['total_bookings'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Count total customers
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM customers");
        $stmt->execute();
        $stats['total_customers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Calculate total revenue
        $stmt = $this->conn->prepare(
            "SELECT COALESCE(SUM(b.travelers * p.price), 0) as revenue
             FROM bookings b
             LEFT JOIN tour_packages p ON b.package_id = p.id
             WHERE b.booking_status = 'Confirmed'"
        );
        $stmt->execute();
        $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];

        // Count pending bookings
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM bookings WHERE booking_status = 'Pending'");
        $stmt->execute();
        $stats['pending_bookings'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Check pending custom requests
        $custom_table_check = $this->conn->prepare("SHOW TABLES LIKE 'custom_packages'");
        $custom_table_check->execute();
        if ($custom_table_check->rowCount() > 0) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM custom_packages WHERE status = 'Pending'");
            $stmt->execute();
            $stats['pending_custom'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            $stats['pending_custom'] = 0;
        }

        // Get monthly booking data
        $stmt = $this->conn->prepare(
            "SELECT DATE_FORMAT(created_at, '%b %Y') as month,
                    DATE_FORMAT(created_at, '%Y-%m') as month_key,
                    COUNT(*) as count
             FROM bookings
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
             GROUP BY month_key, month
             ORDER BY month_key ASC"
        );
        $stmt->execute();
        $monthly = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats['monthly_labels'] = array_column($monthly, 'month');
        $stats['monthly_counts'] = array_column($monthly, 'count');

        // Get top 5 packages
        $stmt = $this->conn->prepare(
            "SELECT p.title, COUNT(b.id) as bookings
             FROM tour_packages p
             LEFT JOIN bookings b ON p.id = b.package_id
             GROUP BY p.id ORDER BY bookings DESC LIMIT 5"
        );
        $stmt->execute();
        $stats['top_packages'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get recent bookings
        $stmt = $this->conn->prepare(
            "SELECT b.id, b.booking_status, b.travel_date, b.travelers,
                    p.title as package_title, c.name as customer_name
             FROM bookings b
             LEFT JOIN tour_packages p ON b.package_id = p.id
             LEFT JOIN customers c ON b.customer_id = c.id
             ORDER BY b.created_at DESC LIMIT 5"
        );
        $stmt->execute();
        $stats['recent_bookings'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }
}
?>