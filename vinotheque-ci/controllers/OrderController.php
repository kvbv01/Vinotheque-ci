<?php
require_once '../models/Database.php';
require_once '../models/Order.php';
require_once '../models/Cart.php';

class OrderController {
    private $db;
    private $order;
    private $cart;

    public function __construct() {
        $this->db = new Database();
        $this->order = new Order($this->db);
        $this->cart = new Cart($this->db);
    }

    public function placeOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $address = $_POST['address'];
            $total = $this->cart->getTotal($userId);
            $orderId = $this->order->createOrder($userId, $address, $total);
            if ($orderId) {
                $this->cart->clearCart($userId);
                header('Location: /orders');
            }
        }
    }

    public function viewOrders() {
        if (isset($_SESSION['user_id'])) {
            $orders = $this->order->getUserOrders($_SESSION['user_id']);
            include '../views/user/orders.php';
        } else {
            header('Location: /login');
        }
    }
}
?>