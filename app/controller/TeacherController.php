<?php

namespace App\controller;

use App\core\Controller;
use App\config\DataBaseManager;
use App\config\Session;
use App\helper\uploadimage;
use App\helpers\SweetAlert;
use App\model\Categorie;
use App\model\ContentText;
use App\model\ContentVideo;
use App\model\Course;
use App\Model\Teacher;
use Exception;

class TeacherController extends Controller
{
    private $dbManager; // Attribut de classe
    private $newCourse;
    private  $teacherId ;


    public function __construct()
    {
        if (Session::isLoggedIn() && session::hasRole('teacher')) {
            $this->dbManager = new DataBaseManager();
            $this->newCourse = new Course( $this->dbManager ) ;
            $s_userId = Session::get('user')['id'];
            $this->teacherId =  $s_userId;
        } else {
            SweetAlert::setMessage(
                'Authentification requise ⚠️',
                'Veuillez vous authentifier en tant qu enseignant pour  accéder a cette page.',
                'warning',
                '../auth/login.php'
            );
        }



    }

    public function index() {

        try {
            $s_userId = Session::get('user')['id'];
            $teacherId =  $s_userId;
            $newTeacher = new Teacher($this->dbManager, $teacherId);
            $statut = $newTeacher ->hasStatut();
            // recuperation du courses
            $courses = $this->newCourse->getMyCoursesTeacher($teacherId);

            //statistique

            $countCourses = $newTeacher->getCountCoursesByTeacher();
            $countInscrits = $newTeacher->getCountInscritByTeacher();
            $ca = $newTeacher->getCAByTeacher();
            $topStudents = $newTeacher->getTopStudentbyTeacher();

            $data = [
                'statut' => $statut,
                'countCourses' => $countCourses,
                'countInscrits' => $countInscrits,
                'topStudents' => $topStudents,
                'ca' => $ca,
                'courses' => $courses
            ] ;
            $this->view('espaceTeacher/mesCourses', $data);


        } catch (Exception $e) {
            // Log error and redirect or show error message
            error_log($e->getMessage());
            $courses = [];
        }
    }

    
    public function detailCourse($id_course)
    {

    }
    public function Archive() {
        try {
            $course = new Course($this->dbManager, $_POST['id_course']);
            $result = $course->archive();
            if ($result) {
                SweetAlert::setMessage('Succès', 'Le cours a été archivé avec succès.', 'success', '');
            } else {
                SweetAlert::setMessage('Erreur', 'Aucun archivage n\'a eu lieu. Veuillez contacter l\'administrateur.', 'error', '');
            }
        } catch (Exception $e) {
            SweetAlert::setMessage('Erreur', $e->getMessage(), 'error', '');
        }
    }



    public function AjouterCourse() {
        try {
            // Validation des champs
            if (empty($_POST['title'])) {
                throw new Exception("Le titre du cours est obligatoire.");
            }
            if (empty($_POST['id_categorie'])) {
                throw new Exception("La catégorie est obligatoire.");
            }


            $uploadResult = uploadImage::uploadImage($_FILES['picture']);
            $picture = $uploadResult['filePath'];
            // Création du cours
            $newCourse = new Course(
                $this->dbManager,
                0,
                $_POST['title'],
                $_POST['description'],
                $picture,
                $this->teacherId, // Supposant que l'ID du professeur est dans la session
                $_POST['id_categorie'],
                Course::STATUS_PENDING,
                0, //archive
                $_POST['prix'],
                $_POST['type']
            );

            $result = $newCourse->add();
            $id_course = $this->dbManager->getLastInsertId();
            if ($result) {

                // Gestion du contenu en fonction du type
                $type = $_POST['type'];

                if ($type === 'video') {
                    // Validation des champs spécifiques au type "Vidéo"


                    if (!empty($_POST['videoURL'])) {
                        $url= $_POST['videoURL'];
                    } elseif (isset($_FILES['videoUpload'])) {
                        $url = uploadVideo($_FILES['videoUpload']);

                    }


                    // Création du contenu vidéo
                    $videoContent = new ContentVideo($this->dbManager);
                    $videoContent->setCourseId($id_course);
                    $videoContent->setTitle($_POST['title']);
                    $videoContent->setUrl($url['filePath']);
                    $videoContent->setDuration((int)$_POST['duration']);

                    if (!$videoContent->add()) {
                        throw new Exception("Échec de l'ajout du contenu vidéo.");
                    }
                } elseif ($type === 'texte') {
                    // Validation des champs spécifiques au type "Texte"
                    if (empty($_POST['content'])) {
                        throw new Exception("Le contenu texte est obligatoire.");
                    }

                    // Création du contenu texte
                    $textContent = new ContentText($this->dbManager);
                    $textContent->setCourseId($id_course);
                    $textContent->setTitle($_POST['title']);
                    $textContent->setContent($_POST['content']);

                    if (!$textContent->add()) {
                        throw new Exception("Échec de l'ajout du contenu texte.");
                    }
                } else {
                    throw new Exception("Type de contenu invalide.");
                }

                // Succès
                setSweetAlertMessage('Succès', 'Le cours et son contenu ont été ajoutés avec succès', 'success', 'addCourse.php');
            } else {
                throw new Exception("Échec de l'ajout du contenue.");

            }
        } catch (Exception $e) {
            setSweetAlertMessage('Erreur', $e->getMessage(), 'error', 'addCourse.php');
        }
    }

    public function viewAdd() {
        $this->view('spaceTeacher/addCourse');;
        $categories = Categorie::getAll($this->dbManager);

    }
}
