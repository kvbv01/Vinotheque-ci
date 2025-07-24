<?php require_once '../../includes/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="/assets/images/products/<?= $product['image'] ?>" class="img-fluid" alt="<?= $product['name'] ?>">
        </div>
        <div class="col-md-6">
            <h2><?= $product['name'] ?></h2>
            <p class="text-muted"><?= $product['category'] ?></p>
            <p class="h4"><?= $product['price'] ?> €</p>
            <p><?= $product['description'] ?></p>
            <form action="/cart/add/<?= $product['id'] ?>" method="POST">
                <div class="form-group">
                    <label for="quantity">Quantité</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
                </div>
                <button type="submit" class="btn btn-success">Ajouter au panier</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>