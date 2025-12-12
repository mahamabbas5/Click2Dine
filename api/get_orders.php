<?php
require_once __DIR__ . '/helpers.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
try {
    $pdo = getPDO();
    if ($user_id > 0) {
        $stmt = $pdo->prepare('SELECT id, items_json, total, status, created_at FROM orders WHERE user_id = :uid ORDER BY id DESC');
        $stmt->execute([':uid' => $user_id]);
    } else {
        $stmt = $pdo->query('SELECT id, user_id, items_json, total, status, created_at FROM orders ORDER BY id DESC');
    }
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    jsonResponse(['success' => true, 'orders' => $rows]);
} catch (Exception $e) {
    sendError($e->getMessage(), 500);
}

?>
