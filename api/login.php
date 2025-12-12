<?php
require_once __DIR__ . '/helpers.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) { $data = $_POST; }

$email = isset($data['email']) ? trim($data['email']) : '';
$password = isset($data['password']) ? $data['password'] : '';

if (!$email || !$password) {
    sendError('Email and password required');
}

try {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, password_hash, name FROM users WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        jsonResponse(['success' => true, 'user_id' => $user['id'], 'name' => $user['name']]);
    } else {
        sendError('Invalid credentials', 401);
    }
} catch (Exception $e) {
    sendError($e->getMessage(), 500);
}
?>
