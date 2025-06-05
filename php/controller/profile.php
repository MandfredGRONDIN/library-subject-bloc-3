<?php
require_once('../config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../controller/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = '';

// Générer un token CSRF si inexistant
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Récupérer les infos utilisateur actuelles
$stmt = $pdo->prepare("SELECT firstname, lastname, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du token CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = "Requête invalide. Veuillez réessayer.";
    } else {
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        if (strlen($firstname) < 2) {
            $errors[] = "Le prénom doit contenir au moins 2 caractères.";
        }
        if (strlen($lastname) < 2) {
            $errors[] = "Le nom doit contenir au moins 2 caractères.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide.";
        }

        // Vérifier si l'email est déjà pris par un autre utilisateur
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $errors[] = "Cet email est déjà utilisé.";
        }

        if (!empty($password) || !empty($password_confirm)) {
            if ($password !== $password_confirm) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            } elseif (strlen($password) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
            }
        }

        if (empty($errors)) {
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, password = ? WHERE id = ?");
                $stmt->execute([$firstname, $lastname, $email, $hashedPassword, $user_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
                $stmt->execute([$firstname, $lastname, $email, $user_id]);
            }

            $success = "Profil mis à jour avec succès.";

            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['email'] = $email;

            $user['firstname'] = $firstname;
            $user['lastname'] = $lastname;
            $user['email'] = $email;

            // Nouveau token CSRF après succès
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}

include __DIR__ . '/../views/profile_view.php';
