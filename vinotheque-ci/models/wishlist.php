<?php
class Wishlist {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addToWishlist($user_id, $product_id) {
        $conn = $this->db->connect();
        
        // Vérifier si le produit est déjà dans la wishlist
        $stmt = $conn->prepare("SELECT id FROM wishlists 
                              WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            return false;
        }
        
        $stmt = $conn->prepare("INSERT INTO wishlists (user_id, product_id) 
                              VALUES (:user_id, :product_id)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        
        return $stmt->execute();
    }

    public function removeFromWishlist($user_id, $product_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("DELETE FROM wishlists 
                              WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }

    public function getUserWishlist($user_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT p.* FROM products p
                              JOIN wishlists w ON p.id = w.product_id
                              WHERE w.user_id = :user_id
                              ORDER BY w.created_at DESC");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isInWishlist($user_id, $product_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT id FROM wishlists 
                              WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return (bool)$stmt->fetch();
    }
}
?>