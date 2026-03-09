<?php
// Connexion à la base de données
require_once __DIR__ . '/../database/db.php';

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    // Hashage sécurisé du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertion du nouvel utilisateur
    $stmt = $pdo->prepare(
        "INSERT INTO users (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([$nom, $prenom, $email, $hashedPassword, $role]);

    // Redirection vers la page de connexion
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="styles/register.css">
</head>

<body>
    <div class="register-box">
        <h2>Créer un compte</h2>

        <form method="POST">
            <label>Nom :</label>
            <input type="text" name="nom" required>

            <label>Prénom :</label>
            <input type="text" name="prenom" required>

            <label>Email :</label>
            <input type="email" name="email" required>

            <label>Mot de passe :</label>
            <input type="password" name="password" required>

            <label>Rôle :</label>
            <select name="role" required>
                <option value="user">Utilisateur</option>
                <option value="admin">Administrateur</option>
            </select>

            <button type="submit">Créer le compte</button>
        </form>

        <a href="login.php">Déjà un compte ? Connexion</a>
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