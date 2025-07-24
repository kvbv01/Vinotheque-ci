<?php require_once '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Finaliser la commande</h2>
    <form action="/cart/checkout" method="POST">
        <div class="row">
            <div class="col-md-6">
                <h4>Adresse de livraison</h4>
                <div class="form-group">
                    <label for="address">Adresse</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="city">Ville</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>
                <div class="form-group">
                    <label for="postal_code">Code postal</label>
                    <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Paiement</h4>
                <div class="form-group">
                    <label for="card_number">Numéro de carte</label>
                    <input type="text" class="form-control" id="card_number" name="card_number" required>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Date d'expiration</label>
                    <input type="text" class="form-control" id="expiry_date" name="expiry_date" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success mt-3">Payer <?= $total ?> €</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>