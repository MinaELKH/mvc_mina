<?php

namespace App\controller;

use App\core\Controller;
use App\config\DataBaseManager;
use App\config\Session;
use App\helper\uploadimage;
use App\helper\SweetAlert;
use App\model\Categorie;
use App\model\ContentText;
use App\model\ContentVideo;
use App\model\Course;
use App\Model\Teacher;
use Exception;

class TeacherController extends Controller
{
    private DataBaseManager $dbManager;
    private int $teacherId;
    private $statut;

    public function __construct()
    {
        if (!Session::isLoggedIn() || !Session::hasRole('teacher')) {
            SweetAlert::setMessage(
                'Authentification requise ⚠️',
                'Veuillez vous authentifier en tant qu\'enseignant pour accéder à cette page.',
                'warning',
                '../auth/login.php'
            );
            exit;
        }

        $this->dbManager = new DataBaseManager();
        $this->teacherId = Session::get('user')['id'];
        $this->statut = (new Teacher($this->dbManager, $this->teacherId))->getStatut() ;
    }

    public function index()
    {
        try {
            $teacher = new Teacher($this->dbManager, $this->teacherId);
            $data = [
                'statut' => $this->statut,
                'countCourses' => $teacher->getCountCoursesByTeacher(),
                'countInscrits' => $teacher->getCountInscritByTeacher(),
                'topStudents' => $teacher->getTopStudentbyTeacher(),
                'ca' => $teacher->getCAByTeacher(),
                'courses' => (new Course($this->dbManager))->getMyCoursesTeacher($this->teacherId)
            ];
            $this->view('espaceTeacher/mesCourses', $data);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function Archive()
    {
        try {
            $course = new Course($this->dbManager, $_POST['id_course']);
            $course->archive();

            $message = $course->archive() ? 'Le cours a été archivé avec succès.' : 'Aucun archivage n\'a eu lieu.';
            SweetAlert::setMessage('Succès', $message, 'success',  '/mvc_mina/Teacher/');
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function AjouterCourse()
    {
        try {
            $this->validateCourseData($_POST);

            $picture = uploadImage::uploadImage($_FILES['picture'])['filePath'];
            $newCourse = new Course(
                $this->dbManager, 0, $_POST['title'], $_POST['description'], $picture,
                $this->teacherId, $_POST['id_categorie'], Course::STATUS_PENDING, 0, $_POST['prix'], $_POST['type']
            );

            if (!$newCourse->add()) {
                throw new Exception("Échec de l'ajout du cours.");
            }

            $id_course = $this->dbManager->getLastInsertId();
            $this->handleCourseContent($id_course, $_POST);

            SweetAlert::setMessage('Succès', 'Le cours et son contenu ont été ajoutés avec succès', 'success', '../teacher');
        } catch (Exception $e) {
            $this->handleException($e, 'addCourse.php');
        }
    }

    public function viewAdd()
    {
        $this->view('espaceTeacher/addCourse', ['statut' => $this->statut, 'categories' => Categorie::getAll($this->dbManager)]);
    }

    public function viewUpdate($id)
    {
        if (!is_numeric($id)) {
            die("ID de cours invalide.");
        }

        try {
            $course = (new Course($this->dbManager, $id))->getById();
            if (!$course) {
                throw new Exception("Le cours avec l'ID $id n'existe pas.");
            }

            $newContent = $this->getCourseContent($course);
            $this->view('espaceTeacher/updateCourse', [
                'statut' => $this->statut, 'course' => $course, 'categories' => Categorie::getAll($this->dbManager),
                'newContent' => $newContent
            ]);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function ModifierCourse($id_course)
    {
        try {
            $course = new Course($this->dbManager, $id_course);
            $existingContent = $this->getCourseContent($course);

            $this->updateCourseData($course, $_POST);


            if (!empty($_FILES['picture']['name'])) {
                // Appelle la méthode uploadImage et stocke le résultat
                $uploadResult = uploadImage::uploadImage($_FILES['picture']);

                // Vérifie si l'upload a réussi
                if ($uploadResult['success']) {
                    // Assigne uniquement le chemin du fichier


                    $course->picture = $uploadResult['filePath']; // <-- Assurez-vous de ne prendre que le chemin

                } else {
                    // Si l'upload échoue, affiche le message d'erreur
                    SweetAlert::setMessage('Erreur', $uploadResult['message'], 'error');
                    return; // Sortie de la fonction si erreur
                }
            }


            $course->update();
            $this->updateCourseContent($existingContent, $_POST);

            SweetAlert::setMessage('Succès', 'Le cours a été mis à jour avec succès.', 'success' , '');
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    private function validateCourseData($data)
    {
        if (empty($data['title'])) throw new Exception("Le titre du cours est obligatoire.");
        if (empty($data['id_categorie'])) throw new Exception("La catégorie est obligatoire.");
    }

    private function handleCourseContent($id_course, $data)
    {
        if ($data['type'] === 'video') {
            $url = !empty($data['videoURL']) ? $data['videoURL'] : uploadVideo($_FILES['videoUpload'])['filePath'];
            $videoContent = new ContentVideo($this->dbManager);
            $videoContent->setCourseId($id_course);
            $videoContent->setTitle($data['title']);
            $videoContent->setUrl($url);
            $videoContent->setDuration((int)$data['duration']);

            if (!$videoContent->add()) throw new Exception("Échec de l'ajout du contenu vidéo.");
        } elseif ($data['type'] === 'texte') {
            if (empty($data['content'])) throw new Exception("Le contenu texte est obligatoire.");
            $textContent = new ContentText($this->dbManager);
            $textContent->setCourseId($id_course);
            $textContent->setTitle($data['title']);
            $textContent->setContent($data['content']);

            if (!$textContent->add()) throw new Exception("Échec de l'ajout du contenu texte.");
        } else {
            throw new Exception("Type de contenu invalide.");
        }
    }

    private function getCourseContent($course)
    {
        return $course->type === 'video'
            ? (new ContentVideo($this->dbManager, 0, $course->id_course))->getByIdCourse()
            : (new ContentText($this->dbManager, 0, $course->id_course))->getByIdCourse();
    }

    private function updateCourseData($course, $data)
    {
        $course->title = $data['title'];
        $course->description = $data['description'];
        $course->id_categorie = $data['id_categorie'];
        $course->prix = $data['prix'];
        $course->id_teacher = $this->teacherId;
        $course->type = $data['type'];
    }

    private function updateCourseContent($content, $data)
    {
        if ($data['type'] === 'video') {
            $content->setUrl(!empty($data['videoURL']) ? $data['videoURL'] : uploadVideo($_FILES['videoUpload'])['filePath']);
            $content->setDuration((int)$data['duration']);
        } elseif ($data['type'] === 'texte') {
            $content->setContent($data['content']);
        }
        $content->update();
    }

    private function handleException(Exception $e, $redirect = '')
    {
        error_log($e->getMessage());
        SweetAlert::setMessage('Erreur', $e->getMessage(), 'error', $redirect);
    }
}
