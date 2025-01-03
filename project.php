<?php
session_start();
include('config.php'); // fichier de connexion à la base de données

// Vérifier que l'utilisateur est un chef de projet
if ($_SESSION['role'] != 'chef_de_projet') {
    header('Location: login.php');
    exit();
}

// Récupérer les projets du chef de projet
$query = "SELECT * FROM projects WHERE created_by = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajouter un nouveau projet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_project_with_tasks.php'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $is_public = $_POST['is_public'];

    $query = "INSERT INTO projects (name, description, created_by, is_public) 
              VALUES (:name, :description, :created_by, :is_public)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':created_by', $_SESSION['user_id']);
    $stmt->bindParam(':is_public', $is_public);
    $stmt->execute();

    header('Location: project.php');
}

// Modifier un projet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_project'])) {
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

    header('Location: project.php');
}

// Supprimer un projet
if (isset($_GET['delete_project'])) {
    $project_id = $_GET['delete_project'];
    $query = "DELETE FROM projects WHERE id = :project_id AND created_by = :created_by";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':project_id', $project_id);
    $stmt->bindParam(':created_by', $_SESSION['user_id']);
    $stmt->execute();

    header('Location: project.php');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Projets</title>
</head>
<body>
    <h1>Tableau de Bord - Chef de Projet</h1>

    <h2>Projets Existants</h2>
    <?php foreach ($projects as $project): ?>
        <div>
            <h3><?= htmlspecialchars($project['name']) ?></h3>
            <p><?= htmlspecialchars($project['description']) ?></p>
            <p>Status: <?= $project['is_public'] ? 'Public' : 'Privé' ?></p>
            <a href="edit_project.php?id=<?= $project['id'] ?>">Modifier</a> | 
            <a href="?delete_project=<?= $project['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">Supprimer</a>
        </div>
    <?php endforeach; ?>

    <h2>Ajouter un Nouveau Projet</h2>
    <form method="POST">
        <label for="name">Nom du projet :</label>
        <input type="text" name="name" required>
        <label for="description">Description :</label>
        <textarea name="description" required></textarea>
        <label for="is_public">Public :</label>
        <select name="is_public">
            <option value="1">Oui</option>
            <option value="0">Non</option>
        </select>
        <button type="submit" name="add_project_with_tasks.php">Ajouter</button>
    </form>

</body>
</html>
