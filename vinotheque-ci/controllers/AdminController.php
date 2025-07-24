<?php
require_once '../models/Database.php';
require_once '../models/Product.php';
require_once '../models/User.php';
require_once '../models/Order.php';

class AdminController {
    private $db;
    private $product;
    private $user;
    private $order;

    public function __construct() {
        $this->db = new Database();
        $this->product = new Product($this->db);
        $this->user = new User($this->db);
        $this->order = new Order($this->db);
    }

    public function dashboard() {
        $totalProducts = $this->product->countProducts();
        $totalUsers = $this->user->countUsers();
        $totalOrders = $this->order->countOrders();
        include '../views/admin/dashboard.php';
    }

    public function manageProducts() {
        $products = $this->product->getAllProducts();
        include '../views/admin/products.php';
    }

    public function manageUsers() {
        $users = $this->user->getAllUsers();
        include '../views/admin/users.php';
    }

    public function manageOrders() {
        $orders = $this->order->getAllOrders();
        include '../views/admin/orders.php';
    }
}
?>