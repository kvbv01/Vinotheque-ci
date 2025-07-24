<?php include '../../views/includes/header.php'; ?>

<main class="container">
    <h1 class="mb-4">Mes Livraisons</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Livraisons à effectuer</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($deliveries)): ?>
                        <div class="alert alert-info">Vous n'avez aucune livraison à effectuer pour le moment</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Adresse</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($deliveries as $delivery): ?>
                                        <tr>
                                            <td><?= $delivery['order_number'] ?></td>
                                            <td>
                                                <?= $delivery['street'] ?><br>
                                                <?= $delivery['city'] ?>, <?= $delivery['postal_code'] ?>
                                            </td>
                                            <td><?= number_format($delivery['total_amount'], 0, ',', ' ') ?> FCFA</td>
                                            <td>
                                                <span class="badge 
                                                    <?= $delivery['status'] === 'assigned' ? 'bg-info' : 
                                                       ($delivery['status'] === 'in_transit' ? 'bg-primary' : 'bg-success') ?>">
                                                    <?= $delivery['status'] === 'assigned' ? 'Assignée' : 
                                                       ($delivery['status'] === 'in_transit' ? 'En cours' : 'Livrée') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/deliveries/<?= $delivery['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    Détails
                                                </a>
                                                <?php if ($delivery['status'] === 'assigned'): ?>
                                                    <form method="POST" action="/deliveries/update-status" class="d-inline">
                                                        <input type="hidden" name="delivery_id" value="<?= $delivery['id'] ?>">
                                                        <input type="hidden" name="status" value="in_transit">
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            Commencer
                                                        </button>
                                                    </form>
                                                <?php elseif ($delivery['status'] === 'in_transit'): ?>
                                                    <form method="POST" action="/deliveries/update-status" class="d-inline">
                                                        <input type="hidden" name="delivery_id" value="<?= $delivery['id'] ?>">
                                                        <input type="hidden" name="status" value="delivered">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            Terminer
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../../views/includes/footer.php'; ?>