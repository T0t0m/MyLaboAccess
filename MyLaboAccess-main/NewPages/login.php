<?php
// Démarrage de la session
session_start();

// Connexion à la base de données
require_once __DIR__ . '/../database/db.php';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Recherche de l'utilisateur par email
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        // Redirection selon le rôle
        if ($user['role'] === 'admin') {
            header('Location: admin.php');
            exit();
        }
        header('Location: dashboard.php');
        exit();
    } else {
        // Message d'erreur en cas d'échec
        $error = 'Email ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles/login.css">
</head>

<body>
    <div class="login-box">
        <h2>Connexion</h2>

        <?php if (!empty($error)) echo '<p class="error">' . $error . '</p>'; ?>

        <form method="POST">
            <label>Email :</label>
            <input type="email" name="email" required>

            <label>Mot de passe :</label>
            <input type="password" name="password" required>

            <button type="submit">Connexion</button>
        </form>

        <a href="register.php">Pas encore de compte ? S'inscrire</a>
    </div>
</body>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Inter, Arial, sans-serif;
        background: #F5F1F4;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-box {
        width: 100%;
        max-width: 380px;
        padding: 30px;
        background: #F0ECEF;
        border-radius: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        font-size: 15px;
        font-weight: 600;
    }

    input {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 12px;
        margin-top: 6px;
        margin-bottom: 15px;
        background: white;
        box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.08);
    }

    button {
        width: 100%;
        padding: 14px;
        background: #D10000;
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 17px;
        cursor: pointer;
        transition: 0.2s;
    }

    button:hover {
        background: #B80000;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 12px;
        text-decoration: none;
        font-weight: bold;
        color: #D10000;
    }

    .error {
        background: #FFD4D4;
        color: #B80000;
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 12px;
        text-align: center;
        font-weight: 700;
    }
</style>

</html>