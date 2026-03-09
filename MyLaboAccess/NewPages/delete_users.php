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

// Récupération de l'ID utilisateur à supprimer
$id = $_GET['id'] ?? null;
if (!$id) die('ID manquant');

// Empêche l'administrateur de supprimer son propre compte
if ($id == $_SESSION['user']['id']) {
    die("Vous ne pouvez pas supprimer votre propre compte admin.");
}

// Suppression de l'utilisateur en base de données
$stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
$stmt->execute([$id]);

// Redirection vers la page admin
header('Location: admin.php');
exit();
?>