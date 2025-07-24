<?php include '../../views/includes/header.php'; ?>
<?php include '../../views/includes/admin_sidebar.php'; ?>

<main class="container">
    <h1 class="mb-4">Créer une nouvelle promotion</h1>

    <form method="POST" action="/admin/promotions/store">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom de la promotion</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="discount_type" class="form-label">Type de réduction</label>
                            <select class="form-select" id="discount_type" name="discount_type" required>
                                <option value="percentage">Pourcentage</option>
                                <option value="fixed">Montant fixe</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="discount_value" class="form-label">Valeur</label>
                            <input type="number" class="form-control" id="discount_value" name="discount_value" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Date de début</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Date de fin</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active">Promotion active</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Produits concernés</label>
                    
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="product-search" placeholder="Rechercher un produit...">
                        <button class="btn btn-outline-secondary" type="button" id="clear-search">Effacer</button>
                    </div>
                    
                    <div style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm">
                            <tbody id="product-list">
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input product-checkbox" type="checkbox" 
                                                       name="products[]" value="<?= $product['id'] ?>" 
                                                       id="product-<?= $product['id'] ?>">
                                                <label class="form-check-label" for="product-<?= $product['id'] ?>">
                                                    <?= htmlspecialchars($product['name']) ?> - 
                                                    <?= number_format($product['price'], 0, ',', ' ') ?> FCFA
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-primary">Créer la promotion</button>
            <a href="/admin/promotions" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSearch = document.getElementById('product-search');
    const productList = document.getElementById('product-list');
    const clearSearch = document.getElementById('clear-search');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    
    productSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = productList.querySelectorAll('tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
    
    clearSearch.addEventListener('click', function() {
        productSearch.value = '';
        const rows = productList.querySelectorAll('tr');
        rows.forEach(row => row.style.display = '');
    });
});
</script>

<?php include '../../views/includes/footer.php'; ?>