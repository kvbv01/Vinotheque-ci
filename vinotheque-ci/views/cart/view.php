<?php require_once '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Votre panier</h2>
    <?php if (empty($cartItems)): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['price'] ?> €</td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= $item['price'] * $item['quantity'] ?> €</td>
                        <td>
                            <a href="/cart/remove/<?= $item['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td><strong><?= $total ?> €</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <a href="/cart/checkout" class="btn btn-success">Valider la commande</a>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>