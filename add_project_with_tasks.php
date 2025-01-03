<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=zirari_todo', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
// Récupération des données du formulaire
$name = $_POST['name'];
$description = $_POST['description'];
$typeproject = $_POST['typeproject'];
$is_public = $_POST['is_public'];
$task_titles = $_POST['task_title'];
$task_descriptions = $_POST['task_description'];
$task_assigned_to = $_POST['task_assigned_to'];

// Insertion du projet dans la base de données
$sql_project = "INSERT INTO projects (name, description, typeproject, is_public) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql_project);
$stmt->execute([$name, $description, $typeproject, $is_public]);
$project_id = $stmt->insert_id; // Récupérer l'ID du projet inséré

// Insertion des tâches associées au projet
for ($i = 0; $i < count($task_titles); $i++) {
    $title = $task_titles[$i];
    $description = $task_descriptions[$i];
    $assigned_to = $task_assigned_to[$i];

    $sql_task = "INSERT INTO tasks (title, description, assigned_to, project_id) VALUES (?, ?, ?, ?)";
    $stmt_task = $conn->prepare($sql_task);
    $stmt_task->bind_param("ssii", $title, $description, $assigned_to, $project_id);
    $stmt_task->execute();
}

// Redirection vers le tableau de bord avec un message de succès
header("Location: dashboard.php?success=1");
exit();
}
?>