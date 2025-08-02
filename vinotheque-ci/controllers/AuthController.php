<?php
require_once 'models/Database.php';
require_once 'models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $this->db = new Database();
        $this->user = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $result = $this->user->login($email, $password);
            
            if (is_array($result) && isset($result['error'])) {
                // Gestion des erreurs spécifiques
                switch ($result['error']) {
                    case 'age_restriction':
                        $error = "Vous devez avoir l'âge légal pour accéder à ce site.";
                        break;
                    case 'sale_not_allowed':
                        $next_window = $result['next_window'];
                        if ($next_window['available_now']) {
                            $error = "Les ventes se terminent à " . $next_window['end_time']->format('H:i') . ".";
                        } else {
                            $error = "Les ventes sont actuellement fermées. Prochaine ouverture : " . 
                                   $next_window['next_available']->format('d/m/Y à H:i') . ".";
                        }
                        break;
                    default:
                        $error = "Erreur de connexion.";
                }
                include 'views/auth/login.php';
            } elseif ($result) {
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['user_role'] = $result['role'] ?? 'customer';
                $_SESSION['country_code'] = $result['country_code'] ?? 'CI';
                header('Location: /');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
                include 'views/auth/login.php';
            }
        } else {
            include 'views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'phone' => $_POST['phone'],
                'birth_date' => $_POST['birth_date'],
                'country_code' => $_POST['country_code'] ?? 'CI'
            ];
            
            $result = $this->user->register($data);
            
            if ($result['success']) {
                $_SESSION['registration_success'] = true;
                header('Location: /login');
                exit;
            } else {
                switch ($result['error']) {
                    case 'age_restriction':
                        $error = "Vous devez avoir l'âge légal dans votre pays pour vous inscrire.";
                        break;
                    case 'database_error':
                        $error = "Erreur lors de l'inscription. Veuillez réessayer.";
                        break;
                    default:
                        $error = "L'inscription a échoué.";
                }
                $countries = $this->user->getSupportedCountries();
                include 'views/auth/register.php';
            }
        } else {
            $countries = $this->user->getSupportedCountries();
            include 'views/auth/register.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
    }
}
?>