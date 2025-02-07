<?php

namespace App\controller;

use App\core\Controller;
use App\config\DataBaseManager;
use App\model\Categorie;

class CategorieController extends Controller
{
    private $dbManager; // Attribut de classe

    public function __construct()
    {
        $this->dbManager = new DataBaseManager(); // Initialisation une seule fois
    }

    public function index() {
        $categories = $this->dbManager->selectAll('categories');
        $this->view('epaceAdmin/categorie', ['categories' => $categories]);
    }

    public function affiche($arg) {
        $newCategorie = new Categorie($this->dbManager, $arg);
        $data = ['id_categorie' => $arg];
        $result = $this->dbManager->selectBy('categories', $data);
        $this->view('epaceAdmin/categorie', $arg);
    }

    public function Ajouter() {
        $newCategorie = new Categorie($this->dbManager, 0, $_POST['name'], $_POST['description']);
        $result = $newCategorie->add();
        $this->view('epaceAdmin/categorie', $result);
    }

    public function Archive() {
        try {
            $newCategorie = new Categorie($this->dbManager, $_POST['id_categorie']);
            $result = $newCategorie->archived();
            if ($result) {
                setSweetAlertMessage('Succès', 'Categorie archivée avec succès.', 'success', 'categorie.php');
            } else {
                setSweetAlertMessage('Erreur', 'Aucun archivage n\'a eu lieu. Veuillez contacter le superAdmin', 'error', 'categorie.php');
            }
        } catch (Exception $e) {
            setSweetAlertMessage('Erreur', $e->getMessage(), 'error', 'categorie.php');
        }
    }
}
