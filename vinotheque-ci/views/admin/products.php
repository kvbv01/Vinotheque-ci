<?php require_once '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Gestion des produits</h2>
    <a href="/admin/products/add" class="btn btn-success mb-3">Ajouter un produit</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= $product['name'] ?></td>
                    <td><?= $product['price'] ?> €</td>
                    <td><?= $product['stock'] ?></td>
                    <td>
                        <a href="/admin/products/edit/<?= $product['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                        <a href="/admin/products/delete/<?= $product['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>