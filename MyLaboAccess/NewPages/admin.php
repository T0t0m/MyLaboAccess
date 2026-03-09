<?php
// Démarrage de la session pour accéder aux informations utilisateur
session_start();

// Requête SQL (non utilisée ici mais déclarée)
$sql = "SELECT * FROM users";

// Vérification de l'authentification et du rôle administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Redirection vers la page de connexion si accès non autorisé
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
</head>

<body>
    <!-- Message de bienvenue -->
    <h2>Bienvenue dans l'espace Admin</h1>
    <h2>Bonjour, <?php echo htmlspecialchars($_SESSION['user']['prenom']); ?> !</h1>

    <h3>Liste des utilisateurs</h3>

    <?php
    // Inclusion du fichier de connexion à la base de données
    require_once __DIR__ . '/../database/db.php';

    // Récupération des informations des utilisateurs
    $stmt = $pdo->query('SELECT id, nom, prenom, email, role FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Tableau d'affichage des utilisateurs -->
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin-bottom:20px;">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>

        <!-- Boucle d'affichage de chaque utilisateur -->
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['nom']); ?></td>
                <td><?php echo htmlspecialchars($u['prenom']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
                <td>
                    <!-- Liens pour modifier ou supprimer un utilisateur -->
                    <a href="edit_users.php?id=<?php echo $u['id']; ?>">Éditer</a> |
                    <a href="delete_users.php?id=<?php echo $u['id']; ?>" style="color:red;">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Lien de déconnexion -->
    <a href="logout.php">Se déconnecter</a>
</body>
</html>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Inter', Arial, sans-serif;
        background-color: #F5F1F4;
        color: #333;
    }

    .container {
        width: 100%;
        max-width: 380px;
        margin: 60px auto;
        padding: 30px;
        background: #F0ECEF;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    h2 {
        text-align: center;
        color: #444;
        margin-bottom: 25px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-size: 15px;
        font-weight: 600;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px;
        border: none;
        background: white;
        border-radius: 12px;
        margin-bottom: 15px;
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
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.2s;
    }

    button:hover {
        background: #B80000;
    }

    a {
        color: #D10000;
        text-decoration: none;
        font-weight: 600;
    }

    a:hover {
        text-decoration: underline;
    }

    .error {
        background: #FFD4D4;
        color: #B80000;
        padding: 10px;
        border-radius: 12px;
        margin-bottom: 15px;
        text-align: center;
        font-weight: bold;
    }
</style>

</html>