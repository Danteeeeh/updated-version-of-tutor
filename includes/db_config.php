<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'if0_38927201_tutors_lounge');
define('DB_USER', 'root');
define('DB_PASS', '');


class Database {
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}", 
                $this->username, 
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("We're experiencing technical difficulties. Please try again later.");
        }

        return $this->conn;
    }
}

// Create global database connection
try {
    $database = new Database();
    $pdo = $database->connect();
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
