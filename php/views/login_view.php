<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body class="login-page">
    <div class="login-container">
        <h2>Connexion</h2>

        <?php if (!empty($error)) : ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <label>Email :</label>
            <input type="email" name="email" required>

            <label>Mot de passe :</label>
            <input type="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>

        <div class="register-link">
            Pas encore de compte ? <a href="../controller/register.php">Inscrivez-vous ici</a>
        </div>
    </div>
</body>

</html>