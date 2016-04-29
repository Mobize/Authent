<?php

session_name('wf3_session');
session_start();

echo '<pre>';
print_r($_SESSION);
print_r($_COOKIE);
echo '</pre>';

echo $_SESSION['id']; // 123