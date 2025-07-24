<?php require_once '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Connexion</h2>
            <form action="/auth/login" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
            <p class="mt-3">Pas encore de compte ? <a href="/auth/register">S'inscrire</a></p>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>