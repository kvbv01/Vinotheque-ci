<?php
require_once 'models/CountryRegulation.php';

class SaleTimeMiddleware {
    private $countryRegulation;
    
    public function __construct() {
        $this->countryRegulation = new CountryRegulation();
    }
    
    /**
     * Vérifier si les ventes sont autorisées pour l'utilisateur connecté
     */
    public function checkSaleTime() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['country_code'])) {
            return ['allowed' => false, 'reason' => 'not_authenticated'];
        }
        
        $country_code = $_SESSION['country_code'];
        
        if (!$this->countryRegulation->isSaleAllowed($country_code)) {
            $next_window = $this->countryRegulation->getNextSaleWindow($country_code);
            return [
                'allowed' => false, 
                'reason' => 'sale_hours_restriction',
                'next_window' => $next_window,
                'country_code' => $country_code
            ];
        }
        
        return ['allowed' => true];
    }
    
    /**
     * Middleware pour les routes de vente
     */
    public function handle($request, $next) {
        $check = $this->checkSaleTime();
        
        if (!$check['allowed']) {
            switch ($check['reason']) {
                case 'not_authenticated':
                    header('Location: /login');
                    exit;
                    
                case 'sale_hours_restriction':
                    $_SESSION['sale_restriction'] = $check;
                    header('Location: /sale-restricted');
                    exit;
            }
        }
        
        return $next($request);
    }
    
    /**
     * Obtenir un message d'erreur localisé
     */
    public function getSaleRestrictionMessage($country_code, $next_window) {
        $regulations = $this->countryRegulation->getCountryRegulations($country_code);
        $country_name = $regulations['name'];
        
        if ($next_window['available_now']) {
            return "Les ventes se terminent aujourd'hui à " . 
                   $next_window['end_time']->format('H:i') . 
                   " selon la réglementation de " . $country_name . ".";
        } else {
            return "Les ventes sont actuellement fermées en " . $country_name . 
                   ". Prochaine ouverture : " . 
                   $next_window['next_available']->format('d/m/Y à H:i') . ".";
        }
    }
    
    /**
     * Vérifier si c'est un jour restreint
     */
    public function isRestrictedDay($country_code, $date = null) {
        if (!$date) {
            $date = new DateTime();
        }
        
        $regulations = $this->countryRegulation->getCountryRegulations($country_code);
        $day_name = strtolower($date->format('l'));
        
        return in_array($day_name, $regulations['restricted_days']);
    }
    
    /**
     * Obtenir les heures de vente pour un pays
     */
    public function getSaleHours($country_code) {
        $regulations = $this->countryRegulation->getCountryRegulations($country_code);
        return $regulations['sale_hours'];
    }
}
?>