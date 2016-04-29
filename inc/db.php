<?php
// Domaine ou IP du serveur ou est située la base de données
define('HOST', 'localhost');
// Nom d'utilisateur autorisé à se connecter à la base
define('USER', 'root');
// Mot de passe de connexion à la base
define('PASS', '');
// Base de données sur laquelle on va faire les requêtes
define('DB', 'authent');

// Le bloc try test tout ce qui se situe à l'intérieur
try {

    // Tableau d'options pour la base de données
    $db_options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", // On force l'encodage en utf8
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // On récupère tous les résultats en tableau associatif
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING // On affiche des warnings pour les erreurs, à commenter en prod
    );

    // On crée une connexion avec la base de données
    $db = new PDO('mysql:host='.HOST.';dbname='.DB, USER, PASS, $db_options);

// Si une erreur survient, on entre dans le bloc catch et on récupère l'erreur
} catch (Exception $e) {
    // On quitte l'execution du script en affichant le message d'erreur
    exit('MySQL Connect Error >> '.$e->getMessage());
}