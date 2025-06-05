<?php
function createUsersTableIfNotExists(PDO $pdo)
{
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(100) NOT NULL,
        lastname VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        registration DATETIME DEFAULT CURRENT_TIMESTAMP,
        role ENUM('admin', 'utilisateur') DEFAULT 'utilisateur'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    try {
        // Création de la table
        $pdo->exec($sql);

        // Vérifier si l'admin existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute(['admin@admin.fr']);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            // Créer un mot de passe sécurisé par défaut
            $defaultPassword = 'admin123';
            $hashedPassword = password_hash($defaultPassword, PASSWORD_BCRYPT);

            // Insérer l'admin
            $stmtInsert = $pdo->prepare("
                INSERT INTO users (firstname, lastname, email, password, role)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmtInsert->execute(['Admin', 'Admin', 'admin@admin.fr', $hashedPassword, 'admin']);
        }
    } catch (PDOException $e) {
        error_log("Erreur lors de la création de la table users ou insertion admin : " . $e->getMessage());
    }
}
