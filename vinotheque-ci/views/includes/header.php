<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#d4af37">
    <meta name="description" content="VINOTHEQUE CI - Vente en ligne de vins et spiritueux avec livraison en Afrique. Respect des réglementations locales.">
    <meta name="keywords" content="vin, spiritueux, livraison, Afrique, Côte d'Ivoire, alcool, vente en ligne">
    
    <!-- Open Graph pour les réseaux sociaux -->
    <meta property="og:title" content="VINOTHEQUE CI - <?= $pageTitle ?? 'Vins et Spiritueux en Ligne' ?>">
    <meta property="og:description" content="Découvrez notre sélection de vins et spiritueux avec livraison dans toute l'Afrique">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:image" content="/assets/images/og-image.jpg">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="VINOTHEQUE CI">
    <link rel="apple-touch-icon" href="/assets/images/icon-192.png">
    
    <title>VINOTHEQUE CI - <?= $pageTitle ?? 'Vins et Spiritueux Premium' ?></title>
    
    <!-- Préchargement des ressources critiques -->
    <link rel="preload" href="/assets/css/mobile-africa.css" as="style">
    <link rel="preload" href="/assets/js/mobile-optimizations.js" as="script">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/mobile-africa.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Fallback pour Bootstrap si nécessaire -->
    <noscript>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </noscript>
    
    <!-- Scripts critiques -->
    <script>
        // Détection des capacités du navigateur
        window.browserCapabilities = {
            serviceWorker: 'serviceWorker' in navigator,
            geolocation: 'geolocation' in navigator,
            notification: 'Notification' in window,
            connection: 'connection' in navigator,
            storage: typeof(Storage) !== "undefined"
        };
        
        // Variables globales pour l'application
        window.appConfig = {
            baseUrl: '<?= $_SERVER['HTTP_HOST'] ?>',
            currentPage: '<?= $_SERVER['REQUEST_URI'] ?>',
            userCountry: '<?= $_SESSION['country_code'] ?? 'CI' ?>',
            isLoggedIn: <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>,
            saleAllowed: true // Sera mis à jour par le JavaScript
        };
    </script>
</head>
<body class="fade-in">
    <!-- Indicateur de réseau -->
    <div class="network-indicator" style="display: none;"></div>
    
    <!-- Navigation -->
    <?php require_once 'navbar.php'; ?>
    
    <!-- Contenu principal -->
    <main class="main-content">