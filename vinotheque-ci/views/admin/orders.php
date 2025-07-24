<?php require_once '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Gestion des commandes</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
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
                    <td><?= $order['user_name'] ?></td>
                    <td><?= $order['order_date'] ?></td>
                    <td><?= $order['total'] ?> €</td>
                    <td><?= $order['status'] ?></td>
                    <td>
                        <a href="/admin/order/<?= $order['id'] ?>" class="btn btn-info btn-sm">Voir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>