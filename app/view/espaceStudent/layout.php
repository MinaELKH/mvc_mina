<?php
use App\helpers\SweetAlert;
use App\config\session;
if (Session::isLoggedIn() && session::hasRole('student')) {
    // Récupérer les données de session
    $s_userId = Session::get('user')['id'];
    $userName = Session::get('user')['name'];
    $userEmail = Session::get('user')['email'];
    $userRole = Session::get('user')['role'];
    $userAvatar = Session::get('user')['avatar'];
    //  var_dump($userAvatar); 
}

?>


<!DOCTYPE html>
<html lang="fr" x-data="{ mobileMenuOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
<!-- Include Alpine.js for interactivity pour dropdown profil-->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo et Menu Mobile -->
                <div class="flex items-center">
                 


                    <div class="flex-shrink-0">
                        <h2 class="text-2xl text-indigo-500 font-bold ">
                            You<span class="text-yellow-500">Demy</span>
                        </h2>
                    </div>

                    <!-- Menu Desktop -->
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4">
                        <a href="<?= BASE_URL ?>" class="text-gray-600 hover:bg-gray-100 px-3 py-2 rounded-md">Acceuil</a>
                            <a href="#" class="text-gray-600 hover:bg-gray-100 px-3 py-2 rounded-md">Mes Cours</a>
                            <a href="#" class="text-gray-600 hover:bg-gray-100 px-3 py-2 rounded-md">en Cours</a>
                            <a href="#" class="text-gray-600 hover:bg-gray-100 px-3 py-2 rounded-md">Favoris</a>
                           
                       
                        </div>
                    </div>
                </div>

                <!-- Recherche et Profil -->
                <div class="flex items-center space-x-4">
                    <div class="w-64">
                    <form action="http://localhost/youdemy/pages/searchResult.php" method="post">
                        <div class="relative">
                            <input
                                type="text"
                                name="search"
                                class="border rounded-full py-2 px-4 pl-10"
                                placeholder="Search...">
                                <input type="submit"   name="btnsearch" style="display: none;">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                        </div>
                    </form>
                   
                        </div>

                 


                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button
                            @click="open = !open"
                            class="flex items-center focus:outline-none">
                            <img
                                src="<?= isset($userAvatar) ? 'http://localhost/youdemy/pages/' . $userAvatar : 'http://localhost/youdemy/pages/uploads/avatar_1.jpg' ?>"
                                alt="Profil"
                                class="w-10 h-10 rounded-full mr-3">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-700"><?= $userName ?></span>
                                <span class="text-xs text-gray-500">
                                    <?= Session::hasRole('student') ? 'Apprenant' : 'Enseignant' ?>
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
                            <a href="<?= Session::hasRole('student') ? '../mesCourses.php' : '../dashboard.php' ?>"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                <i class="fas fa-chart-line mr-2"></i> Tableau de Bord
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                <i class="fas fa-user mr-2"></i> Profil
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                <i class="fas fa-cog mr-2"></i> Paramètres
                            </a>
                            <div class="border-t my-1"></div>
                            <a href="Auth/deconnexion" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                            </a>
                        </div>
                    </div>


                    <!-- Bouton Menu Mobile -->
                    <div class="md:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="text-gray-600 hover:text-gray-800">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    x-show="!mobileMenuOpen"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    x-show="mobileMenuOpen"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div
            x-show="mobileMenuOpen"
            class="md:hidden absolute w-full bg-white shadow-lg"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="mesCourses.php" class="text-gray-600 hover:bg-gray-100 block px-3 py-2 rounded-md">Mes Cours</a>

                <a href="detailCourStudent.php?id_course=33" class="text-gray-600 hover:bg-gray-100 block px-3 py-2 rounded-md">Recommandés</a>
                <a href="#" class="text-gray-600 hover:bg-gray-100 block px-3 py-2 rounded-md">Favoris</a>
            </div>
        </div>
    </nav>

    <!-- Contenu Principal -->
    <main class="pt-24 px-4 sm:px-8 lg:px-12 max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Mes Apprentissages</h1>
            <p class="text-gray-600 text-sm">Continuez votre progression et développez vos compétences</p>
        </div>

        <?php
        echo isset($content) ? $content : '<p>Bienvenue sur Votre Dashorad .</p>';
        ?>

    </main>



    </div>
</body>

</html>