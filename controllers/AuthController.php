<?php
require_once 'config/Database.php';
require_once 'models/Admin.php';
require_once 'models/Customer.php';

class AuthController {
    private $db;

    public function __construct() {
        // Initialize database connection
        $database = new Database();
        $this->db = $database->getConnection();

        // Start session if needed
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showLogin() {
        // Redirect if already logged in
        if (isset($_SESSION['admin_id'])) {
            header("Location: index.php?route=admin_dashboard");
            exit();
        }
        if (isset($_SESSION['customer_id'])) {
            header("Location: index.php?route=home");
            exit();
        }
        require_once 'views/public/login.php';
    }

    public function showRegister() {
        require_once 'views/public/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLogin();
            return;
        }

        // Get login credentials
        $identifier = trim($_POST['identifier'] ?? '');
        $password   = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            $error = "Please enter your email and password.";
            require_once 'views/public/login.php';
            return;
        }

        $admin = new Admin($this->db);
        if ($admin->login($identifier, $password)) {
            // Store admin session
            $_SESSION['admin_id']       = $admin->id;
            $_SESSION['admin_username'] = $admin->username;
            $_SESSION['admin_email']    = $admin->email;

            unset($_SESSION['customer_id'], $_SESSION['customer_name']);
            header("Location: index.php?route=admin_dashboard");
            exit();
        }

        $customer = new Customer($this->db);
        if ($customer->loginByIdentifier($identifier, $password)) {
            // Store customer session
            $_SESSION['customer_id']   = $customer->id;
            $_SESSION['customer_name'] = $customer->name;

            unset($_SESSION['admin_id'], $_SESSION['admin_username']);

            // Redirect after login
            $redirect = $_SESSION['redirect_after_login'] ?? 'index.php?route=home';
            unset($_SESSION['redirect_after_login']);
            header("Location: " . $redirect);
            exit();
        }

        // Login failed
        $error = "Invalid email or password. Please try again.";
        require_once 'views/public/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer = new Customer($this->db);

            // Assign registration data
            $customer->name     = $_POST['name'];
            $customer->email    = $_POST['email'];
            $customer->phone    = $_POST['phone'];
            $customer->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if ($customer->register()) {
                $_SESSION['success_msg'] = "Registration successful! Please log in.";
                header("Location: index.php?route=login");
            } else {
                $error = "Email already registered or registration failed. Please try again.";
                require_once 'views/public/register.php';
            }
            exit();
        }
    }

    public function logout() {
        // End user session
        session_destroy();
        header("Location: index.php?route=login");
        exit();
    }
}
?>