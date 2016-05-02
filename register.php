<?php
require_once 'inc/config.php';

//debug($_POST);

// On réceptionne les données du formulaire
$login = !empty($_POST['login']) ? strip_tags(trim($_POST['login'])) : '';
$password = !empty($_POST['password']) ? strip_tags(trim($_POST['password'])) : '';
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

				// On connecte l'utilisateur
				$_SESSION['user_id'] = $insert_id;

				echo '<div class="alert alert-success" role="alert">';
				echo 'Inscription réussie !';
				echo '</div>';

				//header('Location: index.php');
				redirectJS('index.php', 2);

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
<h1>Inscription</h1>

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
$(document).ready(function() {

	function checkPassword(password) {

		var score = 0;
		if (password.length >= 8) {
			score++;
		}
		if (/[a-z]/.test(password)) {
			score++;
		}
		if (/[A-Z]/.test(password)) {
			score++;
		}
		if (/[0-9]/.test(password)) {
			score++;
		}
		if (/[^\w\s]/gi.test(password)) {
			score++;
		}

		//console.log(score);

		return score;
	}

	function displayPasswordStrength(score, container) {
		var score_label = '';
		var score_color = '';
		switch(score) {
			case 1:
				score_label = 'tres faible';
				score_color = 'red';
			break;
			case 2:
				score_label = 'faible';
				score_color = 'orange';
			break;
			case 3:
				score_label = 'moyen';
				score_color = 'grey';
			break;
			case 4:
				score_label = 'fort';
				score_color = 'lightblue';
			break;
			case 5:
				score_label = 'tres fort';
				score_color = 'green';
			break;
		}

		$(container).css('color', score_color).text(score_label);
	}

	$('form input[name="password"]').on('blur keyup', function() {

		var password = $(this).val();

		var score = checkPassword(password);

		displayPasswordStrength(score, $(this).next('span'));
	});

	$('form input[name="login"]').on('blur', function() {

		var login = $(this).val();
		var $result = $(this).next('span');

		$result.text('');

		if (login.indexOf('@') === -1 ||
			login.indexOf('.') === -1 ||
			login.length < 6) {
			$result.text('Vous devez renseigner un email valide');

			return false;
		}

		$.ajax({
			method: 'POST',
			url: 'ajax-check-login.php',
			data: {login: login},
			dataType: 'json'
		})
		.done(function(result) {

			if (typeof(result.error) !== 'undefined' && result.error !== null) {
				$result.css('color', 'red').text(result.error);
			} else if (typeof(result.status) !== 'undefined' && result.status === true) {
				$result.css('color', 'green').text("L'email saisi est correct et disponible");
			}
		});

	});
});
</script>