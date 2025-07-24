<?php include '../../views/includes/header.php'; ?>
<?php include '../../views/includes/admin_sidebar.php'; ?>

<main class="container">
    <h1 class="mb-4">Gestion des Livraisons</h1>

    <div class="row">
        <!-- Livraisons en attente -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">En attente</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($pending_deliveries)): ?>
                        <div class="alert alert-info">Aucune livraison en attente</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($pending_deliveries as $delivery): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">Commande #<?= $delivery['order_number'] ?></h6>
                                        <small><?= date('d/m/Y H:i', strtotime($delivery['created_at'])) ?></small>
                                    </div>
                                    <p class="mb-1"><?= $delivery['street'] ?>, <?= $delivery['city'] ?></p>
                                    <small>Client: <?= $delivery['customer_name'] ?></small>
                                    <div class="mt-2">
                                        <a href="/deliveries/assign/<?= $delivery['id'] ?>" class="btn btn-sm btn-primary">
                                            Assigner un livreur
                                        </a>
                                        <a href="/deliveries/<?= $delivery['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                            Détails
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Livraisons assignées -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Assignées</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($assigned_deliveries)): ?>
                        <div class="alert alert-info">Aucune livraison assignée</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($assigned_deliveries as $delivery): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">Commande #<?= $delivery['order_number'] ?></h6>
                                        <small><?= date('d/m/Y H:i', strtotime($delivery['created_at'])) ?></small>
                                    </div>
                                    <p class="mb-1"><?= $delivery['street'] ?>, <?= $delivery['city'] ?></p>
                                    <small>Client: <?= $delivery['customer_name'] ?></small>
                                    <div class="mt-2">
                                        <a href="/deliveries/<?= $delivery['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            Détails
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Livraisons en cours -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">En cours</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($in_transit_deliveries)): ?>
                        <div class="alert alert-info">Aucune livraison en cours</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($in_transit_deliveries as $delivery): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">Commande #<?= $delivery['order_number'] ?></h6>
                                        <small><?= date('d/m/Y H:i', strtotime($delivery['created_at'])) ?></small>
                                    </div>
                                    <p class="mb-1"><?= $delivery['street'] ?>, <?= $delivery['city'] ?></p>
                                    <small>Client: <?= $delivery['customer_name'] ?></small>
                                    <div class="mt-2">
                                        <a href="/deliveries/<?= $delivery['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            Détails
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../../views/includes/footer.php'; ?>