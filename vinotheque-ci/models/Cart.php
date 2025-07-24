<?php
class Cart {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addToCart($user_id, $product_id, $quantity = 1) {
        $conn = $this->db->connect();
        
        // Vérifier si le produit est déjà dans le panier
        $stmt = $conn->prepare("SELECT * FROM carts WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            // Mettre à jour la quantité
            $new_quantity = $existing['quantity'] + $quantity;
            return $this->updateCartItem($user_id, $product_id, $new_quantity);
        } else {
            // Ajouter un nouvel article
            $stmt = $conn->prepare("INSERT INTO carts (user_id, product_id, quantity) 
                                  VALUES (:user_id, :product_id, :quantity)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':quantity', $quantity);
            return $stmt->execute();
        }
    }

    public function updateCartItem($user_id, $product_id, $quantity) {
        $conn = $this->db->connect();
        
        if ($quantity <= 0) {
            return $this->removeFromCart($user_id, $product_id);
        }
        
        $stmt = $conn->prepare("UPDATE carts SET quantity = :quantity 
                               WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }

    public function removeFromCart($user_id, $product_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("DELETE FROM carts 
                               WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }

    public function getUserCart($user_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT c.*, p.name, p.price, p.discount_price, p.image 
                               FROM carts c 
                               JOIN products p ON c.product_id = p.id 
                               WHERE c.user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartCount($user_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT SUM(quantity) as count FROM carts WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function clearCart($user_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("DELETE FROM carts WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    public function calculateCartTotals($user_id) {
        $cart_items = $this->getUserCart($user_id);
        
        $subtotal = 0;
        $delivery_fee = 0;
        
        foreach ($cart_items as $item) {
            $price = $item['discount_price'] ?? $item['price'];
            $subtotal += $price * $item['quantity'];
        }
        
        // Livraison gratuite à partir de 60 000 FCFA
        if ($subtotal < 60000) {
            $delivery_fee = 5000; // Exemple: 5 000 FCFA de frais de livraison
        }
        
        $total = $subtotal + $delivery_fee;
        
        return [
            'subtotal' => $subtotal,
            'delivery_fee' => $delivery_fee,
            'total' => $total
        ];
    }
}
?>