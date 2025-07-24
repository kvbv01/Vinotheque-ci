<?php
// models/products/order.php

require_once __DIR__ . '/../Database.php';

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, total, status, created_at)
                VALUES (:user_id, :total, :status, NOW())
            ");
            
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':total', $data['total']);
            $stmt->bindParam(':status', $data['status']);
            
            $stmt->execute();
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de la commande: " . $e->getMessage());
            return false;
        }
    }

    public function addOrderItem($orderId, $productId, $quantity) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, quantity)
                VALUES (:order_id, :product_id, :quantity)
            ");
            
            $stmt->bindParam(':order_id', $orderId);
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':quantity', $quantity);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout d'un article à la commande: " . $e->getMessage());
            return false;
        }
    }

    public function getOrdersByUser($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM orders 
                WHERE user_id = :user_id
                ORDER BY created_at DESC
            ");
            
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des commandes: " . $e->getMessage());
            return [];
        }
    }

    public function getOrderById($orderId) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM orders 
                WHERE id = :order_id
            ");
            
            $stmt->bindParam(':order_id', $orderId);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la commande: " . $e->getMessage());
            return null;
        }
    }

    public function getOrderItems($orderId) {
        try {
            $stmt = $this->db->prepare("
                SELECT oi.*, p.name, p.price, p.image_url
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id
            ");
            
            $stmt->bindParam(':order_id', $orderId);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des articles de commande: " . $e->getMessage());
            return [];
        }
    }

    public function updateStatus($orderId, $status) {
        try {
            $stmt = $this->db->prepare("
                UPDATE orders 
                SET status = :status
                WHERE id = :order_id
            ");
            
            $stmt->bindParam(':order_id', $orderId);
            $stmt->bindParam(':status', $status);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut de la commande: " . $e->getMessage());
            return false;
        }
    }
}
?>