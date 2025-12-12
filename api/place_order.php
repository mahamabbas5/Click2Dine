<?php
require_once __DIR__ . '/helpers.php';

// Expect JSON payload with delivery details
$body = json_decode(file_get_contents('php://input'), true);
if (!$body) {
    sendError('Invalid JSON payload');
}

$user_id = isset($body['user_id']) ? (int)$body['user_id'] : null;
$customer_name = isset($body['customer_name']) ? trim($body['customer_name']) : '';
$customer_phone = isset($body['customer_phone']) ? trim($body['customer_phone']) : '';
$delivery_address = isset($body['delivery_address']) ? trim($body['delivery_address']) : '';
$items = isset($body['items']) ? $body['items'] : [];
$total = isset($body['total']) ? (float)$body['total'] : 0.0;

// Validation
if (empty($customer_name) || empty($customer_phone) || empty($delivery_address)) {
    sendError('Customer name, phone, and delivery address are required');
}

if (empty($items)) {
    sendError('Order must contain at least one item');
}

if ($total <= 0) {
    sendError('Invalid order total');
}

try {
    $pdo = getPDO();
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, customer_name, customer_phone, delivery_address, items_json, total, status) 
                           VALUES (:uid, :name, :phone, :address, :items, :total, :status)');
    $stmt->execute([
        ':uid' => $user_id,
        ':name' => $customer_name,
        ':phone' => $customer_phone,
        ':address' => $delivery_address,
        ':items' => json_encode($items),
        ':total' => $total,
        ':status' => 'pending'
    ]);
    
    jsonResponse([
        'success' => true, 
        'order_id' => $pdo->lastInsertId(),
        'message' => 'Order placed successfully'
    ]);
} catch (Exception $e) {
    sendError($e->getMessage(), 500);
}
?>