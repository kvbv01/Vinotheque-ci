<?php
// models/products/product.php

require_once __DIR__ . '/../Database.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllProducts($limit = null, $offset = 0) {
        try {
            $query = "SELECT p.*, c.name as category_name 
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.id";
            
            if ($limit !== null) {
                $query .= " LIMIT :limit OFFSET :offset";
            }
            
            $stmt = $this->db->prepare($query);
            
            if ($limit !== null) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des produits: " . $e->getMessage());
            return [];
        }
    }

    public function getProductById($productId) {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :product_id
            ");
            
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du produit: " . $e->getMessage());
            return null;
        }
    }

    public function getProductsByCategory($categoryId, $limit = null, $offset = 0) {
        try {
            $query = "SELECT p.*, c.name as category_name 
                      FROM products p
                      JOIN categories c ON p.category_id = c.id
                      WHERE p.category_id = :category_id";
            
            if ($limit !== null) {
                $query .= " LIMIT :limit OFFSET :offset";
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            
            if ($limit !== null) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des produits par catégorie: " . $e->getMessage());
            return [];
        }
    }

    public function searchProducts($searchTerm, $limit = null, $offset = 0) {
        try {
            $query = "SELECT p.*, c.name as category_name 
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.id
                      WHERE p.name LIKE :search_term OR p.description LIKE :search_term";
            
            if ($limit !== null) {
                $query .= " LIMIT :limit OFFSET :offset";
            }
            
            $stmt = $this->db->prepare($query);
            $searchTerm = "%$searchTerm%";
            $stmt->bindParam(':search_term', $searchTerm);
            
            if ($limit !== null) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche de produits: " . $e->getMessage());
            return [];
        }
    }

    public function countProducts() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM products");
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des produits: " . $e->getMessage());
            return 0;
        }
    }
}
?>