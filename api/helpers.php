<?php
require_once __DIR__ . '/config.php';

function sendError($message, $code = 400) {
    jsonResponse(['success' => false, 'message' => $message], $code);
}

function fetchAll($stmt) {
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
