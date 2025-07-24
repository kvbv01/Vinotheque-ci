<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/">Vinothèque-CI</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Accueil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/home/about">À propos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/home/contact">Contact</a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/user/profile">Mon compte</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/auth/logout">Déconnexion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/cart/view">
                        Panier (<?= $_SESSION['cart_count'] ?? 0 ?>)
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/auth/login">Connexion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/auth/register">Inscription</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>