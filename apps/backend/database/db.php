<?php
// Définition du DSN (Data Source Name) pour la connexion à MySQL
// - host : serveur de base de données
// - dbname : nom de la base de données
// - charset : encodage UTF-8
$dsn = 'mysql:host=localhost;dbname=mylaboipi;charset=utf8mb4';

// Identifiants de connexion à la base de données
$user = "root";
$pass = "";

try {
    // Création de l'objet PDO pour établir la connexion
    $pdo = new PDO($dsn, $user, $pass);

    // Activation du mode exception pour gérer les erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Forcer l'encodage UTF-8 pour les échanges avec la base
    $pdo->exec("SET CHARACTER SET utf8");
} catch (PDOException $e) {
    // En cas d'erreur de connexion, arrêt du script avec un message
    die("Erreur connexion DB : " . $e->getMessage());
}
