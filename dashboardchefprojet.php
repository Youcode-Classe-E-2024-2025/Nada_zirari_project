<?php
// session_start();
// include('config.php'); // Inclure le fichier de connexion à la base de données

// Vérifier que l'utilisateur est connecté et est un chef de projet
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'chef_de_projet') {
//     header('Location: login.php');
//     exit();
// }

// Récupérer les projets du chef de projet
// $user_id = $_SESSION['user_id'];
// $query = "SELECT * FROM projects WHERE created_by = :user_id";
// $stmt = $conn->prepare($query);
// $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
// $stmt->execute();

// Vérifier si des projets ont été trouvés
// $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si aucun projet n'est trouvé, on initialise $projects comme un tableau vide
// if (!$projects) {
//     $projects = [];
// }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Chef de Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-900">
    <!-- En-tête -->
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Bienvenue, Chef de Projet !</h1>
            <nav>
                <a href="logout.php" class="text-white hover:text-gray-200">Déconnexion</a>
            </nav>
        </div>
    </header>

    <!-- Section des projets -->
    <section class="container mx-auto p-6 mt-6">
        <h2 class="text-xl font-semibold mb-4">Projets</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($projects)): ?>
                <p class="text-gray-600">Aucun projet trouvé. Ajoutez-en un nouveau ci-dessous.</p>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-semibold text-blue-600"><?= htmlspecialchars($project['name']) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($project['description']) ?></p>
                    <p class="text-sm mt-2">
                        <strong>Status:</strong> <?= $project['is_public'] ? 'Public' : 'Privé' ?>
                    </p>
                    <div class="mt-4">
                        <a href="edit_project.php?id=<?= $project['id'] ?>" class="text-blue-600 hover:text-blue-800">Modifier le projet</a> |
                        <a href="manage_members.php?id=<?= $project['id'] ?>" class="text-blue-600 hover:text-blue-800">Gérer les membres</a> |
                        <a href="manage_tasks.php?id=<?= $project['id'] ?>" class="text-blue-600 hover:text-blue-800">Gérer les tâches</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Formulaire d'ajout de projet -->
    <section class="container mx-auto p-6 mt-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Détails du Projet</h2>
        <form method="POST" action="add_project_with_tasks.php" class="space-y-4">
            <!-- Informations sur le projet -->
            <div>
                <label for="name" class="block text-gray-700 font-medium">Nom du projet :</label>
                <input type="text" name="name" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="description" class="block text-gray-700 font-medium">Description :</label>
                <textarea name="description" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label for="typeproject" class="block text-gray-700 font-medium">Type de projet :</label>
                <select name="typeproject" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Développement">Développement</option>
                    <option value="Recherche">Recherche</option>
                    <option value="Design">Design</option>
                    <option value="Marketing">Marketing</option>
                </select>
            </div>
            <div>
                <label for="is_public" class="block text-gray-700 font-medium">Visibilité :</label>
                <select name="is_public" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1">Public</option>
                    <option value="0">Privé</option>
                </select>
            </div>

            <!-- Informations sur les tâches -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Tâches à ajouter :</h3>
                <div id="task-container">
                    <div class="task-item space-y-4">
                        <div>
                            <label for="task_title" class="block text-gray-700 font-medium">Titre de la tâche :</label>
                            <input type="text" name="task_title[]" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="task_description" class="block text-gray-700 font-medium">Description de la tâche :</label>
                            <textarea name="task_description[]" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div>
                            <label for="task_assigned_to" class="block text-gray-700 font-medium">Assigner à :</label>
                            <select name="task_assigned_to[]" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <!-- Vous devrez charger les utilisateurs depuis la base de données -->
                                <option value="1">Utilisateur 1</option>
                                <option value="2">Utilisateur 2</option>
                                <option value="3">Utilisateur 3</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-task" class="text-blue-600 hover:text-blue-800">Ajouter une tâche</button>
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Ajouter le projet et les tâches</button>
            </div>
        </form>
    </section>
    <script>
        document.getElementById('add-task').addEventListener('click', function() {
            const taskContainer = document.getElementById('task-container');
            const newTaskItem = document.createElement('div');
            newTaskItem.classList.add('task-item', 'space-y-4');
            newTaskItem.innerHTML = `
                <div>
                    <label for="task_title" class="block text-gray-700 font-medium">Titre de la tâche :</label>
                    <input type="text" name="task_title[]" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="task_description" class="block text-gray-700 font-medium">Description de la tâche :</label>
                    <textarea name="task_description[]" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label for="task_assigned_to" class="block text-gray-700 font-medium">Assigner à :</label>
                    <select name="task_assigned_to[]" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Utilisateur 1</option>
                        <option value="2">Utilisateur 2</option>
                        <option value="3">Utilisateur 3</option>
                    </select>
                </div>
            `;
            taskContainer.appendChild(newTaskItem);
        });
    </script>
</body>
</html>
