<?php
// Database configuration
class Database {
    private $host = "YOUR_DB_HOST";
    private $db_name = "YOUR_DB_NAME";
    private $username = "YOUR_DB_USER";
    private $password = "YOUR_DB_PASSWORD";
    private $port = "5432";
    public $conn;

    // Get database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
