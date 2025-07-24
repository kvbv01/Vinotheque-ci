<?php
require_once '../models/Database.php';
require_once '../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $this->db = new Database();
        $this->user = new User($this->db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->user->login($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: /');
            } else {
                $error = "Email ou mot de passe incorrect.";
                include '../views/auth/login.php';
            }
        } else {
            include '../views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            if ($this->user->register($name, $email, $password)) {
                header('Location: /login');
            } else {
                $error = "L'inscription a échoué.";
                include '../views/auth/register.php';
            }
        } else {
            include '../views/auth/register.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
    }
}
?>