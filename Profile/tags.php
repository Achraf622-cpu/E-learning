<?php
class Tag
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getAllTags()
    {
        $stmt = $this->conn->prepare("SELECT * FROM tags");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
