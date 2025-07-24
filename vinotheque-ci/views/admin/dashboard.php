<?php require_once '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Tableau de bord administrateur</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Produits</h5>
                    <p class="card-text"><?= $productCount ?> produits en stock</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Commandes</h5>
                    <p class="card-text"><?= $orderCount ?> commandes</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="card-text"><?= $userCount ?> utilisateurs</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>