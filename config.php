<?php
// Configuration de la connexion à la base de données
$host = '127.0.0.1';
$dbname = 'zirari_todo';
$username = 'root';
$password = ''; // Vous pouvez mettre le mot de passe ici si nécessaire

try {
    // Connexion avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la connexion échoue, afficher l'erreur
    echo "Erreur de connexion : " . $e->getMessage();
    die();
}
?>
