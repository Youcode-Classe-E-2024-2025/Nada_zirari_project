<?php
session_start();
require_once 'database.php';
require_once 'user.php'; // Assurez-vous que la classe User est incluse ici

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les informations de connexion
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Créer une instance de la base de données
    $db = new Database();
    $conn = $db->connect();

    // Créer une instance de la classe User
    $user = new User($conn);

    // Vérifier les informations de connexion
    $user_data = $user->login($email, $password);
    if ($user_data) {
        // Vérification des données de l'utilisateur
        var_dump($user_data); // Vérifie les données de l'utilisateur retournées

        // Stocker les informations de l'utilisateur dans la session
        $_SESSION['user_id'] = $user_data['id']; // Stocker l'ID de l'utilisateur
        $_SESSION['role'] = $user_data['role']; // Stocker le rôle de l'utilisateur

        // Vérification de la session
        var_dump($_SESSION); // Vérifie le contenu de la session

        // Redirection en fonction du rôle
        if ($user_data['role'] === 'chef_de_projet') {
            header('Location: dashboardchefprojet.php'); // Rediriger vers le tableau de bord chef de projet
            exit();
        } elseif ($user_data['role'] === 'membre') {
            header('Location: dashboardmembre.php'); // Tableau de bord membre
            exit();
        } else {
            header('Location: dashboardinvite.php'); // Page par défaut pour les invités
            exit();
        }
    } else {
        $error_message = "Identifiants incorrects.";
        echo $error_message; // Afficher le message d'erreur
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page de Connexion</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-200 flex items-center justify-center h-screen">
  <div class="bg-gray-800 shadow-lg rounded-lg flex w-3/4 max-w-4xl">
    <!-- Section du formulaire -->
    <div class="w-1/2 p-8">
      <h2 class="text-2xl font-bold text-gray-100 mb-4">Connexion</h2>
      <form id="loginForm" class="space-y-4" action="login.php" method="POST">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-300">Adresse Email</label>
          <input type="email" id="email" name="email" required
                 class="w-full mt-1 px-4 py-2 border border-gray-600 rounded-lg shadow-sm bg-gray-700 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-300">Mot de Passe</label>
          <input type="password" id="password" name="password" required
                 class="w-full mt-1 px-4 py-2 border border-gray-600 rounded-lg shadow-sm bg-gray-700 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit"
                class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition">
          Se Connecter
        </button>
      </form>
      <p class="text-sm text-gray-400 mt-4 text-center">
        Pas encore inscrit ? <a href="register.php" class="text-blue-400 hover:underline">Créer un compte</a>
      </p>
    </div>
  </div>
</body>
</html>
