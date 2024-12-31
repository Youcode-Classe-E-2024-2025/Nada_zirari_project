<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        session_start();
        $_SESSION['user'] = $loggedInUser;
        header('Location: dashboard.php');
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <form method="POST">
        <label>Email :</label>
        <input type="email" name="email" required>
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
