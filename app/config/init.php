<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use App\config\Session;

// Démarrer la session automatiquement sur chaque requête
Session::start();
