<?php
require_once __DIR__ . '/helpers.php';

// Accept application/json or form POST
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    $data = $_POST;
}

$name = isset($data['name']) ? trim($data['name']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$password = isset($data['password']) ? $data['password'] : '';

if (!$email || !$password) {
    sendError('Email and password are required');
}

try {
    $pdo = getPDO();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :hash)');
    $stmt->execute([':name' => $name, ':email' => $email, ':hash' => $hash]);
    jsonResponse(['success' => true, 'user_id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    sendError($e->getMessage(), 500);
}

?>
