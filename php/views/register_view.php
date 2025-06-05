<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/register.css">
</head>

<body class="register-page">
    <div class="register-container">
        <h2>Créer un compte</h2>

        <?php if (!empty($error)) : ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php elseif (!empty($success)) : ?>
            <p class="message success"><?= $success ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <label>Prénom :</label>
            <input type="text" name="firstname" required value="<?= isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : '' ?>">

            <label>Nom :</label>
            <input type="text" name="lastname" required value="<?= isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '' ?>">

            <label>Email :</label>
            <input type="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">

            <label>Mot de passe :</label>
            <input type="password" name="password" required>

            <button type="submit">S'inscrire</button>
        </form>

        <div class="login-link">
            Déjà un compte ? <a href="./login.php">Connectez-vous ici</a>
        </div>
    </div>
</body>

</html>