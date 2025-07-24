<?php require_once '../../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Résultats pour "<?= $query ?>"</h2>
    <?php if (empty($products)): ?>
        <p>Aucun résultat trouvé.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="/assets/images/products/<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $product['name'] ?></h5>
                            <p class="card-text"><?= $product['price'] ?> €</p>
                            <a href="/products/detail/<?= $product['id'] ?>" class="btn btn-primary">Voir</a>
                            <a href="/cart/add/<?= $product['id'] ?>" class="btn btn-success">Ajouter au panier</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>