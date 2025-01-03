<?php
session_start();
include('config.php'); // fichier de connexion à la base de données

$project_id = $_GET['id'];

// Récupérer les membres du projet
$query = "SELECT u.id, u.name FROM users u JOIN project_members pm ON u.id = pm.user_id WHERE pm.project_id = :project_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':project_id', $project_id);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajouter un membre
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $query = "INSERT INTO project_members (project_id, user_id) VALUES (:project_id, :user_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Membres</title>
</head>
<body>
    <h1>Gérer les Membres du Projet</h1>

    <h2>Membres Actuels</h2>
    <?php foreach ($members as $member): ?>
        <div><?= htmlspecialchars($member['name']) ?></div>
    <?php endforeach; ?>

    <h2>Ajouter un Membre</h2>
    <form method="POST">
        <label for="user_id">Choisir un membre :</label>
        <select name="user_id">
            <?php
            // Récupérer les utilisateurs disponibles
            $query = "SELECT id, name FROM users WHERE id != :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($users as $user) {
                echo "<option value='" . $user['id'] . "'>" . htmlspecialchars($user['name']) . "</option>";
            }
            ?>
        </select>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
