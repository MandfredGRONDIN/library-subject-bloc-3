<?php
session_start();
require_once('../config.php');

$error = '';

// Générer un token CSRF si pas déjà présent
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Initialiser ou incrémenter le compteur de tentatives
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['last_attempt_time'])) {
    $_SESSION['last_attempt_time'] = time();
}

// Durée de blocage en secondes après 5 tentatives
$cooldownDuration = 60; // 1 minute

// Vérifier si le cooldown est actif
if ($_SESSION['login_attempts'] >= 5) {
    $elapsed = time() - $_SESSION['last_attempt_time'];
    if ($elapsed < $cooldownDuration) {
        $remaining = $cooldownDuration - $elapsed;
        $error = "Trop de tentatives. Réessayez dans $remaining seconde(s).";
        include __DIR__ . '/../views/login_view.php';
        exit;
    } else {
        // Cooldown terminé, réinitialiser le compteur
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = time();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } else {
        $stmt = $pdo->prepare("SELECT id, firstname, lastname, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Empêche session fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];

            $_SESSION['login_attempts'] = 0;

            header("Location: ../home.php");
            exit;
        } else {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            $error = "Email ou mot de passe incorrect.";
        }
    }
}

include __DIR__ . '/../views/login_view.php';
