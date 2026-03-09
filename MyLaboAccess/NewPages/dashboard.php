<?php
// Connexion à la base de données
require_once '../database/db.php';

// Requête SQL pour compter les appareils par type
$sql = "
    SELECT type_appareil, COUNT(*) AS total
    FROM appareils
    GROUP BY type_appareil
";

// Préparation et exécution de la requête
$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialisation des compteurs pour chaque type d'appareil
$counts = [
    'ecran'   => 0,
    'routeur' => 0,
    'switch'  => 0,
    'serveur' => 0,
    'cable'   => 0,
    'wifi'    => 0
];

// Remplissage des compteurs à partir des résultats SQL
foreach ($rows as $row) {
    $counts[$row['type_appareil']] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard – Parc Informatique</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .badge {
            background: #d40000;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>📊 Parc informatique</h1>

        <div class="card"><span>🖥️ Écrans</span><span class="badge"><?= $counts['ecran'] ?></span></div>
        <div class="card"><span>🌐 Routeurs</span><span class="badge"><?= $counts['routeur'] ?></span></div>
        <div class="card"><span>🔀 Switches</span><span class="badge"><?= $counts['switch'] ?></span></div>
        <div class="card"><span>🖥️ Serveurs</span><span class="badge"><?= $counts['serveur'] ?></span></div>
        <div class="card"><span>🔌 Câbles réseau</span><span class="badge"><?= $counts['cable'] ?></span></div>
        <div class="card"><span>📡 Points d’accès WiFi</span><span class="badge"><?= $counts['wifi'] ?></span></div>
    </div>
    <a href="logout.php" style="display:inline-block; margin-bottom:20px; padding:8px 15px; background:#d40000; color:white; border-radius:6px; text-decoration:none;
">Se déconnecter</a>

</body>

</html>