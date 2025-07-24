<?php
class Promotion {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getActivePromotions() {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT * FROM promotions 
                              WHERE is_active = 1 
                              AND start_date <= NOW() 
                              AND end_date >= NOW()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPromotionsForProduct($product_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT p.* FROM promotions p
                               JOIN product_promotions pp ON p.id = pp.promotion_id
                               WHERE pp.product_id = :product_id
                               AND p.is_active = 1
                               AND p.start_date <= NOW() 
                               AND p.end_date >= NOW()");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPromotions() {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT * FROM promotions ORDER BY start_date DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPromotion($data) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("INSERT INTO promotions 
                               (name, description, discount_type, discount_value, 
                               start_date, end_date, is_active) 
                               VALUES 
                               (:name, :description, :discount_type, :discount_value, 
                               :start_date, :end_date, :is_active)");
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':discount_type', $data['discount_type']);
        $stmt->bindParam(':discount_value', $data['discount_value']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':is_active', $data['is_active']);
        
        return $stmt->execute() ? $conn->lastInsertId() : false;
    }

    public function addProductToPromotion($promotion_id, $product_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("INSERT INTO product_promotions 
                               (product_id, promotion_id) 
                               VALUES 
                               (:product_id, :promotion_id)");
        
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':promotion_id', $promotion_id);
        
        return $stmt->execute();
    }

    public function getPromotionById($id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT * FROM promotions WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductsForPromotion($promotion_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT p.* FROM products p
                               JOIN product_promotions pp ON p.id = pp.product_id
                               WHERE pp.promotion_id = :promotion_id");
        $stmt->bindParam(':promotion_id', $promotion_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePromotion($id, $data) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("UPDATE promotions SET 
                               name = :name,
                               description = :description,
                               discount_type = :discount_type,
                               discount_value = :discount_value,
                               start_date = :start_date,
                               end_date = :end_date,
                               is_active = :is_active
                               WHERE id = :id");
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':discount_type', $data['discount_type']);
        $stmt->bindParam(':discount_value', $data['discount_value']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':is_active', $data['is_active']);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function removeProductsFromPromotion($promotion_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("DELETE FROM product_promotions WHERE promotion_id = :promotion_id");
        $stmt->bindParam(':promotion_id', $promotion_id);
        return $stmt->execute();
    }
}
?>