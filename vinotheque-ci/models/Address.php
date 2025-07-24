<?php
class Address {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addAddress($user_id, $data) {
        $conn = $this->db->connect();
        
        // Si c'est la première adresse, la définir comme adresse par défaut
        $is_default = $this->countUserAddresses($user_id) === 0 ? 1 : 0;
        
        $stmt = $conn->prepare("INSERT INTO addresses 
                              (user_id, street, city, postal_code, country, is_default) 
                              VALUES 
                              (:user_id, :street, :city, :postal_code, :country, :is_default)");
        
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':street', $data['street']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':postal_code', $data['postal_code']);
        $stmt->bindParam(':country', $data['country']);
        $stmt->bindParam(':is_default', $is_default);
        
        return $stmt->execute();
    }

    public function getUserAddresses($user_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT * FROM addresses WHERE user_id = :user_id ORDER BY is_default DESC");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAddressById($id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT * FROM addresses WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function setDefaultAddress($user_id, $address_id) {
        $conn = $this->db->connect();
        
        try {
            $conn->beginTransaction();
            
            // Réinitialiser toutes les adresses de l'utilisateur
            $stmt = $conn->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            // Définir la nouvelle adresse par défaut
            $stmt = $conn->prepare("UPDATE addresses SET is_default = 1 WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $address_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }

    public function deleteAddress($user_id, $address_id) {
        $conn = $this->db->connect();
        
        // Vérifier si l'adresse appartient à l'utilisateur
        $address = $this->getAddressById($address_id);
        if (!$address || $address['user_id'] != $user_id) {
            return false;
        }
        
        // Si c'est l'adresse par défaut, on doit en choisir une autre
        if ($address['is_default']) {
            $other_addresses = $this->getUserAddresses($user_id);
            if (count($other_addresses) > 1) {
                foreach ($other_addresses as $addr) {
                    if ($addr['id'] != $address_id) {
                        $this->setDefaultAddress($user_id, $addr['id']);
                        break;
                    }
                }
            }
        }
        
        $stmt = $conn->prepare("DELETE FROM addresses WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $address_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    private function countUserAddresses($user_id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM addresses WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
?>