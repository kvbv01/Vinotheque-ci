<?php
require_once '../models/Database.php';
require_once '../models/Product.php';

class ProductController {
    private $db;
    private $product;

    public function __construct() {
        $this->db = new Database();
        $this->product = new Product($this->db);
    }

    public function showProduct($id) {
        $product = $this->product->getProductById($id);
        if ($product) {
            include '../views/products/detail.php';
        } else {
            header('Location: /');
        }
    }

    public function searchProducts() {
        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $products = $this->product->searchProducts($query);
            include '../views/products/search.php';
        }
    }

    public function showCategory($category) {
        $products = $this->product->getProductsByCategory($category);
        include '../views/products/category.php';
    }
}
?>