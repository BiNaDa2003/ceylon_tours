<?php
require_once 'config/Database.php';
require_once 'models/Contact.php';

class ContactController {
    private $db;
    private $contact;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->contact = new Contact($this->db);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showContactForm() {
        require_once 'views/public/contact.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->contact->name = $_POST['name'];
            $this->contact->email = $_POST['email'];
            $this->contact->subject = $_POST['subject'];
            $this->contact->message = $_POST['message'];

            if ($this->contact->create()) {
                $_SESSION['success_msg'] = "Thank you for contacting us! We will get back to you soon.";
                header("Location: index.php?route=contact");
                exit();
            } else {
                $error = "Sorry, there was an error sending your message. Please try again.";
                require_once 'views/public/contact.php';
            }
        }
    }
}
?>
