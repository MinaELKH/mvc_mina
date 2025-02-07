<?php

namespace App\controller;

use App\config\Session;
use App\core\Controller;
use App\config\DataBaseManager;
use App\helper\SweetAlert;
use App\model\Categorie;

class CategorieController extends Controller
{
    private $dbManager; // Attribut de classe

    public function __construct()
    {

        if (Session::isLoggedIn() && session::hasRole('admin')) {
            // Récupérer les données de session
            $s_userId = Session::get('user')['id'];
            $s_userName = Session::get('user')['name'];
            $s_userEmail = Session::get('user')['email'];
            $s_userRole = Session::get('user')['role'];
            $s_userAvatar = Session::get('user')['avatar'];
            $this->dbManager = new DataBaseManager();
        } else {
            SweetAlert::setMessage(
                'Authentification requise ⚠️',
                'Veuillez vous authentifier en tant qu admin pour  accéder a cette page.',
                'warning',
                '../auth/login'
            );
        }

         // Initialisation une seule fois
    }

    public function index() {
        $categories = $this->dbManager->selectAll('categories');
        $this->view('espaceAdmin/categorie', ['categories' => $categories]);
    }

    public function affiche($arg) {
        $newCategorie = new Categorie($this->dbManager, $arg);
        $data = ['id_categorie' => $arg];
        $result = $this->dbManager->selectBy('categories', $data);
        if ($result) {
            header("Location: " . BASE_URL . "/categorie");
            exit; // IMPORTANT : Arrête le script après la redirection
        } else {
            $this->view('../espaceAdmin/categorie', $result);
        }
    }

    public function Ajouter() {
        $newCategorie = new Categorie($this->dbManager, 0, $_POST['name'], $_POST['description']);
        $result = $newCategorie->add();
        if ($result) {
            header("Location: " . BASE_URL . "/categorie");
            exit; // IMPORTANT : Arrête le script après la redirection
        } else {
            $this->view('../espaceAdmin/categorie', $result);
        }
    }

    public function Archive() {
        try {
            $newCategorie = new Categorie($this->dbManager, $_POST['id_categorie']);
            $result = $newCategorie->archived();

            if ($result) {
                SweetAlert::setMessage('Succès', 'Catégorie archivée avec succès.', 'success', BASE_URL . "/categorie");
            } else {
                SweetAlert::setMessage('Erreur', 'Aucun archivage n\'a eu lieu. Veuillez contacter le superAdmin', 'error', BASE_URL . "/categorie");
            }
        } catch (Exception $e) {
            SweetAlert::setMessage('Erreur', $e->getMessage(), 'error', BASE_URL . "/categorie");
        }
    }

}
