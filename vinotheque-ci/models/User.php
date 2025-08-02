<?php
require_once 'CountryRegulation.php';

class User {
    private $db;
    private $countryRegulation;

    public function __construct() {
        $this->db = new Database();
        $this->countryRegulation = new CountryRegulation();
    }

    public function register($data) {
        // Vérification de l'âge légal selon le pays
        $country_code = $data['country_code'] ?? 'CI'; // Défaut: Côte d'Ivoire
        if (!$this->countryRegulation->isLegalAge($data['birth_date'], $country_code)) {
            return ['success' => false, 'error' => 'age_restriction', 'country' => $country_code];
        }
        
        $conn = $this->db->connect();
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, phone, birth_date, country_code, created_at) 
                               VALUES (:first_name, :last_name, :email, :password, :phone, :birth_date, :country_code, NOW())");
        
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':birth_date', $data['birth_date']);
        $stmt->bindParam(':country_code', $country_code);
        
        if ($stmt->execute()) {
            return ['success' => true, 'user_id' => $conn->lastInsertId()];
        }
        
        return ['success' => false, 'error' => 'database_error'];
    }

    public function login($email, $password) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Vérification de l'âge selon les réglementations du pays
            $country_code = $user['country_code'] ?? 'CI';
            if ($this->countryRegulation->isLegalAge($user['birth_date'], $country_code)) {
                // Vérifier si la vente est autorisée actuellement
                if ($this->countryRegulation->isSaleAllowed($country_code)) {
                    return $user;
                } else {
                    return ['error' => 'sale_not_allowed', 'user' => $user, 'next_window' => $this->countryRegulation->getNextSaleWindow($country_code)];
                }
            } else {
                return ['error' => 'age_restriction', 'country' => $country_code];
            }
        }
        
        return false;
    }

    public function findUserByEmail($email) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($user_id, $data) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("UPDATE users 
                               SET first_name = :first_name, last_name = :last_name, 
                                   phone = :phone, birth_date = :birth_date 
                               WHERE id = :id");
        
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':birth_date', $data['birth_date']);
        $stmt->bindParam(':id', $user_id);
        
        return $stmt->execute();
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
public function getTotalUsersCount() {
    $conn = $this->db->connect();
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

public function getAllUsers() {
    $conn = $this->db->connect();
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Vérifier si l'utilisateur peut effectuer un achat maintenant
 */
public function canPurchase($user_id) {
    $user = $this->getUserById($user_id);
    if (!$user) {
        return ['allowed' => false, 'reason' => 'user_not_found'];
    }
    
    $country_code = $user['country_code'] ?? 'CI';
    
    // Vérifier l'âge
    if (!$this->countryRegulation->isLegalAge($user['birth_date'], $country_code)) {
        return ['allowed' => false, 'reason' => 'age_restriction'];
    }
    
    // Vérifier les horaires de vente
    if (!$this->countryRegulation->isSaleAllowed($country_code)) {
        return [
            'allowed' => false, 
            'reason' => 'sale_hours_restriction',
            'next_window' => $this->countryRegulation->getNextSaleWindow($country_code)
        ];
    }
    
    return ['allowed' => true];
}

/**
 * Obtenir les pays supportés
 */
public function getSupportedCountries() {
    return $this->countryRegulation->getSupportedCountries();
}

/**
 * Obtenir les informations de réglementation pour un pays
 */
public function getCountryInfo($country_code) {
    return $this->countryRegulation->getCountryRegulations($country_code);
}