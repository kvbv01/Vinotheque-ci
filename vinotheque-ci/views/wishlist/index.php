<?php include '../views/includes/header.php'; ?>

<main class="container py-5">
    <h1 class="mb-4">Ma liste de souhaits</h1>
    
    <?php if (empty($wishlist)): ?>
        <div class="alert alert-info">
            Votre liste de souhaits est vide. <a href="/products">Parcourir les produits</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($wishlist as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="/assets/images/products/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text"><?= number_format($product['price'], 0, ',', ' ') ?> FCFA</p>
                            <div class="d-flex justify-content-between">
                                <a href="/product/<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    Voir le produit
                                </a>
                                <button class="btn btn-sm btn-outline-danger toggle-wishlist" 
                                        data-product-id="<?= $product['id'] ?>"
                                        data-in-wishlist="true">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include '../views/includes/footer.php'; ?>