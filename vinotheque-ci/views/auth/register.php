<?php require_once 'views/includes/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0"><i class="fas fa-user-plus"></i> Inscription - VINOTHEQUE CI</h2>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Attention :</strong> La vente d'alcool est réglementée. Vous devez avoir l'âge légal dans votre pays.
                    </div>
                    
                    <form action="/register" method="POST" id="registerForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="first_name" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="last_name" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Téléphone *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" 
                                   placeholder="+225 XX XX XX XX" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="country_code" class="form-label">Pays *</label>
                                    <select class="form-control" id="country_code" name="country_code" required>
                                        <option value="">Sélectionnez votre pays</option>
                                        <?php if (isset($countries)): ?>
                                            <?php foreach ($countries as $code => $name): ?>
                                                <option value="<?= $code ?>" 
                                                        <?= (($_POST['country_code'] ?? '') === $code) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="birth_date" class="form-label">Date de naissance *</label>
                                    <input type="date" class="form-control" id="birth_date" name="birth_date" 
                                           value="<?= htmlspecialchars($_POST['birth_date'] ?? '') ?>" 
                                           max="<?= date('Y-m-d', strtotime('-18 years')) ?>" required>
                                    <small class="form-text text-muted">Vous devez avoir l'âge légal</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Mot de passe *</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   minlength="8" required>
                            <small class="form-text text-muted">Minimum 8 caractères</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                   minlength="8" required>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="age_confirmation" required>
                            <label class="form-check-label" for="age_confirmation">
                                Je confirme avoir l'âge légal pour acheter de l'alcool dans mon pays
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms_conditions" required>
                            <label class="form-check-label" for="terms_conditions">
                                J'accepte les <a href="/terms" target="_blank">conditions d'utilisation</a> 
                                et la <a href="/privacy" target="_blank">politique de confidentialité</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-user-plus"></i> Créer mon compte
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    <p class="text-center">
                        Déjà inscrit ? <a href="/login" class="btn btn-outline-primary">Se connecter</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
        return false;
    }
    
    // Vérification de l'âge basique côté client
    const birthDate = new Date(document.getElementById('birth_date').value);
    const today = new Date();
    const age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    if (age < 18) {
        e.preventDefault();
        alert('Vous devez avoir au moins 18 ans pour vous inscrire.');
        return false;
    }
});
</script>

<?php require_once 'views/includes/footer.php'; ?>