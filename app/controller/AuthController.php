<?php

namespace App\controller;

use App\config\DataBaseManager;
use App\config\Session;
use App\helpers\SweetAlert;
use Exception;
use App\core\Controller;


use App\model\Course;
use App\model\Categorie;
use App\model\User ;
class AuthController extends Controller
{
    public function index(){
        $this->view('auth/login'); ;
    }
    public function register()
    {
        $this->view('auth/register');;
    }
    public function login()
    {
        $this->view('auth/login');;
    }

    public function authentification(){

        try {
            session::start();
            $email = trim($_POST["email"] ?? '');
            $password = $_POST["password"] ?? '';

            if (empty($email) || empty($password)) {
                throw new Exception("Tous les champs sont obligatoires.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Adresse email invalide.");
            }


            $user = User::signin($email, $password);

            if (empty($user)) {
                throw new Exception("Inscription échouée.");
            }

            // Si l'utilisateur est trouvé, création de la session
            Session::set('logged_in', true);
            Session::set('user', [
                'id' => $user->getid_user(),
                'name' => $user->getname_full(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'avatar' => $user->getAvatar(),
            ]);
             $role = $user->getRole();
            if ($role == "student") {
                header("Location: ../../home");
                exit();
            } elseif ($role == "teacher") {
                header('Location:  ../../espaceTeacher/mesCourses.php');
                exit();
            }  elseif ($role == "admin") {

                header('Location:  ../../espaceAdmin/dashboard.php');
                exit();
            }


        } catch (Exception $e) {
            $error = $e->getMessage();
            SweetAlert::setMessage('Erreur', $e->getMessage(), 'error', '');
        }
    }



    public function deconnexion(){

        Session::destroy();
        header("Location: ../home");
        exit();

    }
}