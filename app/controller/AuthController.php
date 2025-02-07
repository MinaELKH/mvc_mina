<?php

namespace App\controller;

use App\config\DataBaseManager;
use App\config\Session;
use App\helper\UploadImage;
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
    public function viewRegister()
    {
        $this->view('auth/register');;
    }
    public function login()
    {
        $this->view('auth/login');;
    }
    public function register()
    {

        try {
            // Récupération des données du formulaire
            $name = trim($_POST["name"] ?? '');
            $email = trim($_POST["email"] ?? '');
            $password = $_POST["password"] ?? '';
            $confirmPassword = $_POST["confirm_password"] ?? '';
            $role = $_POST["role"] ?? '';
            $avatar = $_FILES["avatar"] ?? null;

            // Validation des champs
            if (empty($name) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
                throw new Exception("Tous les champs sont obligatoires.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Adresse email invalide.");
            }

            if ($password !== $confirmPassword) {
                throw new Exception("Les mots de passe ne correspondent pas.");
            }

            if (strlen($password) < 6) {
                throw new Exception("Le mot de passe doit comporter au moins 6 caractères.");
            }

            // Gestion de l'avatar
            $avatarPath = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                // Call the uploadImage function
                $uploadResult = uploadImage::uploadImage($_FILES['avatar']); // Make sure the class name and method match the actual implementation
                if ($uploadResult['success']) {
                    $avatarPath = $uploadResult['filePath']; // Store the path of the uploaded image
                } else {
                    // Handle any error from uploadImage
                    echo "Error: " . $uploadResult['message'];
                }
            }
            // Enregistrement de l'utilisateur dans la base de données

            $userId = User::signup($name, $email, $avatarPath, $password, $role);
            echo "hello";
            var_dump($userId );
            // Stockage des données utilisateur dans la session
            Session::set('logged_in', true);
            Session::set('user', [
                'id' => $userId,
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'avatar' => $avatarPath,
            ]);

            // Redirection en fonction du rôle
            if ($role == "student") {

                header('Location: '.BASE_URL.'/home');
                exit();
            } elseif ($role == "teacher") {
                header('Location:  '.BASE_URL.'/teacher');
                exit();
            }  elseif ($role == "admin") {

                header('Location:  ../../espaceAdmin/dashboard.php');
                exit();
            }

        } catch (Exception $e) {
            $error = $e->getMessage(); // Capture l'erreur et l'affiche dans l'interface
        }
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

                header('Location: '.BASE_URL.'/home');
                exit();
            } elseif ($role == "teacher") {
                header('Location:  '.BASE_URL.'/teacher');
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