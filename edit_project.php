<?php
session_start();
include('config.php'); // fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = $_POST['project_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $is_public = $_POST['is_public'];

    $query = "UPDATE projects SET name = :name, description = :description, is_public = :is_public 
              WHERE id = :project_id AND created_by = :created_by";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':is_public', $is_public);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':created_by', $_SESSION['user_id']);
    $stmt->execute();

    header('Location: dashboard.php');
} else {
    $project_id = $_GET['id'];
    $query = "SELECT * FROM projects WHERE id = :project_id AND created_by = :created_by";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':created_by', $_SESSION['user_id']);
    $stmt->execute();
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Projet</title>
</head>
<body>
    <h1>Modifier le Projet</h1>

    <form method="POST">
        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
        <label for="name">Nom du projet :</label>
        <input type="text" name="name" value="<?= htmlspecialchars($project['name']) ?>" required>
        <label for="description">Description :</label>
        <textarea name="description" required><?= htmlspecialchars($project['description']) ?></textarea>
        <label for="is_public">Public :</label>
        <select name="is_public">
            <option value="1" <?= $project['is_public'] ? 'selected' : '' ?>>Oui</option>
            <option value="0" <?= !$project['is_public'] ? 'selected' : '' ?>>Non</option>
        </select>
        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>
