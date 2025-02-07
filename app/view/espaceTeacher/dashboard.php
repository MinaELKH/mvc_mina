<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';
require_once("../sweetAlert.php");
require_once("../uploadimage.php");

use classes\Course;
use classes\Categorie;
use classes\ContentText;
use classes\ContentVideo;
use config\DataBaseManager;
use classes\Tag;
use classes\CourseTags;
use config\session;


ob_start();

session::start();
if (Session::isLoggedIn() && session::hasRole('teacher')) {
    // Récupérer les données de session
    $s_userId = Session::get('user')['id'];
    $s_userName = Session::get('user')['name'];
    $s_userEmail = Session::get('user')['email'];
    $s_userRole = Session::get('user')['role'];
    $s_userAvatar = Session::get('user')['avatar'];
    //  var_dump($userAvatar); 
} else {
    setSweetAlertMessage(
        'Authentification requise ⚠️',
        'Veuillez vous authentifier en tant qu enseignant pour  accéder a cette page.',
        'warning',
        '../auth/login.php'
    );
}
$dbManager = new DatabaseManager();
?>



<?php
$content = ob_get_clean();
include('layout.php');
?>