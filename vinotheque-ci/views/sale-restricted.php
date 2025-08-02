<?php 
require_once 'views/includes/header.php';
require_once 'middleware/SaleTimeMiddleware.php';

$middleware = new SaleTimeMiddleware();
$restriction = $_SESSION['sale_restriction'] ?? null;
unset($_SESSION['sale_restriction']);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h2 class="mb-0">
                        <i class="fas fa-clock"></i> Ventes temporairement fermées
                    </h2>
                </div>
                <div class="card-body text-center">
                    <?php if ($restriction): ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <h4>Désolé, les ventes sont actuellement fermées</h4>
                            <p class="lead">
                                <?= $middleware->getSaleRestrictionMessage(
                                    $restriction['country_code'], 
                                    $restriction['next_window']
                                ) ?>
                            </p>
                        </div>
                        
                        <?php if (isset($restriction['next_window']['next_available'])): ?>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5><i class="fas fa-calendar-alt"></i> Prochaine ouverture</h5>
                                    <p class="h4 text-primary">
                                        <?= $restriction['next_window']['next_available']->format('l d F Y à H:i') ?>
                                    </p>
                                    <div id="countdown" class="h5 text-success"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <h5><i class="fas fa-info-circle"></i> Pourquoi ces restrictions ?</h5>
                            <p class="text-muted">
                                Les horaires de vente d'alcool sont réglementés par les autorités locales 
                                pour promouvoir une consommation responsable. Nous respectons ces réglementations 
                                dans tous les pays où nous opérons.
                            </p>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6><i class="fas fa-shopping-cart"></i> En attendant</h6>
                                        <p>Vous pouvez parcourir notre catalogue et ajouter des produits à votre liste de souhaits.</p>
                                        <a href="/products" class="btn btn-outline-primary">Parcourir le catalogue</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6><i class="fas fa-bell"></i> Notifications</h6>
                                        <p>Recevez une notification dès que les ventes reprennent.</p>
                                        <button class="btn btn-outline-success" onclick="enableNotifications()">
                                            Activer les notifications
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <h4>Accès restreint</h4>
                            <p>Les ventes peuvent être temporairement fermées selon votre localisation.</p>
                            <a href="/" class="btn btn-primary">Retour à l'accueil</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
<?php if (isset($restriction['next_window']['next_available'])): ?>
// Compte à rebours jusqu'à la prochaine ouverture
const nextOpening = new Date('<?= $restriction['next_window']['next_available']->format('Y-m-d H:i:s') ?>');

function updateCountdown() {
    const now = new Date();
    const diff = nextOpening - now;
    
    if (diff <= 0) {
        document.getElementById('countdown').innerHTML = 'Les ventes sont maintenant ouvertes ! <a href="/" class="btn btn-success">Actualiser</a>';
        return;
    }
    
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    let countdown = '';
    if (days > 0) countdown += days + 'd ';
    countdown += hours.toString().padStart(2, '0') + 'h ';
    countdown += minutes.toString().padStart(2, '0') + 'm ';
    countdown += seconds.toString().padStart(2, '0') + 's';
    
    document.getElementById('countdown').innerHTML = 'Ouverture dans : ' + countdown;
}

setInterval(updateCountdown, 1000);
updateCountdown();
<?php endif; ?>

// Notifications
function enableNotifications() {
    if ('Notification' in window) {
        Notification.requestPermission().then(function(permission) {
            if (permission === 'granted') {
                // Stocker la préférence de notification
                localStorage.setItem('notifications_enabled', 'true');
                <?php if (isset($restriction['next_window']['next_available'])): ?>
                // Programmer une notification
                const timeUntilOpening = nextOpening - new Date();
                if (timeUntilOpening > 0 && timeUntilOpening <= 24 * 60 * 60 * 1000) { // Dans les 24h
                    setTimeout(function() {
                        new Notification('VINOTHEQUE CI', {
                            body: 'Les ventes sont maintenant ouvertes !',
                            icon: '/assets/images/logo.png'
                        });
                    }, timeUntilOpening);
                }
                <?php endif; ?>
                alert('Notifications activées ! Vous serez averti dès que les ventes reprennent.');
            }
        });
    } else {
        alert('Les notifications ne sont pas supportées par votre navigateur.');
    }
}
</script>

<?php require_once 'views/includes/footer.php'; ?>