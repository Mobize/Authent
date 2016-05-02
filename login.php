<?php
require_once 'inc/config.php';

// On réceptionne les données du formulaire
$login = !empty($_POST['login']) ? strip_tags(trim($_POST['login'])) : '';
$password = !empty($_POST['password']) ? strip_tags(trim($_POST['password'])) : '';


$errors = array();

// Le formulaire a ete soumis, on a appuye sur le bouton Envoyer
if (!empty($_POST)) {

	// On check les erreurs possibles
	if (empty($login) || empty($password)) {
		$errors['login'] = 'Identifiants corrects';
	}

	//debug($errors);

	// Aucune erreur dans le formulaire, tous les champs ont été saisis correctement
	if (empty($errors)) {

		// On vérifie que le login/email est pas deja pris
		$query = $db->prepare('SELECT * FROM user WHERE login = :login');
		$query->bindValue(':login', $login, PDO::PARAM_STR);
		$query->execute();
		$user = $query->fetch();

		if (!empty($user)) {

			$crypted_password = $user['password'];

			if (password_verify($password, $crypted_password)) {

				// On connecte l'utilisateur
				$_SESSION['user_id'] = $user['id'];

				echo '<div class="alert alert-success" role="alert">';
				echo 'Connexion réussie !';
				echo '</div>';

				//header('Location: index.php');
				redirectJS('index.php', 2);
				exit();
			} else {
				$errors['login'] = 'Identifiants corrects';
			}
		}
	}
}

?>
<h1>Connexion</h1>

<form method="POST">
	<span style="color: red"><?= !empty($errors['login']) ? $errors['login'] : '' ?></span><br><br>

	Login : <input type="text" size="20" maxlength="100" id="login" name="login" value="<?= $login ?>">
	<br><br>

	Mot de passe : <input type="password" size="20" name="password">
	<br><br>

	<input type="submit" value="Connexion">

</form>