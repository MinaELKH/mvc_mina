<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';
require_once("../sweetAlert.php");
ob_start();

use config\DataBaseManager;
use classes\Teacher;

$dbManager = new DataBaseManager();

// Archive d teacher
if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["archive"])) {
    try {
        $newTeacher = new Teacher($dbManager, $_POST['id_teacher']);
        $result = $newTeacher->archived();

        if ($result) {
            setSweetAlertMessage('Succès', 'L\'enseignant a été archivé avec succès.', 'success', 'gereTeacher.php');
        } else {
            setSweetAlertMessage('Erreur', 'Aucun archivage n\'a eu lieu. veuillez contacter le superAdmin', 'error', 'gereTeacher.php');
        }
    } catch (Exception $e) {

        setSweetAlertMessage('Erreur', $e->getMessage(), 'error', 'gereTeacher.php');
    }
}

// suspension d teacher
if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["suspended"])) {
    try {
        $newTeacher = new Teacher($dbManager, $_POST['id_teacher']);
        $result = $newTeacher->suspended();

        if ($result) {
            setSweetAlertMessage('Succès', 'L\'enseignant a été suspendu avec succès.', 'success', 'gereTeacher.php');
        } else {
            setSweetAlertMessage('Erreur', 'Aucun suspension n\'a eu lieu. veuillez contacter le superAdmin', 'error', 'gereTeacher.php');
        }
    } catch (Exception $e) {

        setSweetAlertMessage('Erreur', $e->getMessage(), 'error', 'gereTeacher.php');
    }
}


// activation d etudiant
if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["activate"])) {
    try {
        $newteacher = new Teacher($dbManager, $_POST['id_teacher']);
        $result = $newteacher->activited();

        if ($result) {
            setSweetAlertMessage('Succès', 'L\'enseignant a été réactive avec succès.', 'success', 'gereteacher.php');
        } else {
            setSweetAlertMessage('Erreur', 'Aucun réactivation n\'a eu lieu. veuillez contacter le superAdmin', 'error', 'gereteacher.php');
        }
    } catch (Exception $e) {

        setSweetAlertMessage('Erreur', $e->getMessage(), 'error', 'gereteacher.php');
    }
}

// approved d teacher
if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["status"])) {
  
    try {
        $newTeacher = new Teacher($dbManager, $_POST['id_teacher']);
        $status  = $_POST["status"];
        $result = $newTeacher->approved($status);
        if ($result) {
            setSweetAlertMessage('Succès', 'L\'operation a étè effectué en succès.', 'success', '');
        } else {
            setSweetAlertMessage('Erreur', 'Aucune operation n\'a eu lieu. veuillez contacter le superAdmin', 'error', '');
        }
    } catch (Exception $e) {

        setSweetAlertMessage('Erreur', $e->getMessage(), 'error', 'gereTeacher.php');
    }
}









?>
<!-- Gestion des Enseignants -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-100 p-8">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                Gestion des Enseignants
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
                    <a href="approvedTeacher.php">
                    <button  class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-full hover:scale-105 transform transition flex items-center shadow-xl hover:shadow-2xl">
                        <i class="fas fa-plus mr-2"></i> Demandes en attente
                    </button>
                    </a>
            </div>
            </form>
        </div>

        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden p-6">
            <table class="w-full p-6" id="dataTable">
                <thead class="bg-gradient-to-r from-blue-50 to-purple-50">
                    <tr>
                        <?php
                        $headers = [
                            'Enseignant' => 'w-1/4',
                            'Cours' => 'w-1/6',
                            'Contact' => 'w-1/4',
                            'Statut' => 'w-1/6',
                            'Actions' => 'w-1/6 text-center'
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
                    if (isset($_GET["pending"])) {
                        $newTeacher = new teacher($dbManager);
                        $teacherss = $newTeacher->getAll_Pending();
                    } else {
                        $newTeacher = new teacher($dbManager);
                        $teachers = $newTeacher->getAll();
                    }
                  
                    foreach ($teachers as $teacher): 
                    
                    ?>
                    
                        <tr class="border-b border-gray-100 hover:bg-blue-50/50 transition duration-300">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="relative">
                                        <img       src="<?= isset($teacher->avatar) ? '../'.$teacher->avatar : '../uploads/avatar_1.jpg' ?>"
                                          
                                            alt="Profile"
                                            class="w-16 h-16 rounded-full object-cover border-4 bg-green-500 border-opacity-30 transform hover:scale-110 transition" />
                                        <span class="absolute bottom-0 right-0 block h-4 w-4 rounded-full bg-green-500 ring-2 ring-white"></span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900"><?= htmlspecialchars($teacher->getname_full()) ?></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    nombre de cours a voir
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    <div><?= htmlspecialchars($teacher->getEmail()) ?></div>
                                    <div class="text-xs text-gray-400">00212 68594892</div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="= $teacher->suspended ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> 
                                    px-3 py-1 rounded-full text-xs font-semibold uppercase">
                                    <?php
                                    if ($teacher->suspended == 0) {
                                        echo "<span class='text-green-500'>Actif</span>";
                                    } else {
                                        echo "<span class='text-red-500'>Suspension</span>";
                                    }
                                    ?>
                                </span>
                            </td>
                            <!-- approuve -->
                        
                            <form action="" method="post">
                                <input type="hidden" name="id_teacher" value="<?= $teacher->getid_user() ?>">
                         
                          

                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-3">
                                    <!-- View Details Button -->
                                    <button
                                        type="button"
                                        onclick="viewTeacherDetails(<?= $teacher->getid_user() ?>)"
                                        class="text-blue-500 hover:text-blue-700 transform hover:scale-125 transition"
                                        title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Suspension Button -->
                                    <?php if ($teacher->suspended == 1): ?>
                                        <button
                                            type="submit"
                                            name="activate"
                                            class="text-green-500 hover:text-green-700 transform hover:scale-125 transition"
                                            title="Réactiver l'enseignant">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    <?php else: ?>
                                        <button
                                            type="submit"
                                            name="suspended"
                                            class="text-orange-500 hover:text-orange-700 transform hover:scale-125 transition"
                                            title="Suspendre l'enseignant">
                                            <i class="fas fa-ban "></i>
                                        </button>
                                    <?php endif; ?>

                                    <!-- Archive Button -->
                                    <?php if (!$teacher->archived): ?>
                                        <button
                                            type="submit"
                                            name="archive"
                                            class="text-red-500 hover:text-red-700 transform hover:scale-125 transition"
                                            title="Archiver l'enseignant">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                </form>



















                            </td>
                        </tr>
                    <?php endforeach;
                    ?>
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
include('layout.php');
?>