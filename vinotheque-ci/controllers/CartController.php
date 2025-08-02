<?php
require_once 'models/Database.php';
require_once 'models/Cart.php';
require_once 'models/Product.php';
require_once 'models/User.php';
require_once 'middleware/SaleTimeMiddleware.php';

class CartController {
    private $db;
    private $cart;
    private $product;
    private $user;
    private $saleTimeMiddleware;

    public function __construct() {
        $this->db = new Database();
        $this->cart = new Cart();
        $this->product = new Product();
        $this->user = new User();
        $this->saleTimeMiddleware = new SaleTimeMiddleware();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        // Vérifier si l'utilisateur peut effectuer des achats
        $purchaseCheck = $this->user->canPurchase($_SESSION['user_id']);
        if (!$purchaseCheck['allowed']) {
            $_SESSION['purchase_restriction'] = $purchaseCheck;
        }
        
        $cartItems = $this->cart->getCartItems($_SESSION['user_id']);
        $saleTimeCheck = $this->saleTimeMiddleware->checkSaleTime();
        
        include 'views/cart/index.php';
    }

    public function add() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        // Vérifier les horaires de vente
        $saleCheck = $this->saleTimeMiddleware->checkSaleTime();
        if (!$saleCheck['allowed']) {
            $_SESSION['sale_restriction'] = $saleCheck;
            header('Location: /sale-restricted');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = intval($_POST['quantity']);
            
            // Vérifier la disponibilité du produit
            $product = $this->product->getById($productId);
            if (!$product || $product['stock'] < $quantity) {
                $_SESSION['error'] = 'Produit non disponible ou stock insuffisant.';
                header('Location: /product/' . $productId);
                exit;
            }
            
            $result = $this->cart->addItem($_SESSION['user_id'], $productId, $quantity);
            if ($result) {
                $_SESSION['success'] = 'Produit ajouté au panier avec succès.';
            } else {
                $_SESSION['error'] = 'Erreur lors de l\'ajout au panier.';
            }
            
            header('Location: /cart');
            exit;
        }
    }

    public function remove() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        if (isset($_GET['id'])) {
            $result = $this->cart->removeItem($_SESSION['user_id'], $_GET['id']);
            if ($result) {
                $_SESSION['success'] = 'Produit retiré du panier.';
            } else {
                $_SESSION['error'] = 'Erreur lors de la suppression.';
            }
        }
        
        header('Location: /cart');
        exit;
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        // Vérifier les horaires de vente
        $saleCheck = $this->saleTimeMiddleware->checkSaleTime();
        if (!$saleCheck['allowed']) {
            $_SESSION['sale_restriction'] = $saleCheck;
            header('Location: /sale-restricted');
            exit;
        }
        
        // Vérifier si l'utilisateur peut effectuer des achats
        $purchaseCheck = $this->user->canPurchase($_SESSION['user_id']);
        if (!$purchaseCheck['allowed']) {
            $_SESSION['purchase_restriction'] = $purchaseCheck;
            header('Location: /purchase-restricted');
            exit;
        }
        
        $cartItems = $this->cart->getCartItems($_SESSION['user_id']);
        if (empty($cartItems)) {
            $_SESSION['error'] = 'Votre panier est vide.';
            header('Location: /cart');
            exit;
        }
        
        include 'views/cart/checkout.php';
    }
}
?>