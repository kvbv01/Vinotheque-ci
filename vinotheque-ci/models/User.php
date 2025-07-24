<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($data) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, phone, birth_date) 
                               VALUES (:first_name, :last_name, :email, :password, :phone, :birth_date)");
        
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':birth_date', $data['birth_date']);
        
        return $stmt->execute();
    }

    public function login($email, $password) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Vérification de l'âge (18 ans minimum)
            $birth_date = new DateTime($user['birth_date']);
            $today = new DateTime();
            $age = $today->diff($birth_date)->y;
            
            if ($age >= 18) {
                return $user;
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