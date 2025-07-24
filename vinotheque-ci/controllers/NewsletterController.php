<?php
require_once '../models/Newsletter.php';

class NewsletterController {
    private $newsletterModel;

    public function __construct() {
        $this->newsletterModel = new Newsletter();
    }

    public function subscribe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            
            if ($email) {
                if ($this->newsletterModel->subscribe($email)) {
                    $_SESSION['newsletter_success'] = 'Merci pour votre inscription à notre newsletter!';
                } else {
                    $_SESSION['newsletter_error'] = 'Vous êtes déjà inscrit à notre newsletter.';
                }
            } else {
                $_SESSION['newsletter_error'] = 'Veuillez entrer une adresse email valide.';
            }
            
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    public function adminIndex() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /login');
            exit();
        }
        
        $subscribers = $this->newsletterModel->getAllSubscribers();
        require_once '../views/admin/newsletter.php';
    }
}
?>