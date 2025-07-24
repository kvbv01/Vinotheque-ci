<?php require_once '../../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Mon profil</h2>
    <form action="/user/profile" method="POST">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Nouveau mot de passe (laisser vide si inchangé)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>