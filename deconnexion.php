<?php
// Déconnexion de la session du membre connecté !!!
session_start();
$_SESSION = array();
session_destroy();
header('Location:index.php');

?>
