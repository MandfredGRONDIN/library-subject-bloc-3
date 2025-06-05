<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>

<body class="profile-page">
    <div class="profile-container">
        <a href="../home.php" class="back-home">← Retour à l’accueil</a>
        <h2>Mon profil</h2>
        <?php if ($success): ?>
            <p class="message-success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <ul class="message-errors">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <label>Prénom :</label>
            <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>

            <label>Nom :</label>
            <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>

            <label>Email :</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>Nouveau mot de passe (laisser vide pour garder l'ancien) :</label>
            <input type="password" name="password">

            <label>Confirmer le nouveau mot de passe :</label>
            <input type="password" name="password_confirm">

            <button type="submit">Mettre à jour</button>
        </form>



    </div>
</body>

</html>