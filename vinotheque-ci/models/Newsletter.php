<?php
class Newsletter {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function subscribe($email) {
        $conn = $this->db->connect();
        
        // Vérifier si l'email existe déjà
        $stmt = $conn->prepare("SELECT * FROM newsletter_subscriptions WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            return false; // Déjà inscrit
        }
        
        $stmt = $conn->prepare("INSERT INTO newsletter_subscriptions (email, subscribed_at) 
                               VALUES (:email, NOW())");
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function unsubscribe($email) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("DELETE FROM newsletter_subscriptions WHERE email = :email");
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function getAllSubscribers() {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT * FROM newsletter_subscriptions ORDER BY subscribed_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>