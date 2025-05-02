<?php
session_start();
define('DB_HOST', 'localhost');
define('DB_NAME', 'feedback_system');
define('DB_USER', 'root'); // Update with your DB username
define('DB_PASS', '');     // Update with your DB password

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>