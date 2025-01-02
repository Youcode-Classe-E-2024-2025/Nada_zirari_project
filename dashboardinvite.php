<?php
// Inclure la connexion à la base de données
require_once 'config.php';

// Créer une classe Project pour récupérer les projets publics
class Project {
    private $conn;
    private $table = 'projects';

    public function __construct($pdo) {
        // Utiliser la connexion PDO passée en paramètre
        $this->conn = $pdo;
    }

    // Récupérer les projets publics
    public function getPublicProjects() {
        $query = "SELECT * FROM {$this->table} WHERE is_public = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Créer une instance de la classe Project et récupérer les projets publics
$project = new Project($pdo);
$publicProjects = $project->getPublicProjects();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets Publics</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- En-tête -->
    <header class="bg-blue-600 text-white py-6">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-extrabold">Projets Publics</h1>
            <p class="mt-2 text-lg">Découvrez les projets disponibles pour le public</p>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="py-8">
        <div class="container mx-auto px-4">
            <?php if (count($publicProjects) > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($publicProjects as $project): ?>
                        <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <h2 class="text-xl font-semibold text-blue-600"><?= htmlspecialchars($project['name']) ?></h2>
                            <p class="text-gray-700 mt-2"><?= htmlspecialchars($project['description']) ?></p>
                            <p class="text-sm text-gray-500 mt-4">Créé le : <?= date('d M Y', strtotime($project['created_at'])) ?></p>
                            <a href="#" class="mt-4 inline-block text-blue-500 hover:text-blue-700">Voir plus</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-600">Aucun projet public disponible pour le moment.</p>
            <?php endif; ?>
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
