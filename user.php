<?php
require_once 'Database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($name, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO {$this->table} (name, email, password) VALUES (:name, :email, :password)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}
?>
