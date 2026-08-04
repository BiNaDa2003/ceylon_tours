<?php
require_once 'config/Database.php';
require_once 'models/Admin.php';
require_once 'models/Customer.php';

class AuthController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    
    public function showLogin() {
        
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

        $identifier = trim($_POST['identifier'] ?? '');   
        $password   = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            $error = "Please enter your email and password.";
            require_once 'views/public/login.php';
            return;
        }

       
        $admin = new Admin($this->db);
        if ($admin->login($identifier, $password)) {
            // Successful admin login
            $_SESSION['admin_id']       = $admin->id;
            $_SESSION['admin_username'] = $admin->username;
            $_SESSION['admin_email']    = $admin->email;
            // Clear any stale customer session
            unset($_SESSION['customer_id'], $_SESSION['customer_name']);
            header("Location: index.php?route=admin_dashboard");
            exit();
        }

        
        $customer = new Customer($this->db);
        if ($customer->loginByIdentifier($identifier, $password)) {
            // Successful customer login
            $_SESSION['customer_id']   = $customer->id;
            $_SESSION['customer_name'] = $customer->name;
            
            unset($_SESSION['admin_id'], $_SESSION['admin_username']);

            
            $redirect = $_SESSION['redirect_after_login'] ?? 'index.php?route=home';
            unset($_SESSION['redirect_after_login']);
            header("Location: " . $redirect);
            exit();
        }

        
        $error = "Invalid email or password. Please try again.";
        require_once 'views/public/login.php';
    }

    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer = new Customer($this->db);
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
        session_destroy();
        header("Location: index.php?route=login");
        exit();
    }
}
?>
