<?php include '../../views/includes/header.php'; ?>

<?php if ($_SESSION['user']['role'] === 'admin'): ?>
    <?php include '../../views/includes/admin_sidebar.php'; ?>
<?php endif; ?>

<main class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Livraison #<?= $delivery['id'] ?></h1>
        <span class="badge 
            <?= $delivery['status'] === 'pending' ? 'bg-warning text-dark' : 
               ($delivery['status'] === 'assigned' ? 'bg-info' : 
               ($delivery['status'] === 'in_transit' ? 'bg-primary' : 'bg-success')) ?>">
            <?= $delivery['status'] === 'pending' ? 'En attente' : 
               ($delivery['status'] === 'assigned' ? 'Assignée' : 
               ($delivery['status'] === 'in_transit' ? 'En cours' : 'Livrée')) ?>
        </span>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations de la commande</h5>
                </div>
                <div class="card-body">
                    <p><strong>N° Commande:</strong> <?= $delivery['order_number'] ?></p>
                    <p><strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($delivery['created_at'])) ?></p>
                    <p><strong>Montant total:</strong> <?= number_format($delivery['total_amount'], 0, ',', ' ') ?> FCFA</p>
                    <p><strong>Méthode de paiement:</strong> 
                        <?= $delivery['payment_method'] === 'cash_on_delivery' ? 'Paiement à la livraison' : 
                           ($delivery['payment_method'] === 'mobile_money' ? 'Mobile Money' : 'Carte de crédit') ?>
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Articles</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($delivery['items'] as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0"><?= $item['name'] ?></h6>
                                    <small><?= $item['quantity'] ?> x <?= number_format($item['unit_price'], 0, ',', ' ') ?> FCFA</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    <?= number_format($item['total_price'], 0, ',', ' ') ?> FCFA
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Adresse de livraison</h5>
                </div>
                <div class="card-body">
                    <p><strong>Client:</strong> <?= $delivery['customer_name'] ?></p>
                    <p><strong>Téléphone:</strong> <?= $delivery['customer_phone'] ?></p>
                    <p><strong>Adresse:</strong></p>
                    <p>
                        <?= $delivery['street'] ?><br>
                        <?= $delivery['city'] ?>, <?= $delivery['postal_code'] ?><br>
                        <?= $delivery['country'] ?>
                    </p>
                </div>
            </div>

            <?php if ($delivery['delivery_person_name']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Livreur</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nom:</strong> <?= $delivery['delivery_person_name'] ?></p>
                        <p><strong>Téléphone:</strong> <?= $delivery['delivery_person_phone'] ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($_SESSION['user']['role'] === 'delivery' && in_array($delivery['status'], ['assigned', 'in_transit'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Mettre à jour le statut</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/deliveries/update-status">
                            <input type="hidden" name="delivery_id" value="<?= $delivery['id'] ?>">
                            
                            <?php if ($delivery['status'] === 'assigned'): ?>
                                <input type="hidden" name="status" value="in_transit">
                                <button type="submit" class="btn btn-primary w-100">
                                    Commencer la livraison
                                </button>
                            <?php elseif ($delivery['status'] === 'in_transit'): ?>
                                <input type="hidden" name="status" value="delivered">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes (optionnel)</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    Confirmer la livraison
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../../views/includes/footer.php'; ?>