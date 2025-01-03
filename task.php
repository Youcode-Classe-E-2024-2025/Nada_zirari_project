<?php
class Task {
    private $conn;
    private $table = 'tasks';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($title, $description, $status, $project_id, $assigned_to) {
        $query = "INSERT INTO {$this->table} (title, description, status, project_id, assigned_to) VALUES (:title, :description, :status, :project_id, :assigned_to)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->bindParam(':assigned_to', $assigned_to);
        return $stmt->execute();
    }

    public function getAllByProject($project_id) {
        $query = "SELECT * FROM {$this->table} WHERE project_id = :project_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
