<?php
session_start();
require_once('../config.php');

$error = '';
$success = '';

// Générer un token CSRF si pas déjà présent
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Limitation des tentatives d'inscription
if (!isset($_SESSION['register_attempts'])) {
    $_SESSION['register_attempts'] = 0;
}
if (!isset($_SESSION['last_register_attempt_time'])) {
    $_SESSION['last_register_attempt_time'] = time();
}

$cooldownDuration = 60; // 1 minute de blocage après 5 essais

if ($_SESSION['register_attempts'] >= 5) {
    $elapsed = time() - $_SESSION['last_register_attempt_time'];
    if ($elapsed < $cooldownDuration) {
        $remaining = $cooldownDuration - $elapsed;
        $error = "Trop de tentatives d'inscription. Réessayez dans $remaining seconde(s).";
        include __DIR__ . '/../views/register_view.php';
        exit;
    } else {
        $_SESSION['register_attempts'] = 0;
        $_SESSION['last_register_attempt_time'] = time();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifier le token CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Requête invalide. Veuillez réessayer.";
    } else {
        $firstname = trim($_POST['firstname']);
        $lastname  = trim($_POST['lastname']);
        $email     = trim($_POST['email']);
        $mdp       = $_POST['password'];
        $role      = 'utilisateur';

        if (strlen($firstname) < 2 || strlen($lastname) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($mdp) < 6) {
            $error = "Données invalides. Assurez-vous de remplir correctement tous les champs.";
        } else {
            // Vérifier si l'utilisateur existe déjà
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $error = "Un compte existe déjà avec cet e-mail.";
            } else {
                $hashedPassword = password_hash($mdp, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare("
                    INSERT INTO users (firstname, lastname, email, password, role)
                    VALUES (?, ?, ?, ?, ?)
                ");

                try {
                    $stmt->execute([$firstname, $lastname, $email, $hashedPassword, $role]);
                    $success = "Inscription réussie. <a href='../controller/login.php'>Connectez-vous ici</a>";
                    // Reset tentative après succès
                    $_SESSION['register_attempts'] = 0;
                    // Nouveau token CSRF après succès
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                } catch (PDOException $e) {
                    $error = "Erreur lors de l'inscription : " . $e->getMessage();
                }
            }
        }

        if ($error) {
            $_SESSION['register_attempts']++;
            $_SESSION['last_register_attempt_time'] = time();
        }
    }
}

include __DIR__ . '/../views/register_view.php';
