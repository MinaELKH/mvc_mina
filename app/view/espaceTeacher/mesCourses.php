<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';
require_once("../sweetAlert.php");
ob_start();

use classes\Course;
use classes\CourseTags;
use classes\Review;
use config\DataBaseManager;
use classes\ContentText;
use classes\ContentVideo;
use classes\Teacher;
use config\session;

session::start();
if (Session::isLoggedIn() && session::hasRole('teacher')) {
    // Récupérer les données de session
    $s_userId = Session::get('user')['id'];
    $s_userName = Session::get('user')['name'];
    $s_userEmail = Session::get('user')['email'];
    $s_userRole = Session::get('user')['role'];
    $s_userAvatar = Session::get('user')['avatar'];
} else {
    setSweetAlertMessage(
        'Authentification requise ⚠️',
        'Veuillez vous authentifier en tant qu enseignant pour  accéder a cette page.',
        'warning',
        '../auth/login.php'
    );
}
// affichage 
try {
    $dbManager = new DataBaseManager();
    $teacherId =  $s_userId;
    $newTeacher = new Teacher($dbManager, $teacherId);


    // recuperation du courses 


    $courses = $newTeacher->getMyCourses();



    //statistique 

        $countCourses = $newTeacher->getCountCoursesByTeacher();
        $countInscrits = $newTeacher->getCountInscritByTeacher();
        $ca = $newTeacher->getCAByTeacher();
        $topStudents = $newTeacher->getTopStudentbyTeacher();



} catch (Exception $e) {
    // Log error and redirect or show error message
    error_log($e->getMessage());
    $courses = [];
}
// Archivage d'un cours
if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["archive"])) {
    try {
        $course = new Course($dbManager, $_POST['id_course']);
        $result = $course->archive();

        if ($result) {
            setSweetAlertMessage('Succès', 'Le cours a été archivé avec succès.', 'success', '');
        } else {
            setSweetAlertMessage('Erreur', 'Aucun archivage n\'a eu lieu. Veuillez contacter l\'administrateur.', 'error', '');
        }
    } catch (Exception $e) {
        setSweetAlertMessage('Erreur', $e->getMessage(), 'error', '');
    }
}



?>

