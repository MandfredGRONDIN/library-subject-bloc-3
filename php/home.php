<?php
session_start();
require_once('config.php');

// Récupérer le nombre total de livres
$queryTotalBooks = "SELECT COUNT(*) as total_books FROM livres";
$stmtTotalBooks = $pdo->prepare($queryTotalBooks);
$stmtTotalBooks->execute();
$resultTotalBooks = $stmtTotalBooks->fetch(PDO::FETCH_ASSOC);

// Récupérer le nombre d'utilisateurs enregistrés
$queryTotalUsers = "SELECT COUNT(*) as total_users FROM users";
$stmtTotalUsers = $pdo->prepare($queryTotalUsers);
$stmtTotalUsers->execute();
$resultTotalUsers = $stmtTotalUsers->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <header>
        <h1>Librairie XYZ</h1>
    </header>

    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <ul>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <li>Bonjour <?= htmlspecialchars($_SESSION['firstname']); ?></li>
                    <li><a href="books.php">Voir la liste des livres</a></li>
                    <li><a href="./controller/profile.php">Mon profil</a></li>
                    <li><a href="./controller/logout.php">Déconnexion</a></li>
                <?php else : ?>
                    <li><a href="./controller/login.php">Connexion</a></li>
                    <li><a href="./controller/register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <div class="container">
                <h1>Dashboard</h1>
                <div class="container">
                    <div class="statistic">
                        <h3>Total des Livres</h3>
                        <p><?= $resultTotalBooks['total_books']; ?></p>
                    </div>

                    <div class="statistic">
                        <h3>Utilisateurs Enregistrés</h3>
                        <p><?= $resultTotalUsers['total_users']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?= date("Y"); ?> Librairie XYZ</p>
        </div>
    </footer>
</body>

</html>