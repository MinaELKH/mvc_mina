<?php

use App\config\session;

session::start();
if (Session::isLoggedIn() && session::hasRole('teacher')) {
    // Récupérer les données de session
    $s_userId = Session::get('user')['id'];
    $s_userName = Session::get('user')['name'];
    $s_userEmail = Session::get('user')['email'];
    $s_userRole = Session::get('user')['role'];
    $s_userAvatar = Session::get('user')['avatar'];
} ;



$teacher_statut = $data['statut'];
$message = '';




?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Moderne</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- CSS Quill -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <!-- JS Quill -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <!-- Tagify CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">

    <!-- Tagify JS -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.min.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Include Alpine.js for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


</head>

<body class="bg-gray-100 font-sans">
    <!-- Navbar Moderne -->
    <nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <h2 class="text-2xl text-indigo-500 font-bold">
                        You<span class="text-yellow-500">Demy</span>
                    </h2>
                </div>
            </div>

            <!-- Menu Burger (Mobile) -->
            <div class="flex lg:hidden">
                <button
                    @click="open = !open"
                    class="text-gray-700 hover:text-gray-900 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Menu Principal -->
            <div x-data="{ open: false }" :class="{'block': open, 'hidden': !open}" class="hidden lg:flex items-center space-x-4">
                <a href="mesCourses.php"
                    class="text-gray-700 hover:bg-gray-100 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                    Mes Cours
                </a>
                <a href="teacher/viewAdd"
                    class="text-gray-700 hover:bg-gray-100 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                    Ajouter un nouveau cours
                </a>
            </div>

            <!-- Éléments de Droite -->
            <div class="flex items-center space-x-4">
                <!-- Recherche -->
                <div class="hidden md:block">
                    <div class="relative">
                        <input type="text" placeholder="Rechercher..."
                            class="bg-gray-100 text-gray-700 border-none rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        <span class="absolute right-3 top-3 text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="hidden md:block">
                    <button class="text-gray-700 hover:text-gray-900 relative">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="bg-red-500 text-white rounded-full px-2 py-1 text-xs absolute -top-2 -right-2">3</span>
                    </button>
                </div>

                <!-- Profil -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <img src="<?= isset($s_userAvatar) ? '../' . $s_userAvatar : '../../uploads/avatar_1.jpg' ?>"
                            alt="Profil" class="w-10 h-10 rounded-full mr-3">
                        <div class="hidden md:flex flex-col">
                            <span class="text-sm font-medium text-gray-700"><?= $s_userName ?></span>
                            <span class="text-xs text-yellow-500">enseignant</span>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-20 border">
                        <a href="../auth/logout.php"
                            class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>







    <main class="flex-1 bg-gray-100 p-6">



        <?php
        if ($teacher_statut->approved == 'rejected') : ?>
            <div class="min-h-screen bg-gray-100 flex items-center justify-center p-2">
                <div class="bg-white shadow-xl rounded-2xl max-w-2xl w-full p-2 space-y-6">
                    <div class="text-center">
                        <h1 class="text-3xl font-bold text-red-600 mb-4">Demande d'Adhésion Refusée</h1>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                            <p class="text-red-700 font-semibold">Votre candidature n'a pas été retenue</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <p class="text-gray-700 leading-relaxed">
                            Cher(e) <span class="font-bold text-gray-900"><?=$s_userName?></span>,
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            Nous regrettons de vous informer que votre demande d'adhésion en tant qu'enseignant(e) sur notre plateforme a été rejetée après un examen approfondi.
                        </p>
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 my-4">
                            <p class="text-yellow-700">
                                Nous vous encourageons à revoir les critères d'adhésion pour améliorer votre demande à l'avenir.
                            </p>
                        </div>
                        <p class="text-gray-600 leading-relaxed">
                            Si vous avez des questions ou souhaitez obtenir des éclaircissements concernant cette décision, n'hésitez pas à nous contacter.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            Nous vous souhaitons tout de même beaucoup de succès dans vos projets futurs.
                        </p>
                        <div class="pt-6 border-t border-gray-200">
                            <p class="text-gray-800 font-semibold">Cordialement,</p>
                            <p class="text-gray-600">L'équipe Youdemy</p>
                            <p class="text-sm text-gray-500 mt-2">
                                📧 contact@youdemy.com<br>
                                📞 +33 1 23 45 67 89
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($teacher_statut->approved == 'pending') : ?>
            <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
                <div class="bg-white shadow-xl rounded-2xl max-w-2xl w-full p-8 space-y-6">
                    <div class="text-center">
                        <h1 class="text-3xl font-bold text-yellow-600 mb-4">Demande d'Adhésion en Cours</h1>
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                            <p class="text-yellow-700 font-semibold">Votre demande est actuellement en attente d'examen</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <p class="text-gray-600 leading-relaxed">
                            Cher(e) <span class="font-bold text-gray-900"><?=$s_userName?></span>,
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            Nous vous informons que votre demande d'adhésion en tant qu'enseignant(e) est actuellement en cours d'examen. Nous vous tiendrons informé(e) dès qu'une décision sera prise.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            Nous vous remercions de votre patience et de votre compréhension.
                        </p>
                        <div class="pt-6 border-t border-gray-200">
                            <p class="text-gray-800 font-semibold">Cordialement,</p>
                            <p class="text-gray-600">L'équipe Youdemy</p>
                            <p class="text-sm text-gray-500 mt-2">
                                📧 contact@youdemy.com<br>
                                📞 +33 1 23 45 67 89
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <?php
            echo isset($content) ? $content : '<p>Bienvenue sur Votre Dashorad .</p>';
            ?>
        <?php endif; ?>

        <script>
            // Graphique de Revenus
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin'],
                    datasets: [{
                        label: 'Revenus',
                        data: [12000, 19000, 15000, 22000, 18000, 25000],
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                }
            });

            // Graphique des Utilisateurs
            const usersCtx = document.getElementById('usersChart').getContext('2d');
            new Chart(usersCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin'],
                    datasets: [{
                        label: 'Nouveaux Utilisateurs',
                        data: [500, 750, 600, 900, 800, 1000],
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    }]
                }
            });
        </script>


<!-- responsive -->

<style>
    @media (max-width: 768px) {
        /* Cache le menu principal et bascule sur un bouton burger */
        .nav-menu {
            display: none;
        }

        .burger-menu {
            display: block;
        }
    }

    @media (min-width: 768px) {
        /* Affiche le menu principal sur grand écran */
        .nav-menu {
            display: flex;
        }

        .burger-menu {
            display: none;
        }
    }
</style>

</body>

</html>