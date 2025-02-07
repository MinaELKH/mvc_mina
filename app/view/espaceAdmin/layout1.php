<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';
require_once("../sweetAlert.php");

use config\session;
use classes\Role;

session::start();
if (Session::isLoggedIn() && session::hasRole('admin')) {
    // Récupérer les données de session
    $s_userId = Session::get('user')['id'];
    $s_userName = Session::get('user')['name'];
    $s_userEmail = Session::get('user')['email'];
    $s_userRole = Session::get('user')['role'];
    $s_userAvatar = Session::get('user')['avatar'];
    //  var_dump($userAvatar); 
} else {
    setSweetAlertMessage(
        'Authentification requise ⚠️',
        'Veuillez vous authentifier en tant qu admin pour  accéder a cette page.',
        'warning',
        '../auth/login.php'
    );
}
?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Language (Français) -->
    <script src="//cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json"></script>
    <!-- script data table -->
    <script src="../../src/js/dataTable.js"></script>
    <script src="../../src/js/main.js"></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="../../src/css/style.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->


        <nav class="fixed  top-0 left-0 h-screen w-16 bg-gradient-to-b from-blue-600 to-indigo-700 shadow-2xl rounded-2xl p-2 space-y-2 z-50"> <!-- Dashboard -->
            <a href="dashboard.php" class="group relative flex items-center justify-center py-3 px-4 rounded-lg transition duration-300 hover:bg-white/20 hover:scale-105">
                <div class="absolute inset-0 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <i class="fas fa-tachometer-alt text-white text-xl relative z-10 transform group-hover:rotate-12"></i>
                <span class="absolute -right-2 -top-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    3
                </span>
            </a>

            <!-- Valider Enseignant -->
            <a href="approvedTeacher.php" class="group relative flex items-center justify-center py-3 px-4 rounded-lg transition duration-300 hover:bg-white/20 hover:scale-105">
                <div class="absolute inset-0 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
                    <i class="fas fa-user-check text-white text-xl relative z-10 transform group-hover:rotate-12"></i>

                <span class="absolute -right-2 -top-2 bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    5
                </span>
            </a>

        
            <!-- Gérer Enseignant -->
            <a href="gereTeacher.php" class="group relative flex items-center justify-center py-3 px-4 rounded-lg transition duration-300 hover:bg-white/20 hover:scale-105">
                <div class="absolute inset-0 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <i class="fas fa-chalkboard-teacher text-white text-xl relative z-10 transform group-hover:rotate-12"></i>
            </a>

            <!-- Gérer Étudiants -->
            <a href="gereStudent.php" class="group relative flex items-center justify-center py-3 px-4 rounded-lg transition duration-300 hover:bg-white/20 hover:scale-105">
                <div class="absolute inset-0 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <i class="fas fa-users-cog text-white text-xl relative z-10 transform group-hover:rotate-12"></i>
                <span class="absolute -right-2 -top-2 bg-yellow-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    12
                </span>
            </a>

            <!-- cour -->
            <a href="courses.php" class="group relative flex items-center justify-center py-3 px-4 rounded-lg transition duration-300 hover:bg-white/20 hover:scale-105">
                <div class="absolute inset-0 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <i class="fas fa-laptop-code text-white text-xl relative z-10 transform group-hover:rotate-12"></i>
                <span class="absolute -right-2 -top-2 bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    10
                </span>
            </a>
            <!-- Catégories -->
            <a href="categorie.php" class="group relative flex items-center justify-center py-3 px-4 rounded-lg transition duration-300 hover:bg-white/20 hover:scale-105">
                <div class="absolute inset-0 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <i class="fas fa-layer-group text-white text-xl relative z-10 transform group-hover:rotate-12"></i>
            </a>

            <!-- Tags -->
            <a href="tags.php" class="group relative flex items-center justify-center py-3 px-4 rounded-lg transition duration-300 hover:bg-white/20 hover:scale-105">
                <div class="absolute inset-0 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <i class="fas fa-tags text-white text-xl relative z-10 transform group-hover:rotate-12"></i>
            </a>

            <!-- Statistiques -->
            <a href="static.php" class="group relative flex items-center justify-center py-3 px-4 rounded-lg transition duration-300 hover:bg-white/20 hover:scale-105">
                <div class="absolute inset-0 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <i class="fas fa-chart-pie text-white text-xl relative z-10 transform group-hover:rotate-12"></i>
            </a>

            <!-- Séparateur -->
            <div class="border-t border-white/20 my-2"></div>

            <!-- Déconnexion -->
            <a href="logout.php" class="group relative flex items-center justify-center py-3 px-4 rounded-lg transition duration-300 hover:bg-red-500/20 hover:scale-105">
                <div class="absolute inset-0 bg-white/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <i class="fas fa-sign-out-alt text-white text-xl relative z-10 transform group-hover:rotate-12 group-hover:text-red-500"></i>
            </a>
        </nav>

        <style>
            /* Animations supplémentaires */
            @keyframes pulse {
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.05);
                }

                100% {
                    transform: scale(1);
                }
            }

            .group:hover i {
                animation: pulse 0.5s ease-in-out;
            }
        </style>

        <!-- Main Content -->

        <div class="ml-16 flex-1 overflow-x-hidden">
            <!-- Top Bar -->
            <nav class="bg-white shadow-md fixed w-full z-10 top-0">
                <div class="container px-4 py-4 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        

                        <div class="flex-shrink-0">
                            <h2 class="text-2xl text-indigo-500 font-bold ">
                                You<span class="text-yellow-500">Demy</span>
                            </h2>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                    

                        <!-- la partie qui se change selon user  -->

                        <div class="flex items-center space-x-4 relative">
                            <?php if (Session::isLoggedIn()) : ?>
                                <div class="flex items-center space-x-4">
                                    <!-- Notifications -->
                                    <div class="relative">
                                        <button class="text-gray-700 hover:text-gray-900">
                                            <i class="fas fa-bell text-lg"></i>
                                            <span class="bg-red-500 text-white rounded-full px-2 py-1 text-xs absolute -top-2 -right-2">3</span>
                                        </button>
                                    </div>

                                    <!-- Profile Dropdown -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button
                                            @click="open = !open"
                                            class="flex items-center focus:outline-none">
                                            <img
                                                src="<?= isset($s_userAvatar) ? '../'.$s_userAvatar : '../../uploads/avatar_1.jpg' ?>"
                                                alt="Profil"
                                                class="w-10 h-10 rounded-full mr-3">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-700"><?= $s_userName ?></span>
                                                <span class="text-xs text-yellow-500">
                                                    Administrateur 
                                                </span>
                                            </div>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div
                                            x-show="open"
                                            @click.away="open = false"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-90"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-300"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-90"
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-20 border">
                                            
                                            <a href="../auth/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Include Alpine.js for interactivity -->
                        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>




                        <!-- fin de la partie qui se change selon user  -->
                    </div>
                </div>
            </nav>
            <!-- Main Section -->
            <main class="flex-1 bg-gray-100 p-2 mt-24">
                <?php
                echo isset($content) ? $content : '<p>Bienvenue sur Votre Dashorad .</p>';
                ?>
            </main>
        </div>
    </div>

</body>

</html>