<?php
require_once __DIR__ . '/config.php';
try {
    $pdo = getPDO();
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    jsonResponse(['success' => true, 'database' => DB_NAME, 'tables' => $tables]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Database connection failed'], 500);
}
