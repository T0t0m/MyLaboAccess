<?php
// Démarrage de la session
session_start();

// Connexion à la base de données
require_once __DIR__ . '/../database/db.php';

// Vérification des droits administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Récupération de l'ID utilisateur
$id = $_GET['id'] ?? null;
if (!$id) die('ID manquant.');

// Récupération des données de l'utilisateur
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) die('Utilisateur introuvable.');

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Mise à jour des informations en base
    $update = $pdo->prepare('UPDATE users SET nom=?, prenom=?, email=?, role=? WHERE id=?');
    $update->execute([$nom, $prenom, $email, $role, $id]);

    // Redirection vers l'espace admin
    header('Location: admin.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Modifier utilisateur</title>
    <link rel="stylesheet" href="styles/edit_users.css">
</head>

<body>
    <div class="edit-box">
        <h2>Modifier l'utilisateur</h2>

        <form method="POST">
            <label>Nom :</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>

            <label>Prénom :</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>

            <label>Email :</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>Rôle :</label>
            <select name="role" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
            </select>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
</body>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Inter, Arial, sans-serif;
        background: #F5F1F4;
    }

    .register-box {
        width: 100%;
        max-width: 420px;
        margin: 60px auto;
        background: #F0ECEF;
        padding: 30px;
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

    input,
    select {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 12px;
        margin-top: 6px;
        margin-bottom: 15px;
        background: white;
        box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.08);
        font-size: 15px;
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
        margin-top: 15px;
        text-decoration: none;
        font-weight: bold;
        color: #D10000;
    }
</style>

</html>