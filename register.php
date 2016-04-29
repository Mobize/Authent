<?php
require_once 'inc/config.php';

//debug($_POST);

// On réceptionne les données du formulaire
$login = !empty($_POST['login']) ? strip_tags($_POST['login']) : '';
$password = !empty($_POST['password']) ? strip_tags($_POST['password']) : '';
$confirm_password = !empty($_POST['confirm_password']) ? strip_tags($_POST['confirm_password']) : '';

$errors = array();

// Le formulaire a ete soumis, on a appuye sur le bouton Envoyer
if (!empty($_POST)) {

	// On check les erreurs possibles
	if (empty($login) || !filter_var($login, FILTER_VALIDATE_EMAIL)) {
		$errors['login'] = 'Veuillez renseigner un email valide';
	}
	if (empty($password)) {
		$errors['password'] = 'Veuillez renseigner un mot de passe';
	} else if (strlen($password) < 8) {
		$errors['password'] = 'Votre mot de passe doit comporter au moins 8 caractères';
	}
	if (!empty($password) && $password !== $confirm_password) {
		$errors['confirm_password'] = 'Les 2 mots de passe ne correspondent pas';
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
			$errors['login'] = 'Cet email est deja pris';
		} else {

			$crypted_password = password_hash($password, PASSWORD_BCRYPT);

			$query = $db->prepare('INSERT INTO user SET login = :login, password = :password, date = NOW()');

			// Pour chacune des variables précédées d'un : on doit faire un bindValue pour passer la valeur à la requête
			$query->bindValue(':login', $login, PDO::PARAM_STR);
			$query->bindValue(':password', $crypted_password, PDO::PARAM_STR);

			// On execute la requête
			$query->execute();

			// On récupère le numéro de la ligne automatiquement généré par MySQL avec l'attribut AUTO_INCREMENT
			$insert_id = $db->lastInsertId();

			if (!empty($insert_id)) {

				$_SESSION['user_id'] = $insert_id;

				echo '<div class="alert alert-success" role="alert">';
				echo 'Inscription réussie !';
				echo '</div>';

				//header('Location: index.php');
				redirectJS('index.php', 3);

				exit();
			}
			$errors['db_error'] = 'Erreur interne, merci de reessayer ulterieurement';
		}
	}
}

/*
foreach($errors as $error) {
	echo $error.'<br>';
}
echo '<hr>';
*/
?>


<form method="POST">

	Login : <input type="text" size="20" maxlength="100" id="login" name="login" value="<?= $login ?>">
	<span style="color: red"><?= !empty($errors['login']) ? $errors['login'] : '' ?></span>
	<br><br>

	Mot de passe : <input type="password" size="20" name="password">
	<span style="color: red"><?= !empty($errors['password']) ? $errors['password'] : '' ?></span>
	<br><br>

	Confirmation du mot de passe : <input type="password" size="20" name="confirm_password">
	<span style="color: red"><?= !empty($errors['confirm_password']) ? $errors['confirm_password'] : '' ?></span>
	<br><br>

	<input type="submit" value="Inscription">

</form>