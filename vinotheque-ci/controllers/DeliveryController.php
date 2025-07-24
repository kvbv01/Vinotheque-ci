<?php
require_once '../models/Delivery.php';
require_once '../models/Order.php';

class DeliveryController {
    private $deliveryModel;
    private $orderModel;

    public function __construct() {
        $this->deliveryModel = new Delivery();
        $this->orderModel = new Order();
        $this->checkAuthentication();
    }

    private function checkAuthentication() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            $_SESSION['error'] = 'Vous devez vous connecter pour accéder à cette page';
            header('Location: /login');
            exit();
        }
    }

    public function index() {
        // Pour les livreurs: afficher leurs livraisons
        if ($_SESSION['user']['role'] === 'delivery') {
            $deliveries = $this->deliveryModel->getDeliveryPersonDeliveries($_SESSION['user']['id']);
            require_once '../views/delivery/person_deliveries.php';
        } 
        // Pour les admins: afficher toutes les livraisons
        else if ($_SESSION['user']['role'] === 'admin') {
            $pending_deliveries = $this->deliveryModel->getDeliveriesByStatus('pending');
            $assigned_deliveries = $this->deliveryModel->getDeliveriesByStatus('assigned');
            $in_transit_deliveries = $this->deliveryModel->getDeliveriesByStatus('in_transit');
            
            require_once '../views/delivery/admin_index.php';
        } 
        // Pour les clients: rediriger vers leur profil
        else {
            header('Location: /user/profile');
            exit();
        }
    }

    public function show($delivery_id) {
        $delivery = $this->deliveryModel->getDeliveryDetails($delivery_id);
        
        if (!$delivery) {
            $_SESSION['error'] = 'Livraison non trouvée';
            header('Location: /deliveries');
            exit();
        }
        
        // Vérifier les permissions
        $user = $_SESSION['user'];
        if ($user['role'] === 'customer' && $delivery['customer_id'] != $user['id']) {
            $_SESSION['error'] = 'Accès non autorisé';
            header('Location: /user/profile');
            exit();
        }
        
        if ($user['role'] === 'delivery' && $delivery['delivery_person_id'] != $user['id']) {
            $_SESSION['error'] = 'Accès non autorisé';
            header('Location: /deliveries');
            exit();
        }
        
        require_once '../views/delivery/show.php';
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $delivery_id = $_POST['delivery_id'];
            $status = $_POST['status'];
            $notes = $_POST['notes'] ?? null;
            
            if ($this->deliveryModel->updateDeliveryStatus($delivery_id, $status, $notes)) {
                $_SESSION['success'] = 'Statut de livraison mis à jour';
                
                // Si la livraison est marquée comme livrée, mettre à jour le statut de la commande
                if ($status === 'delivered') {
                    $delivery = $this->deliveryModel->getDeliveryDetails($delivery_id);
                    $this->orderModel->updateOrderStatus($delivery['order_id'], 'delivered');
                }
            } else {
                $_SESSION['error'] = 'Erreur lors de la mise à jour du statut';
            }
            
            header('Location: ' . ($_SESSION['user']['role'] === 'admin' ? '/admin/deliveries' : '/deliveries'));
            exit();
        }
    }

    public function assign() {
        if ($_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = 'Accès non autorisé';
            header('Location: /deliveries');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $delivery_id = $_POST['delivery_id'];
            $delivery_person_id = $_POST['delivery_person_id'];
            
            if ($this->deliveryModel->assignDeliveryToPerson($delivery_id, $delivery_person_id)) {
                $_SESSION['success'] = 'Livreur assigné avec succès';
            } else {
                $_SESSION['error'] = 'Erreur lors de l\'assignation du livreur';
            }
            
            header('Location: /admin/deliveries');
            exit();
        }
        
        $delivery_persons = $this->deliveryModel->getAvailableDeliveryPersons();
        require_once '../views/delivery/assign.php';
    }
}
?>