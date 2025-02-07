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

?>



<?php
$content = ob_get_clean();
include('layout.php');
?>