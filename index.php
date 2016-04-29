<?php
require_once 'inc/config.php';

if (!empty($_SESSION['user_id'])) {

	// L'utilisateur est connecte
	$user_id = $_SESSION['user_id'];

	$query = $db->prepare('SELECT * FROM user WHERE id = :id');
	$query->bindValue(':id', $user_id, PDO::PARAM_INT);
	$query->execute();
	$user = $query->fetch();

	if (!empty($user)) {
		echo 'Bonjour, vous êtes connecté(e) en tant que '.$user['login'].'<br>';
		echo '<a href="logout.php">Déconnexion</a>';
		exit();
	}
}
echo '<a href="register.php">Inscription</a> | <a href="login.php">Connexion</a>';
