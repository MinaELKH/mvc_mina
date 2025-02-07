<?php
namespace App\controller;
use App\config\DataBaseManager;
use App\core\Controller;
use App\model\Course;
use Exception;
use App\model\Categorie;
class HomeController extends Controller
{
    public function index(){
        try {
            $dbManager = new DataBaseManager();
            // Pagination settings
            $perPage = 6; // Number of courses per page
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Current page (default is 1)

            // Fetch courses for the current page
            $courses = Course::showInCatalogue( $dbManager, $page, $perPage);

            // Fetch total number of courses
            $totalCourses = Course::countCourses( $dbManager);
            $totalPages = ceil($totalCourses / $perPage); // Total number of pages
            $categories = Categorie::getAll($dbManager);
            $this->view('home', ['categories' => $categories  , 'courses' => $courses, 'totalPages' => $totalPages, 'page' => $page]); ;

} catch (Exception $e) {
    error_log($e->getMessage());
    $courses = [];
}
    }
}