<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';
require_once("../sweetAlert.php");
use config\session;
ob_start();
session::start();
if (Session::isLoggedIn() && session::hasRole('admin')) {
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

use config\DataBaseManager;
use classes\Teacher;

$dbManager = new DataBaseManager();

// approved d teacher
if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["status"])) {
    //  
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
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-100 p-2">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                Les demandes en attente
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

            </div>

        </div>

        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden p-6">
            <table class="w-full p-6" id="dataTable">
                <thead class="bg-gradient-to-r from-blue-50 to-purple-50">
                    <tr>
                        <?php
                        $headers = [
                            'Enseignant' => 'w-1/4',
                            'Contact' => 'w-1/4',
                            'Document' => 'w-1/6',
                            'Approuve' => 'w-1/6',
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
                    $newTeacher = new teacher($dbManager);
                    $teachers = $newTeacher->getAll_Pending();
                    //  echo"<pre>" ;
                    //  var_dump($teachers);
                    //  echo"<pre>" ;
                    //  die();
                    foreach ($teachers as $teacher): ?>
                        <tr class="border-b border-gray-100 hover:bg-blue-50/50 transition duration-300">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="relative">
                                        <img
                                            src="<?= htmlspecialchars($teacher->getAvatar() ?? 'default-avatar.png') ?>"
                                            alt="Profile"
                                            class="w-16 h-16 rounded-full object-cover border-4 bg-green-500 border-opacity-30 transform hover:scale-110 transition" />
                                        <span class="absolute bottom-0 right-0 block h-4 w-4 rounded-full bg-green-500 ring-2 ring-white"></span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900"><?= htmlspecialchars($teacher->getname_full()) ?></div>
                                    </div>
                                </div>
                            </td>


                            <td class="px-6 py-4 text-center">
                                <div class="text-sm text-gray-600 ">
                                    <div><?= htmlspecialchars($teacher->getEmail()) ?></div>
                                    <div class="text-xs text-gray-400">00212 68594892</div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="#" title="Voir le CV" class="text-blue-500 hover:text-blue-700 transition-colors duration-300 transform hover:scale-110">
                                        <i class="fas fa-file-pdf text-2xl"></i>
                                    </a>
                                    <a href="#" title="Diplômes" class="text-green-500 hover:text-green-700 transition-colors duration-300 transform hover:scale-110">
                                        <i class="fas fa-graduation-cap text-2xl"></i>
                                    </a>
                                </div>
                            </td>
                            <!-- approuve -->
                            <td class="px-2 py-1 text-center">
                       
                                <form action="" method="post">
                                <input type="hidden" name="id_teacher" value="<?= $teacher->id_user ?>">
                                    <div class="inline-flex space-x-1">
                                

                                        <button type="submit" name="status" value="approved" class="
                                          text-green-500 
                                            hover:text-green-600 transition-colors duration-200">
                                            <i class="fas fa-check-circle"></i>
                                        </button>

                                        <button type="submit" name="status" value="rejected" class="
                                            text-red-500
                                            hover:text-red-600 transition-colors duration-200">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
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