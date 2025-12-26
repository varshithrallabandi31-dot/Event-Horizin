<?php
$host = 'localhost';
$db   = 'event_platform';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass);
    $stmt = $pdo->query("DESCRIBE ticket_tiers");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    file_put_contents('schema_dump.txt', print_r($rows, true));
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
