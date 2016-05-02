<?php
require_once 'inc/config.php';

$login = !empty($_POST['login']) ? strip_tags(trim($_POST['login'])) : '';

$error = null;
$status = false;

if (empty($login) || !filter_var($login, FILTER_VALIDATE_EMAIL)) {
	$error = 'Veuillez renseigner un email valide';
}

if (empty($errors)) {

	// On vérifie que le login/email est pas deja pris
	$query = $db->prepare('SELECT * FROM user WHERE login = :login');
	$query->bindValue(':login', $login, PDO::PARAM_STR);
	$query->execute();
	$user = $query->fetch();

	if (!empty($user)) {
		$error = 'Cet email est déjà pris';
	} else {
		$status = true;
	}
}

$result = array(
	'status' => $status,
	'error' => $error
);

exit(json_encode($result));