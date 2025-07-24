<?php include '../../views/includes/header.php'; ?>
<?php include '../../views/includes/admin_sidebar.php'; ?>

<main class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des Promotions</h1>
        <a href="/admin/promotions/create" class="btn btn-primary">
            <i class="bi bi-plus"></i> Créer une promotion
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Valeur</th>
                    <th>Dates</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($promotions as $promotion): ?>
                    <tr>
                        <td><?= htmlspecialchars($promotion['name']) ?></td>
                        <td>
                            <?= $promotion['discount_type'] === 'percentage' ? 'Pourcentage' : 'Montant fixe' ?>
                        </td>
                        <td>
                            <?= $promotion['discount_type'] === 'percentage' ? 
                                $promotion['discount_value'] . '%' : 
                                number_format($promotion['discount_value'], 0, ',', ' ') . ' FCFA' ?>
                        </td>
                        <td>
                            <?= date('d/m/Y', strtotime($promotion['start_date'])) ?> - 
                            <?= date('d/m/Y', strtotime($promotion['end_date'])) ?>
                        </td>
                        <td>
                            <span class="badge <?= $promotion['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $promotion['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <a href="/admin/promotions/edit/<?= $promotion['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../../views/includes/footer.php'; ?>