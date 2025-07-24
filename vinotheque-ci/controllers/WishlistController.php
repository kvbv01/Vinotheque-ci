<?php
require_once '../models/Wishlist.php';

class WishlistController {
    private $wishlistModel;

    public function __construct() {
        $this->wishlistModel = new Wishlist();
        $this->checkAuthentication();
    }

    private function checkAuthentication() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            $_SESSION['error'] = 'Vous devez vous connecter pour accéder à cette page';
            header('Location: /login');
            exit();
        }
    }

    public function index() {
        $user_id = $_SESSION['user']['id'];
        $wishlist = $this->wishlistModel->getUserWishlist($user_id);
        
        require_once '../views/wishlist/index.php';
    }

    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user']['id'];
            $product_id = $_POST['product_id'];
            
            if ($this->wishlistModel->isInWishlist($user_id, $product_id)) {
                $success = $this->wishlistModel->removeFromWishlist($user_id, $product_id);
                $action = 'removed';
            } else {
                $success = $this->wishlistModel->addToWishlist($user_id, $product_id);
                $action = 'added';
            }
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'action' => $action,
                    'in_wishlist' => !$this->wishlistModel->isInWishlist($user_id, $product_id)
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Une erreur est survenue'
                ]);
            }
        }
    }
}
?>