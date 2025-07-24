<?php
class Delivery {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getDeliveriesByStatus($status) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT d.*, o.order_number, u.first_name as customer_name, 
                               a.city, a.street, a.postal_code
                               FROM deliveries d
                               JOIN orders o ON d.order_id = o.id
                               JOIN users u ON o.user_id = u.id
                               JOIN addresses a ON o.address_id = a.id
                               WHERE d.status = :status
                               ORDER BY d.created_at");
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDeliveryPersonDeliveries($delivery_person_id, $status = null) {
        $conn = $this->db->connect();
        
        $query = "SELECT d.*, o.order_number, u.first_name as customer_name, 
                 a.city, a.street, a.postal_code, o.total_amount
                 FROM deliveries d
                 JOIN orders o ON d.order_id = o.id
                 JOIN users u ON o.user_id = u.id
                 JOIN addresses a ON o.address_id = a.id
                 WHERE d.delivery_person_id = :delivery_person_id";
        
        if ($status) {
            $query .= " AND d.status = :status";
        }
        
        $query .= " ORDER BY d.delivery_date, d.delivery_time";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':delivery_person_id', $delivery_person_id);
        
        if ($status) {
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateDeliveryStatus($delivery_id, $status, $notes = null) {
        $conn = $this->db->connect();
        
        $stmt = $conn->prepare("UPDATE deliveries 
                               SET status = :status, notes = :notes, updated_at = NOW() 
                               WHERE id = :delivery_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':delivery_id', $delivery_id);
        
        return $stmt->execute();
    }

    public function getDeliveryDetails($delivery_id) {
        $conn = $this->db->connect();
        
        $stmt = $conn->prepare("SELECT d.*, o.order_number, o.total_amount, o.payment_method,
                               u.first_name as customer_name, u.phone as customer_phone,
                               a.street, a.city, a.postal_code, a.country,
                               dp.first_name as delivery_person_name, dp.phone as delivery_person_phone
                               FROM deliveries d
                               JOIN orders o ON d.order_id = o.id
                               JOIN users u ON o.user_id = u.id
                               JOIN addresses a ON o.address_id = a.id
                               LEFT JOIN users dp ON d.delivery_person_id = dp.id
                               WHERE d.id = :delivery_id");
        $stmt->bindParam(':delivery_id', $delivery_id);
        $stmt->execute();
        
        $delivery = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($delivery) {
            // Récupérer les articles de la commande
            $stmt = $conn->prepare("SELECT oi.*, p.name, p.image 
                                   FROM order_items oi
                                   JOIN products p ON oi.product_id = p.id
                                   WHERE oi.order_id = (SELECT order_id FROM deliveries WHERE id = :delivery_id)");
            $stmt->bindParam(':delivery_id', $delivery_id);
            $stmt->execute();
            
            $delivery['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $delivery;
    }

    public function assignDeliveryToPerson($delivery_id, $delivery_person_id) {
        $conn = $this->db->connect();
        
        $stmt = $conn->prepare("UPDATE deliveries 
                               SET delivery_person_id = :delivery_person_id, 
                                   status = 'assigned', 
                                   updated_at = NOW() 
                               WHERE id = :delivery_id");
        $stmt->bindParam(':delivery_person_id', $delivery_person_id);
        $stmt->bindParam(':delivery_id', $delivery_id);
        
        return $stmt->execute();
    }

    public function getAvailableDeliveryPersons() {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT id, first_name, last_name, phone 
                               FROM users 
                               WHERE role = 'delivery' 
                               ORDER BY first_name");
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>