<section class="container mx-auto px-4 lg:px-24">

    <!-- statistqique  -->

    <!-- Conteneur des widgets -->
    <div class="grid grid-cols-4 gap-4">
        <!-- Widget Nombre de Cours -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center">
            <div class="bg-blue-100 text-blue-600 rounded-full p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm">Nombre de Cours</h3>
                <p class="text-xl font-bold text-gray-800"><?= $countCourses ?></p>
            </div>
        </div>

        <!-- Widget Nombre d'Inscrits -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center">
            <div class="bg-green-100 text-green-600 rounded-full p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm">Total Inscrits</h3>
                <p class="text-xl font-bold text-gray-800"><?= $countInscrits ?></p>
            </div>
        </div>

        <!-- Widget Chiffre d'Affaires -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center">
            <div class="bg-purple-100 text-purple-600 rounded-full p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm">Chiffre d'Affaires</h3>
                <p class="text-xl font-bold text-gray-800"><?= number_format($ca, 0, ',', ' ') ?> €</p>
            </div>
        </div>

        <!-- Widget Étudiants Fidèles -->
        <div class="bg-white shadow-md rounded-lg p-4 flex items-center">
            <div class="bg-red-100 text-red-600 rounded-full p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm">Top Étudiant</h3>
                <p class="text-sm font-bold text-gray-800"><?= $topStudents ?></p>
            </div>
        </div>
    </div>






    <!-- mes cours -->
    <div class="flex justify-between items-center my-6">
        <h2 class="text-xl font-semibold text-gray-700">Mes Cours en Cours</h2>
        <a href="/courses" class="text-sm text-blue-600 hover:underline">Voir tous les cours</a>
    </div>

    <?php if (empty($courses)): ?>
        <div class="text-center py-10 bg-gray-100 rounded-lg">
            <p class="text-gray-500">Vous n'avez pas encore de cours en cours.</p>
            <a href="/create-course" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Créer un nouveau cours
            </a>
        </div>
    <?php else: ?>
        <!-- Grille des cours -->


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            <?php foreach ($courses as $course): ?>

                <div class="bg-white rounded-md shadow-sm overflow-hidden relative group">
                    <a href="detailCourse.php?id_course=<?= htmlspecialchars($course->id_course); ?>" class="block">
                        <div class="relative">
                            <!-- Image du cours -->
                            <img
                                alt="<?= htmlspecialchars($course->title); ?>"
                                class="w-full h-32 object-cover"
                                src="<?php echo '../' . $course->picture ?: 'https://via.placeholder.com/300x200.png?text=Image+Non+Disponible'; ?>" />
                            <!-- Icône de favoris -->
                            <i class="fas fa-heart text-white absolute top-2 right-2 bg-yellow-500 p-1 rounded-full"></i>
                        </div>
                        <div class="p-3">
                            <div class="flex items-center mb-2">
                                <!-- Avatar de l'auteur -->
                                <img
                                    alt="Profile picture of <?= htmlspecialchars($course->teacher_name); ?>"
                                    class="w-6 h-6 rounded-full mr-2"
                                    src="<?php echo '../' . ($course->avatar ?: 'https://via.placeholder.com/100.png?text=Avatar'); ?>" />
                                <span class="font-semibold">
                                    <?= htmlspecialchars($course->teacher_name); ?>
                                </span>
                            </div>
                            <!-- Titre du cours -->
                            <h2 class="text-sm font-semibold">
                                <?= htmlspecialchars($course->title); ?>
                            </h2>
                            <!-- Informations supplémentaires -->
                            <div class="flex  justify-between items-center text-xs text-gray-600 mt-2">
                                <span class="mr-2">
                                    <?= htmlspecialchars($course->category_name); ?>
                                </span>
                                <span class="mr-2 flex items-center">
                                    <i class="fas fa-user mr-1"></i>
                                    <?= htmlspecialchars($course->student_count); ?>
                                </span>
                                <span class="mr-2 flex items-center">
                                    <i class="fas fa-comment mr-1"></i>
                                    <?= htmlspecialchars($course->review_count); ?>
                                </span>
                                <i class="fas fa-star text-yellow-500 mr-2"></i>
                                <span>
                                    <!-- <?= htmlspecialchars($course->rating); ?> -->
                                </span>
                            </div>
                            <!-- Tags -->
                            <div class="flex space-x-2 mt-2">
                                <?php
                                $newtag = new CourseTags($dbManager, $course->id_course);
                                $result = $newtag->getTagsBycourse();

                                // Couleur 
                                $colors = ['bg-blue-100', 'bg-green-100', 'bg-red-100', 'bg-yellow-100', 'bg-purple-100', 'bg-pink-100', 'bg-indigo-100', 'bg-teal-100', 'bg-orange-100', 'bg-gray-100'];

                                foreach ($result as $index => $Objet_tag) {
                                    // Sélectionner une couleur en fonction de l'index du tag
                                    $colorClass = $colors[$index % count($colors)];
                                    echo '<span class="' . $colorClass . '  text-xs text-gray-700 px-1 py-1 rounded-full">' . $Objet_tag->name_tag . '</span>';
                                }
                                ?>
                            </div>


                            <!-- Prix et bouton -->

                        </div>
                    </a>

                    <div class="absolute top-2 right-2 flex space-x-1 opacity-100 group-hover:opacity-50 transition-opacity">
                        <!-- Icône d'archivage -->
                        <form action="" method="post" class="inline-block">
                            <input type="hidden" name="id_course" value="<?= htmlspecialchars($course->id_course); ?>">
                            <button name="archive" class="bg-gray-200/50 rounded-full p-1 hover:bg-gray-300/70">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </button>
                        </form>

                        <!-- Icône de modification -->
                        <a href="updateCourse.php?id_course=<?= htmlspecialchars($course->id_course); ?>"
                            class="bg-gray-200/50 rounded-full p-1 hover:bg-gray-300/70">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>


    <?php endif; ?>


    <!-- Version responsive pour mobile -->
    <style>
        @media (max-width: 640px) {
            .grid-cols-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 380px) {
            .grid-cols-4 {
                grid-template-columns: 1fr;
            }
        }
    </style>



</section>

<?php
$content = ob_get_clean();
include('layout.php');
?>