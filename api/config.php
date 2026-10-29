<?php
// Click2Dine configuration (MySQL). Values come from environment variables so the
// same code runs on EC2 (local MySQL) and Elastic Beanstalk (RDS) without edits.
error_reporting(E_ALL);
ini_set('display_errors', 0);          // never leak errors to visitors
ini_set('log_errors', 1);

function env_first(array $names, $default = '') {
    foreach ($names as $n) {
        $v = getenv($n);
        if ($v !== false && $v !== '') return $v;
        if (isset($_SERVER[$n]) && $_SERVER[$n] !== '') return $_SERVER[$n];
    }
    return $default;
}

// Optional local override file (not committed): api/db_config.php returns an array
$local = file_exists(__DIR__ . '/db_config.php') ? require __DIR__ . '/db_config.php' : [];

define('DB_HOST', $local['host'] ?? env_first(['DB_HOST', 'RDS_HOSTNAME'], '127.0.0.1'));
define('DB_PORT', $local['port'] ?? env_first(['DB_PORT', 'RDS_PORT'], '3306'));
define('DB_NAME', $local['name'] ?? env_first(['DB_NAME', 'RDS_DB_NAME'], 'click2dine'));
define('DB_USER', $local['user'] ?? env_first(['DB_USER', 'RDS_USERNAME'], 'c2d_app'));
define('DB_PASS', $local['pass'] ?? env_first(['DB_PASSWORD', 'RDS_PASSWORD'], ''));

function getPDO() {
    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    return $pdo;
}

function jsonResponse($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Same-origin in production; kept permissive for local development
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    exit;
}
