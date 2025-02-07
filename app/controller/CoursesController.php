<?php

namespace App\controller;

use App\core\Controller;
use App\config\DataBaseManager;
use App\config\Session;
use App\helpers\SweetAlert;
use App\model\ContentText;
use App\model\ContentVideo;
use App\model\Course;
use Exception;

class CoursesController extends Controller
{
    private $this->dbManager; // Attribut de classe
    private $newCourse;
    public function __construct()
    {
        $this->dbManager = new DataBaseManager();
        $this->newCourse = new Course( $this->dbManager ) ;

    }

    public function index() {
        $id_student = Session::get('user')['id'];
        $courses = $this->newCourse->getMyCourses($id_student);
        $this->view('espaceStudent/mesCourses', ['courses' => $courses]);
    }

    
    public function detailCourse($id_course)
    {
        $id_course = $_GET['id_course'] ?? null;
        $id_content = $_GET['id_content'] ?? null;
// charger les donnees du cours
        if (!$id_course || !is_numeric($id_course)) {
            die("ID de cours invalide ou manquant.");
        }
// charger les donnees du cours
        try {
            $newCourse = new Course($this->dbManager, $id_course);
            $course = $this->newCourse->getDetailCourse(); // je selection d apres viewcourse 


            if (!$course) {
                throw new Exception("Le cours avec l'ID $id_course n'existe pas.");
            }
            // Recuperer le contenu du cours en fonction de son type
            $newContent = null;

            if ($course->type == 'texte') {
                $newContent = new ContentText($this->dbManager);
                $result = ContentText::getAllByIdCourse($this->dbManager, $id_course);
            } else if ($course->type == 'video') {
                $newContent = new ContentVideo($this->dbManager);
                $result = ContentVideo::getAllByIdCourse($this->dbManager, $id_course);
                // var_dump($newContent) ;
                // die() ;
            }


            /// on verifie si le id_content dans l url , ou on va recupere le premier chapitre par defaut 
            if (isset($_GET['id_content'])) {
                // verif si le contenu est trouve on le recupere via l id_course
                if (is_numeric($_GET['id_content'])) {
                    $newContent->id_content = intval($_GET['id_content']);
                    $ObjetContent = $newContent->getById();
                } else {

                    throw new Exception("ID content invalide ");
                }
            } elseif ($newContent) {
                // echo "hello" ;
                // var_dump($newContent) ;
                // die() ; 
                $newContent->id_course = $id_course;
                $ObjetContent = $newContent->getByIdCourse();
                // echo "hello" ;
                // var_dump($ObjetContent) ;
                // die() ; 
            } else {
                throw new Exception("Le contenu associe au cours n'a pas ete trouve.");
            }
        } catch (Exception $e) {
            // Gerer les erreurs
            error_log($e->getMessage());
            die("Erreur : Impossible de charger les donnees du cours.");
        }

    }
  

    public function Ajouter() {
        $newCategorie = new Categorie($this->dbManager, 0, $_POST['name'], $_POST['description']);
        $result = $newCategorie->add();
        $this->view('epaceAdmin/categorie', $result);
    }

    public function Archive() {
        try {
            $newCourse = new Course($this->dbManager, $_POST['id_categorie']);
            $result = $newCourse->archived();
            if ($result) {
                sweetAlert::setMessage('Succès', 'Course archivée avec succès.', 'success', '');
            } else {
                sweetAlert::setMessage('Erreur', 'Aucun archivage n\'a eu lieu. Veuillez contacter le superAdmin', 'error', '');
            }
        } catch (Exception $e) {
            sweetAlert::setMessage('Erreur', $e->getMessage(), 'error', '');
        }
    }
}
