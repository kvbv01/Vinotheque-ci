# VINOTHEQUE-CI 🍷

**Application web de vente en ligne de vins et spiritueux avec service de livraison intégré, spécialement conçue pour les marchés africains.**

![VINOTHEQUE CI](https://img.shields.io/badge/VINOTHEQUE-CI-gold?style=for-the-badge&logo=wine)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php&logoColor=white)
![Mobile First](https://img.shields.io/badge/Mobile-First-green?style=flat&logo=mobile)
![PWA](https://img.shields.io/badge/PWA-Ready-purple?style=flat&logo=pwa)

## 🌍 Vue d'ensemble

VINOTHEQUE-CI est une plateforme e-commerce moderne et responsive, optimisée pour les marchés africains. Elle respecte les réglementations locales concernant la vente d'alcool et offre une expérience utilisateur adaptée aux contraintes spécifiques de l'Afrique (connexions mobiles, réglementations variables, pratiques commerciales locales).

### ✨ Fonctionnalités principales

- **🔞 Vérification d'âge légal** par pays africain
- **⏰ Gestion des horaires de vente** selon les réglementations locales
- **📱 Design mobile-first** optimisé pour l'Afrique
- **🌐 PWA (Progressive Web App)** installable
- **📍 Géolocalisation et zones de livraison**
- **🔒 Sécurité renforcée** et conformité RGPD
- **💳 Méthodes de paiement africaines** (Mobile Money, etc.)
- **🌍 Support multilingue** (français, anglais, langues locales)
- **📦 Gestion avancée des stocks**

## 🏗️ Architecture

### Structure du projet

```
vinotheque-ci/
├── assets/
│   ├── css/
│   │   ├── style.css                 # Styles de base
│   │   └── mobile-africa.css         # Styles optimisés mobile Afrique
│   └── js/
│       └── mobile-optimizations.js   # Optimisations JavaScript mobile
├── config/
│   ├── Database.php                  # Configuration base de données
│   ├── Cache.php                     # Système de cache
│   └── Logger.php                    # Système de logs
├── controllers/
│   ├── AuthController.php            # Authentification avec vérification d'âge
│   ├── CartController.php            # Panier avec restrictions horaires
│   ├── ProductController.php         # Gestion des produits
│   ├── DeliveryController.php        # Gestion des livraisons
│   └── ...
├── models/
│   ├── User.php                      # Utilisateurs avec pays et âge
│   ├── Product.php                   # Produits avec gestion stock
│   ├── CountryRegulation.php         # Réglementations par pays
│   ├── Cart.php                      # Panier d'achats
│   └── ...
├── middleware/
│   └── SaleTimeMiddleware.php        # Vérification horaires de vente
├── views/
│   ├── includes/
│   │   ├── header.php               # Header avec PWA et meta tags
│   │   ├── footer.php               # Footer responsive
│   │   └── navbar.php               # Navigation mobile-first
│   ├── auth/
│   │   ├── login.php                # Connexion avec restrictions
│   │   └── register.php             # Inscription avec vérification pays/âge
│   ├── cart/
│   ├── products/
│   └── sale-restricted.php          # Page restrictions de vente
├── manifest.json                     # Manifeste PWA
├── docker-compose.yml               # Configuration Docker
├── composer.json                    # Dépendances PHP
└── index.php                       # Point d'entrée avec routeur
```

### Technologies utilisées

- **Backend**: PHP 8.0+, PDO MySQL
- **Frontend**: HTML5, CSS3 (Mobile-First), JavaScript ES6+
- **Base de données**: MySQL/MariaDB
- **Cache**: Redis/Memcached (optionnel)
- **PWA**: Service Workers, Web App Manifest
- **Conteneurisation**: Docker & Docker Compose

## 🚀 Installation

### Prérequis

- PHP 8.0 ou supérieur
- MySQL 5.7+ ou MariaDB 10.3+
- Composer
- Docker & Docker Compose (optionnel)

### Installation rapide avec Docker

```bash
# Cloner le projet
git clone https://github.com/votre-org/vinotheque-ci.git
cd vinotheque-ci

# Lancer avec Docker Compose
docker-compose up -d

# L'application sera accessible sur http://localhost:8080
```

### Installation manuelle

```bash
# Installer les dépendances PHP
composer install

# Configurer la base de données
cp config/database.example.php config/database.php
# Éditer config/database.php avec vos paramètres

# Importer la structure de base de données
mysql -u root -p vinotheque_ci < database/schema.sql

# Configurer le serveur web (Apache/Nginx)
# Pointer le DocumentRoot vers le dossier du projet
```

### Configuration

1. **Base de données** : Configurer `config/database.php`
2. **Pays supportés** : Modifier `models/CountryRegulation.php`
3. **Zones de livraison** : Configurer dans l'interface admin
4. **Méthodes de paiement** : Configurer les API de paiement

## 🌍 Fonctionnalités spécifiques à l'Afrique

### Réglementations par pays

Le système gère automatiquement les réglementations spécifiques à chaque pays :

```php
// Exemple de configuration pour la Côte d'Ivoire
'CI' => [
    'legal_age' => 18,
    'sale_hours' => ['start' => '08:00', 'end' => '22:00'],
    'restricted_days' => [],
    'currency' => 'XOF',
    'name' => 'Côte d\'Ivoire'
]
```

### Pays actuellement supportés

- 🇨🇮 **Côte d'Ivoire** (XOF) - 8h-22h, pas de restriction
- 🇸🇳 **Sénégal** (XOF) - 8h-20h, restriction vendredi
- 🇲🇦 **Maroc** (MAD) - 10h-18h, restriction vendredi
- 🇬🇭 **Ghana** (GHS) - 8h-21h, restriction dimanche
- 🇳🇬 **Nigeria** (NGN) - 9h-21h, restriction dimanche
- 🇰🇪 **Kenya** (KES) - 9h-23h, restriction dimanche
- 🇿🇦 **Afrique du Sud** (ZAR) - 8h-18h, restriction dimanche

### Optimisations mobiles

- **Détection de connexion lente** : Mode économie de données automatique
- **Images adaptatives** : Qualité réduite sur 2G/3G
- **Cache intelligent** : Stockage local des données critiques
- **Interface tactile** : Boutons et zones de touche optimisés
- **Gestes de navigation** : Balayage pour naviguer

## 🔒 Sécurité et conformité

### Vérification d'âge

- Vérification automatique à l'inscription
- Contrôle continu lors des achats
- Respect des âges légaux par pays
- Interface de confirmation explicite

### Protection des données

- Chiffrement des mots de passe (bcrypt)
- Validation et assainissement des entrées
- Protection CSRF
- Sessions sécurisées
- Conformité RGPD

### Restrictions de vente

- Vérification des horaires en temps réel
- Blocage automatique hors heures légales
- Gestion des jours fériés et restrictions religieuses
- Notifications utilisateur des prochaines ouvertures

## 📱 Progressive Web App (PWA)

### Fonctionnalités PWA

- **Installation sur mobile** : Ajout à l'écran d'accueil
- **Mode hors ligne** : Navigation basique sans connexion
- **Notifications push** : Alertes promotions et statut commandes
- **Mise à jour automatique** : Déploiement transparent
- **Expérience native** : Interface similaire à une app mobile

### Service Worker

Le Service Worker gère :
- Cache des ressources critiques
- Synchronisation en arrière-plan
- Notifications push
- Mise à jour automatique du contenu

## 💳 Méthodes de paiement

### Paiements mobiles africains

- **Orange Money** (Côte d'Ivoire, Sénégal, Mali...)
- **MTN Mobile Money** (Ghana, Côte d'Ivoire, Cameroun...)
- **Airtel Money** (Kenya, Nigeria, Tanzania...)
- **M-Pesa** (Kenya, Tanzania)
- **Moov Money** (Côte d'Ivoire, Bénin, Togo...)

### Paiements traditionnels

- Cartes bancaires (Visa, Mastercard)
- Virements bancaires
- Paiement à la livraison (selon zones)

## 🚚 Système de livraison

### Géolocalisation

- Détection automatique de la position
- Calcul des zones de livraison disponibles
- Estimation des délais de livraison
- Suivi en temps réel des commandes

### Zones de livraison

Configuration flexible par ville/région :
- Délais de livraison variables
- Frais de port adaptés
- Restrictions géographiques
- Partenaires logistiques locaux

## 🌐 Internationalisation

### Langues supportées

- **Français** (principal)
- **Anglais** (Ghana, Nigeria, Kenya...)
- **Langues locales** (à venir)

### Devises

Support automatique des devises locales :
- XOF (Franc CFA Ouest)
- XAF (Franc CFA Central)
- MAD (Dirham marocain)
- GHS (Cedi ghanéen)
- NGN (Naira nigérian)
- KES (Shilling kenyan)
- ZAR (Rand sud-africain)

## 📊 Analytics et monitoring

### Métriques suivies

- Temps de chargement par région
- Taux de conversion par pays
- Utilisation des méthodes de paiement
- Performance sur connexions lentes
- Erreurs et problèmes techniques

### Optimisations continues

- A/B testing des interfaces
- Optimisation des images par région
- Adaptation des fonctionnalités selon l'usage
- Amélioration de la performance mobile

## 🔧 API et intégrations

### API REST

```bash
# Exemples d'endpoints
GET /api/products                    # Liste des produits
GET /api/products/{id}              # Détail produit
POST /api/cart/add                  # Ajouter au panier
GET /api/delivery/zones             # Zones de livraison
POST /api/orders                    # Créer une commande
GET /api/user/profile               # Profil utilisateur
```

### Webhooks

- Notifications de paiement
- Statuts de livraison
- Mises à jour de stock
- Événements système

## 🧪 Tests et qualité

### Tests automatisés

```bash
# Tests unitaires
composer test

# Tests d'intégration
composer test:integration

# Tests de performance
composer test:performance
```

### Contrôle qualité

- Code standards PSR-12
- Analyse statique avec PHPStan
- Tests de sécurité avec Psalm
- Validation W3C du HTML
- Tests d'accessibilité

## 🚀 Déploiement

### Environnements

- **Développement** : Docker local
- **Test** : Serveur de staging
- **Production** : Serveurs cloud (AWS, Azure, OVH...)

### CI/CD

Pipeline automatisé :
1. Tests unitaires et d'intégration
2. Analyse de sécurité
3. Build des assets
4. Déploiement automatique
5. Tests de fumée post-déploiement

## 📈 Roadmap

### Version 2.0 (Q2 2024)

- [ ] Intelligence artificielle pour recommandations
- [ ] Réalité augmentée pour visualisation produits
- [ ] Blockchain pour traçabilité
- [ ] Intégration IoT pour gestion stock

### Version 2.1 (Q3 2024)

- [ ] Marketplace multi-vendeurs
- [ ] Programme de fidélité avancé
- [ ] Chat en temps réel
- [ ] Application mobile native

## 🤝 Contribution

### Guide de contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

### Standards de code

- Suivre PSR-12 pour PHP
- ESLint pour JavaScript
- Tests obligatoires pour nouvelles fonctionnalités
- Documentation mise à jour

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 👥 Équipe

- **Développement** : Équipe VINOTHEQUE-CI
- **Design UX/UI** : Spécialistes mobile-first
- **Conformité légale** : Experts réglementations africaines
- **Logistique** : Partenaires locaux

## 📞 Support

- **Email** : support@vinotheque.ci
- **WhatsApp** : +225 XX XX XX XX
- **Documentation** : https://docs.vinotheque.ci
- **Status** : https://status.vinotheque.ci

## 🙏 Remerciements

- Communauté PHP Afrique
- Partenaires logistiques locaux
- Beta testeurs à travers l'Afrique
- Contributeurs open source

---

**Développé avec ❤️ pour l'Afrique** 🌍

*L'abus d'alcool est dangereux pour la santé. À consommer avec modération.*