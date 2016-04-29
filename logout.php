<?php
require_once 'inc/config.php';

session_unset();
session_destroy();

//TODO : delete client cookie

echo '<h1>Déconnexion</h1>';
echo 'Vous avez bien été déconnecté';

redirectJS('index.php', 2);