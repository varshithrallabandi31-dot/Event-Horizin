<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $phone;
    public $name;
    public $bio;
    public $interests;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Find user by phone
    public function findByPhone($phone) {
        if (!$this->conn) return false;
        $query = "SELECT * FROM " . $this->table_name . " WHERE phone = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $phone);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->phone = $row['phone'];
            $this->name = $row['name'];
            $this->bio = $row['bio'];
            $this->interests = $row['interests'];
            return $row;
        }
        return false;
    }

    // Find user by ID
    public function findById($id) {
        if (!$this->conn) return false;
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->phone = $row['phone'];
            $this->name = $row['name'];
            $this->bio = $row['bio'];
            $this->interests = $row['interests'];
            return $row;
        }
        return false;
    }

    // Create new user with just phone (first step)
    public function createWithPhone($phone) {
        if (!$this->conn) return false;
        $query = "INSERT INTO " . $this->table_name . " SET phone = :phone";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':phone', $phone);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Update profile
    public function updateProfile($id, $name, $bio, $interests) {
        if (!$this->conn) return false;
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, bio = :bio, interests = :interests 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $name = htmlspecialchars(strip_tags($name));
        $bio = htmlspecialchars(strip_tags($bio));
        
        // Ensure interests is valid JSON, default to empty JSON array if empty or invalid
        if (empty($interests)) {
            $interests = '[]';
        } else {
            // Verify if it is already valid JSON
            json_decode($interests);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // If not valid JSON, treat as comma-separated string and convert to JSON array
                $interestsArray = array_map('trim', explode(',', $interests));
                $interests = json_encode($interestsArray);
            }
        }
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':interests', $interests); // Now guaranteed to be valid JSON
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Update name and email
    public function updateBasicInfo($id, $name, $email = null) {
        if (!$this->conn) return false;
        
        $query = "UPDATE " . $this->table_name . " SET name = :name";
        if ($email) {
            $query .= ", email = :email";
        }
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $name = htmlspecialchars(strip_tags($name));
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id);
        
        if ($email) {
            $email = htmlspecialchars(strip_tags($email));
            $stmt->bindParam(':email', $email);
        }

        return $stmt->execute();
    }
}
