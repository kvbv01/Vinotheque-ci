<?php require_once '../../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Mes commandes</h2>
    <?php if (empty($orders)): ?>
        <p>Vous n'avez pas encore passé de commande.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['order_date'] ?></td>
                        <td><?= $order['total'] ?> €</td>
                        <td><?= $order['status'] ?></td>
                        <td>
                            <a href="/user/orders/<?= $order['id'] ?>" class="btn btn-info btn-sm">Détails</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>