<?php
require_once '../models/Database.php';
require_once '../models/Cart.php';
require_once '../models/Product.php';

class CartController {
    private $db;
    private $cart;
    private $product;

    public function __construct() {
        $this->db = new Database();
        $this->cart = new Cart($this->db);
        $this->product = new Product($this->db);
    }

    public function viewCart() {
        if (isset($_SESSION['user_id'])) {
            $cartItems = $this->cart->getCartItems($_SESSION['user_id']);
            include '../views/cart/view.php';
        } else {
            header('Location: /login');
        }
    }

    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];
            $this->cart->addItem($_SESSION['user_id'], $productId, $quantity);
            header('Location: /cart');
        }
    }

    public function removeFromCart() {
        if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
            $this->cart->removeItem($_SESSION['user_id'], $_GET['id']);
            header('Location: /cart');
        }
    }

    public function checkout() {
        if (isset($_SESSION['user_id'])) {
            include '../views/cart/checkout.php';
        } else {
            header('Location: /login');
        }
    }
}
?>