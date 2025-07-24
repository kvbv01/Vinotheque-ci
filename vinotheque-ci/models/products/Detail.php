<?php
// models/products/detail.php

require_once __DIR__ . '/../Database.php';

class ProductDetail {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getProductDetails($productId) {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, c.name as category_name, 
                       GROUP_CONCAT(DISTINCT i.image_url) as images,
                       AVG(r.rating) as average_rating,
                       COUNT(r.id) as review_count
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_images i ON p.id = i.product_id
                LEFT JOIN reviews r ON p.id = r.product_id
                WHERE p.id = :product_id
                GROUP BY p.id
            ");
            
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                // Convertir les images en tableau
                $product['images'] = $product['images'] ? explode(',', $product['images']) : [];
                
                // Récupérer les avis détaillés
                $product['reviews'] = $this->getProductReviews($productId);
                
                return $product;
            }
            
            return null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des détails du produit: " . $e->getMessage());
            return null;
        }
    }

    private function getProductReviews($productId) {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, u.username, u.profile_image
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.product_id = :product_id
                ORDER BY r.created_at DESC
            ");
            
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des avis: " . $e->getMessage());
            return [];
        }
    }
}
?>