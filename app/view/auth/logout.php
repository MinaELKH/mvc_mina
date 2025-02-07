<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';

use App\config\Session;
Session::start();

// Détruire toutes les variables de session
Session::destroy();

// Rediriger l'utilisateur vers la page d'accueil ou de connexion
header("Location: login.php");
exit();
?>