<?php
require_once '../models/Database.php';
require_once '../models/Product.php';

class HomeController {
    private $db;
    private $product;

    public function __construct() {
        $this->db = new Database();
        $this->product = new Product($this->db);
    }

    public function index() {
        $featuredProducts = $this->product->getFeaturedProducts();
        include '../views/home/index.php';
    }

    public function about() {
        include '../views/home/about.php';
    }

    public function contact() {
        include '../views/home/contact.php';
    }
}
?>