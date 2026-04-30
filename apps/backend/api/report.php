<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../database/db.php';

// Lecture JSON
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
  echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
  exit;
}

// Récupération du token dans les headers
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
  echo json_encode(['success' => false, 'message' => 'Token manquant']);
  exit;
}

$token = $matches[1];

$equipment_name = trim($input['equipment_name'] ?? '');
$quantity = (int)($input['quantity'] ?? 1);
$description = trim($input['description'] ?? '');

try {
  // Vérification du token en base
  $stmt = $pdo->prepare("SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > NOW() LIMIT 1");
  $stmt->execute([$token]);
  $user = $stmt->fetch();

  if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Token invalide ou expiré']);
    exit;
  }

  $userId = $user['user_id'];

  $stmt = $pdo->prepare('INSERT INTO reports (user_id, equipment_name, quantity, description) VALUES (?, ?, ?, ?)');
  $stmt->execute([$userId, $equipment_name, $quantity, $description]);

  echo json_encode(['success' => true, 'message' => 'Signalement enregistré']);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}
