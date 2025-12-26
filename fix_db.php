<?php
require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Users table
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS email VARCHAR(255) AFTER name");
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS location VARCHAR(255) AFTER email");
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar_url VARCHAR(255) DEFAULT NULL");
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS bio TEXT DEFAULT NULL");
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS interests TEXT DEFAULT NULL");
    echo "Users table updated.<br>";

    // Events table
    $conn->exec("ALTER TABLE events ADD COLUMN IF NOT EXISTS kit_config JSON AFTER category");
    echo "Events table updated.<br>";

    // Notifications table
    $sql = "CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        message TEXT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->exec($sql);
    echo "Notifications table checked.<br>";

    // RSVPs table
    $conn->exec("ALTER TABLE rsvps ADD COLUMN IF NOT EXISTS approved_at TIMESTAMP NULL AFTER status");
    $conn->exec("ALTER TABLE rsvps ADD COLUMN IF NOT EXISTS rejection_reason TEXT AFTER approved_at");
    echo "RSVPs table updated.<br>";

    // Photos table
    $sql = "CREATE TABLE IF NOT EXISTS photos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_id INT NOT NULL,
        user_id INT NOT NULL,
        file_path VARCHAR(255) NOT NULL,
        is_curated TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->exec($sql);
    echo "Photos table checked.<br>";

    // Messages table
    $sql = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_id INT NOT NULL,
        user_id INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->exec($sql);
    echo "Messages table checked.<br>";

    echo "Database fix completed!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
