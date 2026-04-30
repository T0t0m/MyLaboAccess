<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Admin-Token');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
  echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
  exit;
}

$identifier = trim($input['identifier'] ?? '');
$password = $input['password'] ?? '';

// Admin token from headers
$adminToken = $_SERVER['HTTP_ADMIN_TOKEN'] ?? null;

if (empty($identifier) || empty($password)) {
  echo json_encode(['success' => false, 'message' => 'Identifier and password required']);
  exit;
}

try {
  $dbHost = '127.0.0.1';
  $dbName = 'mylaboipi';
  $dbUser = 'root';
  $dbPass = '';

  $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

  // Find user
  $stmt = $pdo->prepare('SELECT id, email, nom, password_hash FROM users WHERE email = ? OR nom = ? LIMIT 1');
  $stmt->execute([$identifier, $identifier]);
  $user = $stmt->fetch();

  if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
    exit;
  }

  $isAdmin = false;
  $adminId = null;

  if ($adminToken) {
    $adm = $pdo->prepare('SELECT id FROM users WHERE api_token = ? AND role = "admin" LIMIT 1');
    $adm->execute([$adminToken]);
    $admin = $adm->fetch();

    if ($admin) {
      $isAdmin = true;
      $adminId = $admin['id'];
    }
  }

  if (!$isAdmin) {
    if (!password_verify($password, $user['password_hash'])) {
      echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect']);
      exit;
    }
  }

  $del = $pdo->prepare('DELETE FROM users WHERE id = ?');
  $del->execute([$user['id']]);

  if ($isAdmin) {
    $log = $pdo->prepare('INSERT INTO audit_logs (admin_id, action, target_user_id, created_at) VALUES (?, ?, ?, NOW())');
    $log->execute([$adminId, 'delete_user', $user['id']]);
  }

  echo json_encode(['success' => true, 'message' => 'Compte supprimé']);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
