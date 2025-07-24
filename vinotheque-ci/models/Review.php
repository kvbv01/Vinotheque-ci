<?php
class Review {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createReview($user_id, $order_id, $product_id, $rating, $comment) {
        $conn = $this->db->connect();
        
        // Vérifier que l'utilisateur a bien acheté ce produit
        $stmt = $conn->prepare("SELECT oi.id 
                              FROM order_items oi
                              JOIN orders o ON oi.order_id = o.id
                              WHERE oi.order_id = :order_id
                              AND oi.product_id = :product_id
                              AND o.user_id = :user_id");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        if (!$stmt->fetch()) {
            return false;
        }
        
        // Vérifier qu'il n'y a pas déjà un avis pour cette commande/produit
        $stmt = $conn->prepare("SELECT id FROM reviews 
                              WHERE user_id = :user_id 
                              AND order_id = :order_id
                              AND product_id = :product_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            return false;
        }
        
        // Créer l'avis
        $stmt = $conn->prepare("INSERT INTO reviews 
                              (user_id, order_id, product_id, rating, comment)
                              VALUES 
                              (:user_id, :order_id, :product_id, :rating, :comment)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);
        
        return $stmt->execute();
    }

    public function getProductReviews($product_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT r.*, u.first_name, u.last_name
                               FROM reviews r
                               JOIN users u ON r.user_id = u.id
                               WHERE r.product_id = :product_id
                               ORDER BY r.created_at DESC");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageRating($product_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT AVG(rating) as average, COUNT(*) as count
                               FROM reviews
                               WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserReviews($user_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT r.*, p.name as product_name, p.image
                               FROM reviews r
                               JOIN products p ON r.product_id = p.id
                               WHERE r.user_id = :user_id
                               ORDER BY r.created_at DESC");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>