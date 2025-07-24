<?php require_once '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Inscription</h2>
            <form action="/auth/register" method="POST">
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>
            <p class="mt-3">Déjà inscrit ? <a href="/auth/login">Se connecter</a></p>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>