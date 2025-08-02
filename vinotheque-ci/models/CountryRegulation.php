<?php
class CountryRegulation {
    private $db;
    
    // Réglementations par défaut pour les pays africains
    private $regulations = [
        'CI' => [ // Côte d'Ivoire
            'legal_age' => 18,
            'sale_hours' => ['start' => '08:00', 'end' => '22:00'],
            'restricted_days' => [],
            'currency' => 'XOF',
            'name' => 'Côte d\'Ivoire'
        ],
        'SN' => [ // Sénégal
            'legal_age' => 18,
            'sale_hours' => ['start' => '08:00', 'end' => '20:00'],
            'restricted_days' => ['friday'], // Restrictions le vendredi
            'currency' => 'XOF',
            'name' => 'Sénégal'
        ],
        'MA' => [ // Maroc
            'legal_age' => 18,
            'sale_hours' => ['start' => '10:00', 'end' => '18:00'],
            'restricted_days' => ['friday'],
            'currency' => 'MAD',
            'name' => 'Maroc'
        ],
        'GH' => [ // Ghana
            'legal_age' => 18,
            'sale_hours' => ['start' => '08:00', 'end' => '21:00'],
            'restricted_days' => ['sunday'],
            'currency' => 'GHS',
            'name' => 'Ghana'
        ],
        'NG' => [ // Nigeria
            'legal_age' => 18,
            'sale_hours' => ['start' => '09:00', 'end' => '21:00'],
            'restricted_days' => ['sunday'],
            'currency' => 'NGN',
            'name' => 'Nigeria'
        ],
        'KE' => [ // Kenya
            'legal_age' => 18,
            'sale_hours' => ['start' => '09:00', 'end' => '23:00'],
            'restricted_days' => ['sunday'],
            'currency' => 'KES',
            'name' => 'Kenya'
        ],
        'ZA' => [ // Afrique du Sud
            'legal_age' => 18,
            'sale_hours' => ['start' => '08:00', 'end' => '18:00'],
            'restricted_days' => ['sunday'],
            'currency' => 'ZAR',
            'name' => 'Afrique du Sud'
        ]
    ];

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Obtenir les réglementations pour un pays donné
     */
    public function getCountryRegulations($country_code) {
        return isset($this->regulations[$country_code]) 
            ? $this->regulations[$country_code] 
            : $this->regulations['CI']; // Défaut: Côte d'Ivoire
    }

    /**
     * Vérifier si un utilisateur a l'âge légal dans son pays
     */
    public function isLegalAge($birth_date, $country_code) {
        $regulations = $this->getCountryRegulations($country_code);
        $birth = new DateTime($birth_date);
        $today = new DateTime();
        $age = $today->diff($birth)->y;
        
        return $age >= $regulations['legal_age'];
    }

    /**
     * Vérifier si la vente est autorisée à l'heure actuelle
     */
    public function isSaleAllowed($country_code, $datetime = null) {
        if (!$datetime) {
            $datetime = new DateTime();
        }
        
        $regulations = $this->getCountryRegulations($country_code);
        
        // Vérifier le jour de la semaine
        $day_name = strtolower($datetime->format('l'));
        if (in_array($day_name, $regulations['restricted_days'])) {
            return false;
        }
        
        // Vérifier les heures de vente
        $current_time = $datetime->format('H:i');
        $start_time = $regulations['sale_hours']['start'];
        $end_time = $regulations['sale_hours']['end'];
        
        return ($current_time >= $start_time && $current_time <= $end_time);
    }

    /**
     * Obtenir le prochain créneau de vente autorisé
     */
    public function getNextSaleWindow($country_code) {
        $regulations = $this->getCountryRegulations($country_code);
        $now = new DateTime();
        
        // Si la vente est actuellement autorisée
        if ($this->isSaleAllowed($country_code, $now)) {
            $end_today = clone $now;
            $end_today->setTime(
                ...explode(':', $regulations['sale_hours']['end'])
            );
            return [
                'available_now' => true,
                'end_time' => $end_today
            ];
        }
        
        // Trouver le prochain créneau disponible
        $next_window = clone $now;
        for ($i = 0; $i < 7; $i++) {
            if ($i > 0) {
                $next_window->add(new DateInterval('P1D'));
            }
            
            $day_name = strtolower($next_window->format('l'));
            if (!in_array($day_name, $regulations['restricted_days'])) {
                $next_window->setTime(
                    ...explode(':', $regulations['sale_hours']['start'])
                );
                
                if ($next_window > $now) {
                    return [
                        'available_now' => false,
                        'next_available' => $next_window
                    ];
                }
            }
        }
        
        return null;
    }

    /**
     * Obtenir tous les pays supportés
     */
    public function getSupportedCountries() {
        return array_map(function($reg) {
            return $reg['name'];
        }, $this->regulations);
    }

    /**
     * Obtenir la devise d'un pays
     */
    public function getCountryCurrency($country_code) {
        $regulations = $this->getCountryRegulations($country_code);
        return $regulations['currency'];
    }
}
?>