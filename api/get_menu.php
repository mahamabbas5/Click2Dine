<?php
require_once __DIR__ . '/helpers.php';

$rid = isset($_GET['restaurant_id']) ? (int)$_GET['restaurant_id'] : 0;
try {
    $pdo = getPDO();
    if ($rid > 0) {
        $stmt = $pdo->prepare('SELECT id, name, description, price, image FROM menu_items WHERE restaurant_id = :rid');
        $stmt->execute([':rid' => $rid]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query('SELECT id, name, description, price, image FROM menu_items');
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    jsonResponse(['success' => true, 'menu' => $items]);
} catch (Exception $e) {
    sendError($e->getMessage(), 500);
}

?>
