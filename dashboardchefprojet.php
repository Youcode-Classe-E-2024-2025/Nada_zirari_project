<?php
// Inclure la connexion à la base de données et la classe pour les projets et les tâches
require_once 'config.php';  // Assurez-vous que votre fichier de configuration est bien inclu

// Vérifier que l'utilisateur est un chef de projet (admin)
session_start();
if ($_SESSION['role'] !== 'chef_de_projet') {
    header('Location: login.php');  // Rediriger vers la page de connexion si l'utilisateur n'est pas un chef de projet
    exit;
}

// Récupérer les projets du chef de projet
$projectsQuery = "SELECT * FROM projects WHERE created_by = :created_by";
$stmt = $pdo->prepare($projectsQuery);
$stmt->bindParam(':created_by', $_SESSION['user_id']);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les membres d'équipe
$membersQuery = "SELECT * FROM users WHERE role = 'membre'";
$stmt = $pdo->prepare($membersQuery);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Chef de Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- En-tête -->
    <header class="bg-blue-600 text-white py-6">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-extrabold">Dashboard Chef de Projet</h1>
            <p class="mt-2 text-lg">Gérez vos projets, tâches et membres d'équipe</p>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="py-8">
        <div class="container mx-auto px-4">
            <!-- Projets -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Projets</h2>
                <a href="create_project.php" class="bg-blue-500 text-white py-2 px-4 rounded">Créer un Projet</a>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    <?php foreach ($projects as $project): ?>
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <h3 class="text-xl font-semibold text-blue-600"><?= htmlspecialchars($project['name']) ?></h3>
                            <p class="text-gray-700 mt-2"><?= htmlspecialchars($project['description']) ?></p>
                            <p class="text-sm text-gray-500 mt-4">Créé le : <?= date('d M Y', strtotime($project['created_at'])) ?></p>
                            <a href="edit_project.php?id=<?= $project['id'] ?>" class="mt-4 inline-block text-blue-500 hover:text-blue-700">Modifier</a>
                            <a href="delete_project.php?id=<?= $project['id'] ?>" class="mt-4 inline-block text-red-500 hover:text-red-700">Supprimer</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Membres d'équipe -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Membres d'équipe</h2>
                <a href="add_member.php" class="bg-blue-500 text-white py-2 px-4 rounded">Ajouter un Membre</a>
                <div class="mt-6">
                    <ul>
                        <?php foreach ($members as $member): ?>
                            <li class="mb-4">
                                <p class="text-lg"><?= htmlspecialchars($member['name']) ?> (<?= htmlspecialchars($member['email']) ?>)</p>
                                <a href="remove_member.php?id=<?= $member['id'] ?>" class="text-red-500 hover:text-red-700">Retirer</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Tâches -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Tâches</h2>
                <a href="create_task.php" class="bg-blue-500 text-white py-2 px-4 rounded">Créer une Tâche</a>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    <!-- Liste des tâches à afficher ici -->
                    <!-- Exemple de tâche -->
                    <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <h3 class="text-xl font-semibold text-blue-600">Tâche 1</h3>
                        <p class="text-gray-700 mt-2">Description de la tâche.</p>
                        <p class="text-sm text-gray-500 mt-4">Assignée à : John Doe</p>
                        <a href="edit_task.php?id=1" class="mt-4 inline-block text-blue-500 hover:text-blue-700">Modifier</a>
                        <a href="delete_task.php?id=1" class="mt-4 inline-block text-red-500 hover:text-red-700">Supprimer</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pied de page -->
    <footer class="bg-gray-800 text-white py-4 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 Votre Entreprise. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
