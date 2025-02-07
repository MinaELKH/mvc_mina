<?php

namespace App\controller;

use App\config\DataBaseManager;
use App\core\Controller;
use App\model\Course;
use config\Session;
use Exception;
use App\model\Categorie;

class RegisterController extends Controller
{

    public function index()
    {
        $this->view('auth/register');;
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
                $uploadResult = uploadImage($avatar);
                $avatarPath = $uploadResult['filePath'];
                // Enregistrement de l'utilisateur dans la base de données
                $userId = User::signup($name, $email, $avatarPath, $password, $role);
                var_dump($userId);
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
                session::gotoLocation($role);

            } catch (Exception $e) {
                $error = $e->getMessage(); // Capture l'erreur et l'affiche dans l'interface
            }
        }


}