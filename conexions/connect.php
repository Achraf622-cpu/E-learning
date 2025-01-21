
<?php
class Connection {
    protected $conn;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'elearn';
        $username = 'root';
        $password = 'password';

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}

?>



