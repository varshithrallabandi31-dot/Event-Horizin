<?php
$host = 'localhost';
$db   = 'event_platform';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT * FROM ticket_tiers LIMIT 5");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
