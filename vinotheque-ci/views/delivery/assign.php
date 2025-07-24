<?php include '../../views/includes/header.php'; ?>
<?php include '../../views/includes/admin_sidebar.php'; ?>

<main class="container">
    <h1 class="mb-4">Assigner un livreur</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Détails de la livraison</h5>
                </div>
                <div class="card-body">
                    <p><strong>N° Commande:</strong> <?= $delivery['order_number'] ?></p>
                    <p><strong>Client:</strong> <?= $delivery['customer_name'] ?></p>
                    <p><strong>Adresse:</strong> <?= $delivery['street'] ?>, <?= $delivery['city'] ?></p>
                    <p><strong>Montant:</strong> <?= number_format($delivery['total_amount'], 0, ',', ' ') ?> FCFA</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Assigner un livreur</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/deliveries/assign">
                        <input type="hidden" name="delivery_id" value="<?= $delivery['id'] ?>">
                        
                        <div class="mb-3">
                            <label for="delivery_person_id" class="form-label">Livreur</label>
                            <select class="form-select" id="delivery_person_id" name="delivery_person_id" required>
                                <option value="">Sélectionnez un livreur</option>
                                <?php foreach ($delivery_persons as $person): ?>
                                    <option value="<?= $person['id'] ?>">
                                        <?= $person['first_name'] ?> <?= $person['last_name'] ?> (<?= $person['phone'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Assigner</button>
                            <a href="/admin/deliveries" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../../views/includes/footer.php'; ?>