<?php
class Database {
    private $host = '127.0.0.1';
    private $db = 'zirari_todo';
    private $user = 'root'; // Remplacez par votre utilisateur MySQL
    private $pass = ''; // Remplacez par votre mot de passe MySQL
    private $charset = 'utf8mb4';

    public function connect() {
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
            $pdo = new PDO($dsn, $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit();
        }
    }
}
?>
