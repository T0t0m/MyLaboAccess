<?php
+$host = '127.0.0.1';
+$db   = 'mylaboipi';
+$user = 'root';
+$pass = '';

try {
    // Création de l'objet PDO pour établir la connexion
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    

    // Forcer l'encodage UTF-8 pour les échanges avec la base
    $pdo->exec("SET CHARACTER SET utf8");
} catch (PDOException $e) {
    // En cas d'erreur de connexion, arrêt du script avec un message
    die("Erreur connexion DB : " . $e->getMessage());
}
