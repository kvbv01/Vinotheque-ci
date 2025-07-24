<?php
require_once '../models/Database.php';
require_once '../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $this->db = new Database();
        $this->user = new User($this->db);
    }

    public function profile() {
        if (isset($_SESSION['user_id'])) {
            $user = $this->user->getUserById($_SESSION['user_id']);
            include '../views/user/profile.php';
        } else {
            header('Location: /login');
        }
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $this->user->updateUser($_SESSION['user_id'], $name, $email);
            header('Location: /profile');
        }
    }
}
?>