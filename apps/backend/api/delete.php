<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Attention : CORS ouvert à tous
header('Access-Control-Allow-Headers: Content-Type, Admin-Token');

// 1. Vérification du token d'administration
$headers = getallheaders();
$adminToken = $headers['Admin-Token'] ?? $headers['admin-token'] ?? '';
$secretAdminKey = "admintoken?";
$isAdmin = ($adminToken !== '' && $adminToken === $secretAdminKey);

// 2. Lecture et validation des données JSON (identifiant et mot de passe)
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
  echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
  exit;
}

$identifier = trim($input['identifier'] ?? ''); // email or nom
$password = $input['password'] ?? '';

// Le mot de passe est exigé uniquement si ce n'est pas un admin
if (empty($identifier) || empty($password) && !$isAdmin) {
  echo json_encode(['success' => false, 'message' => 'Identifier and password required']);
  exit;
}

try {
  // 3. Connexion BDD
  // TODO : Utiliser db.php à la place
  $dbHost = '127.0.0.1';
  $dbName = 'mylaboipi';
  $dbUser = 'root';
  $dbPass = '';

  $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

  // 4. Recherche de l'utilisateur
  $stmt = $pdo->prepare('SELECT id, email, nom, password_hash FROM users WHERE email = ? OR nom = ? LIMIT 1');
  $stmt->execute([$identifier, $identifier]);
  $user = $stmt->fetch();

  if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
    exit;
  }

  // 5. Vérification du mot de passe (ignorée pour l'admin)
  if (!$isAdmin) {
    if (!password_verify($password, $user['password_hash'])) {
      echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect']);
      exit;
    }
  }

  // 6. Enregistrement de l'action (log) si c'est un admin
  if ($isAdmin) {
    // TODO : Récupérer et insérer l'ID réel de l'administrateur au lieu d'un token en dur
    $log = $pdo->prepare('INSERT INTO admin_logs (action, target_user, executed_at) VALUES (?, ?, NOW())');
    $log->execute(['SUPPRESSION_PAR_ADMIN', $user['id']]);
  }

  // 7. Suppression effective
  $del = $pdo->prepare('DELETE FROM users WHERE id = ?');
  $del->execute([$user['id']]);

  echo json_encode(['success' => true, 'message' => 'Compte supprimé']);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
