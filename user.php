<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Méthode pour enregistrer un utilisateur
    public function register($name, $email, $password) {
        // Vérifier si l'email existe déjà
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // L'email existe déjà dans la base de données
            return false;
        }

        // Hashage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertion dans la base de données
        $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'membre')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return true; // Inscription réussie
        }

        return false; // Erreur lors de l'inscription
    }

    // Méthode pour se connecter
    public function login($email, $password) {
        // Préparer la requête pour récupérer l'utilisateur par email
        $query = "SELECT id, name, email, password, role FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Vérifier si l'utilisateur existe
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier le mot de passe
            if (password_verify($password, $user['password'])) {
                // L'utilisateur est authentifié, retourner ses informations
                return $user; // Retourner les informations de l'utilisateur
            }
        }

        return false; // Identifiants incorrects
    }
}

?>
