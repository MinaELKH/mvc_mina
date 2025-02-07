<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';
require_once("../sweetAlert.php");
ob_start();

use config\DataBaseManager;
use classes\Course;

$dbManager = new DataBaseManager();

// Archive d course
if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["archive"])) {
    try {
        $newcourse = new Course($dbManager, $_POST['id_course']);
        $result = $newcourse->archive();

        if ($result) {
            setSweetAlertMessage('Succès', 'Le cour a été archivé avec succès.', 'success', 'gerecourse.php');
        } else {
            setSweetAlertMessage('Erreur', 'Aucun archivage n\'a eu lieu. veuillez contacter le superAdmin', 'error', 'gerecourse.php');
        }
    } catch (Exception $e) {

        setSweetAlertMessage('Erreur', $e->getMessage(), 'error', 'courses.php');
    }
}



// approved d course
if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["status"])) {
    //    var_dump($_POST);
    //    die();
    try {
        $newcourse = new course($dbManager, $_POST['id_course']);
        $status  = $_POST["status"];
        $result = $newcourse->approve($status);
        if ($result) {
            setSweetAlertMessage('Succès', 'L\'operation a étè effectué en succès.', 'success', '');
        } else {
            setSweetAlertMessage('Erreur', 'Aucune operation n\'a eu lieu. veuillez contacter le superAdmin', 'error', '');
        }
    } catch (Exception $e) {
        var_dump($e->getMessage());
        die();
        setSweetAlertMessage('Erreur', $e->getMessage(), 'error', 'course.php');
    }
}









?>
<!-- Gestion des Enseignants -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-100 p-8">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                Gestion des Cours
            </h1>

            <div class="flex space-x-4">
                <div class="relative">
                    <input
                        name="search"
                        type="text"
                        placeholder="Rechercher..."
                        class="pl-10 pr-4 py-3 w-72 bg-white/80 backdrop-blur-sm rounded-full border-2 border-blue-100 focus:border-blue-300 transition duration-300 ease-in-out shadow-lg">
                    <i class="fas fa-search absolute left-4 top-4 text-blue-400"></i>
                </div>
                <form method="get" action="">
                    <button value="1" name="pending" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-full hover:scale-105 transform transition flex items-center shadow-xl hover:shadow-2xl">
                        <i class="fas fa-plus mr-2"></i> Demandes en attente
                    </button>
                </form>
            </div>

        </div>

        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden p-6">
            <table class="w-full p-6" id="dataTable">
                <thead class="bg-gradient-to-r from-blue-50 to-purple-50">
                    <tr>
                        <?php
                        $headers = [
                            'Titre' => 'w-1/6',
                            'Catégorie' => 'w-1/6 ',
                            'Info' => 'w-1/6 ',
                            'Enseignant' => 'w-1/6 ',
                            'Description' => 'w-1/6',
                            'statut' => 'w-1/6'
                        ];

                        foreach ($headers as $header => $width): ?>
                            <th class="<?= $width ?> px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">
                                <?= $header ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $newcourse = new Course($dbManager);
                    $courses = Course:: getAllCourseAdmin($dbManager  );
                    //  echo '<pre>';
                    //  print_r($courses);  // Affiche les données sous un format lisible
                    //  echo '</pre>';
                    foreach ($courses as $course):
                    ?>
                        <tr class="border-b border-gray-100 hover:bg-blue-50/50 transition duration-300 text-sm  ">
                            <!-- Colonne Titre -->
                            <td class="px-4 py-2">
                                <div class="flex items-center space-x-2">
                                    <div class="relative">
                                        <img
                                            src="<?= htmlspecialchars($course->picture ?? 'default-avatar.png') ?>"
                                            alt="Course Image"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-opacity-30" />
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 truncate max-w-[150px]">
                                            <?= htmlspecialchars($course->title) ?>
                                        </div>
                                        <div class="text-xs text-gray-500 truncate max-w-[150px]">
                                            <span class="flex items-center text-gray-600">
                                                <i class="fas fa-calendar mr-1 text-purple-500 text-sm"></i>
                                                <?= date('d/m/Y', strtotime($course->created_at)) ?? 'N/A' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Colonne Catégorie -->
                            <td class="px-4 py-2 text-center">
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                                    <?= htmlspecialchars($course->category_name) ?>
                                </span>
                            </td>

                            <!-- Colonne Informations -->
                            <td class="px-4 py-2  text-center">
                                <div class="flex items-center space-x-2 text-xs">
                                    <span class="flex items-center text-gray-600">
                                        <i class="fas fa-comment mr-1 text-blue-500 text-sm"></i>
                                        <?= htmlspecialchars($course->review_count) ?>
                                    </span>
                                    <span class="flex items-center text-gray-600">
                                        <i class="fas fa-users mr-1 text-green-500 text-sm"></i>
                                        <?= htmlspecialchars($course->student_count) ?>
                                    </span>
                                </div>
                            </td>

                            <!-- Colonne teacher -->
                            <td class="px-4 py-2 text-center">
                                <div class="flex justify-center space-x-2">
                                    <div class="text-xs text-gray-500 truncate max-w-[150px]">
                                        <?= htmlspecialchars($course->teacher_name) ?>
                                        <br>
                                        <?= htmlspecialchars($course->email) ?>
                                    </div>



                                </div>
                            </td>
                            <!-- Colonne Description -->
                            <td class="px-4 py-2">
                                <div class="text-xs text-gray-500 truncate max-w-[200px]">
                                    <?= substr(htmlspecialchars($course->description), 0, 20) . '...' ?>
                                </div>
                            </td>

                            <!-- Colonne Statut -->
                            <td class="px-4 py-2">
                                <form action="" method="post">
                                    <input type="hidden" name="id_course" value="<?= $course->id_course ?>">
                                    <div class="inline-flex space-x-2">
                                        <button type="submit" name="status" value="pending" class="
                                            <?= $course->status == 'pending' ? 'text-yellow-500' : 'text-gray-300' ?> 
                                            hover:text-yellow-600 transition-colors duration-200">
                                            <i class="fas fa-clock"></i>
                                        </button>

                                        <button type="submit" name="status" value="approved" class="
                                            <?= $course->status == 'approved' ? 'text-green-500' : 'text-gray-300' ?> 
                                            hover:text-green-600 transition-colors duration-200">
                                            <i class="fas fa-check-circle"></i>
                                        </button>

                                        <button type="submit" name="status" value="rejected" class="
                                        <?= $course->status == 'rejected' ? 'text-red-500' : 'text-gray-300' ?> 
                                        hover:text-red-600 transition-colors duration-200">
                                            <i class="fas fa-times-circle"></i>
                                        </button>

                                        <button
                                            onclick="viewcourseDetails(<?= $course->id_course ?>)"
                                            class="text-blue-500 hover:text-blue-700 text-sm ">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                    </div>
                                </form>
                            </td>



                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<style>
    /* Animations et effets supplémentaires */
    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    .animate-float {
        animation: float 4s ease-in-out infinite;
    }
</style>

<script>
    // Interactions dynamiques
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.classList.add('transform', 'scale-[1.02]', 'shadow-lg');
        });

        row.addEventListener('mouseleave', function() {
            this.classList.remove('transform', 'scale-[1.02]', 'shadow-lg');
        });
    });
</script>




<?php
$content = ob_get_clean();
include('layout1.php');
?>