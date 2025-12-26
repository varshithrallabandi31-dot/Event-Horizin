<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Add requires_approval column
    $sql = "ALTER TABLE events ADD COLUMN requires_approval TINYINT(1) DEFAULT 1";
    $conn->exec($sql);
    echo "Column 'requires_approval' added successfully.\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
