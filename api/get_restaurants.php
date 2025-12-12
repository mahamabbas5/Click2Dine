<?php
require_once __DIR__ . '/helpers.php';

try {
    $pdo = getPDO();
    $stmt = $pdo->query('SELECT id, name, description FROM restaurants ORDER BY id');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    jsonResponse(['success' => true, 'restaurants' => $rows]);
} catch (Exception $e) {
    sendError($e->getMessage(), 500);
}

?>
