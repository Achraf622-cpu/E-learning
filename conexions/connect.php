
<?php
class Connection {
    protected $conn;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'elearn';
        $username = 'root';
        $password = 'password';

        try {
            // Create PDO instance
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            // Set PDO attributes
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle connection errors
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Getter for the PDO instance
    public function getConnection() {
        return $this->conn;
    }

    // You can optionally expose query() or prepare() methods as well
    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }
}


?>



