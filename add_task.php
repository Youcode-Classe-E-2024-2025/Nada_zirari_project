<?php
session_start();
require_once 'database.php';
require_once 'project.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = $_POST['project_id'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $user_id = $_SESSION['user_id']; // Chef de projet

    $db = new Database();
    $conn = $db->connect();

    $project = new Project($conn);
    $project->createTask($task_name, $task_description, $project_id, $user_id);
    
    header('Location: dashboardchefprojet.php');
    exit();
}
?>
