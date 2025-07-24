<?php
require_once '../models/Promotion.php';
require_once '../models/Product.php';

class PromotionController {
    private $promotionModel;
    private $productModel;

    public function __construct() {
        $this->promotionModel = new Promotion();
        $this->productModel = new Product();
        $this->checkAdmin();
    }

    private function checkAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /login');
            exit();
        }
    }

    public function index() {
        $promotions = $this->promotionModel->getAllPromotions();
        require_once '../views/admin/promotions.php';
    }

    public function create() {
        $products = $this->productModel->getAllProducts();
        require_once '../views/admin/promotion_create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'discount_type' => $_POST['discount_type'],
                'discount_value' => $_POST['discount_value'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            $promotion_id = $this->promotionModel->createPromotion($data);

            if ($promotion_id) {
                // Ajouter les produits sélectionnés à la promotion
                if (!empty($_POST['products'])) {
                    foreach ($_POST['products'] as $product_id) {
                        $this->promotionModel->addProductToPromotion($promotion_id, $product_id);
                    }
                }

                $_SESSION['success'] = 'Promotion créée avec succès';
                header('Location: /admin/promotions');
                exit();
            } else {
                $_SESSION['error'] = 'Erreur lors de la création de la promotion';
                header('Location: /admin/promotions/create');
                exit();
            }
        }
    }

    public function edit($id) {
        $promotion = $this->promotionModel->getPromotionById($id);
        $promotion_products = $this->promotionModel->getProductsForPromotion($id);
        $products = $this->productModel->getAllProducts();

        require_once '../views/admin/promotion_edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'discount_type' => $_POST['discount_type'],
                'discount_value' => $_POST['discount_value'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            if ($this->promotionModel->updatePromotion($id, $data)) {
                // Mettre à jour les produits de la promotion
                $this->promotionModel->removeProductsFromPromotion($id);
                
                if (!empty($_POST['products'])) {
                    foreach ($_POST['products'] as $product_id) {
                        $this->promotionModel->addProductToPromotion($id, $product_id);
                    }
                }

                $_SESSION['success'] = 'Promotion mise à jour avec succès';
                header('Location: /admin/promotions');
                exit();
            } else {
                $_SESSION['error'] = 'Erreur lors de la mise à jour de la promotion';
                header('Location: /admin/promotions/edit/' . $id);
                exit();
            }
        }
    }
}
?>