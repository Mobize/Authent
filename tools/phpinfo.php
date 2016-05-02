<?php
// Affiche toutes les infos d'environnement du script
echo '<pre>';
print_r($_SERVER);
echo '</pre>';

// Affiche toute la configuration de PHP
phpinfo();