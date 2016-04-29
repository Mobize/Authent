Authentification
===================

----------
Description
-------------

Inscription (avec cryptage du mot de passe), connexion, déconnexion

----------
Consignes
-------------

### Inscription (register.php)

1. Créer un formulaire HTML en method POST avec les champs :
	- login (email)
	- password
	- confirm_password

2. Créer une base de données "authent" (utf8_general_ci)

3. Créer une table "user" avec les champs :
	- id
	- login
	- password (CHAR 60)

4. Réceptionner et contrôler les données du formulaire :
	- login non vide
	- password longueur 8 minimum
	- confirm_password === password

5. Si pas d'erreur :
	- Crypter le mot de passe (php.net/password_hash)
	- Faire une requête qui insert une ligne dans la table ``user``
	- Si la requête a fonctionné, mettre l'id du user généré par la requête (AUTO_INCREMENT) en SESSION, puis rediriger l'utilisateur vers la page d'accueil

### Page d'accueil (index.php)

1. Démarrer la session

2. Vérifier qu'on a un identifiant utilisateur en session :
	- S'il est défini et non vide, s'en servir pour faire une requête qui va chercher la ligne dans la table ``user`` correspondant à l'identifiant en session, et afficher l'email du user
	- Sinon, afficher "Utilisateur non connecté

### Connexion (login.php)

1. Créer un formulaire HTML en method POST avec les champs :
	- login (email)
	- password

2.  Réceptionner et contrôler les données du formulaire

3. Si pas d'erreur, on fait une requête qui va chercher une ligne dans la table ``user`` correspondant au login renseigné :
	- Si on trouve une ligne, on va vérifier que le mot de passe tapé est le bon (php.net/password_verify), et on stock l'identifiant utilisateur en session + redirection home
	- Sinon, on affiche un message d'erreur

### Déconnexion (logout.php)

1. Démarrer la session

2. Détruire la session (php.net/session_unset + php.net/session_destroy)

3. Redirection home


