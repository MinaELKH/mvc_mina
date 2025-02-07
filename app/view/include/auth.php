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
                    src="<?= isset($userAvatar) ? $userAvatar : 'uploads/avatar_1.jpg' ?>"
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
                <a href="<?= Session::hasRole('student') ? 'Courses' : 'espaceTeacher/dashboard' ?>"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                    <i class="fas fa-chart-line mr-2"></i> Tableau de Bord
                </a>
                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                    <i class="fas fa-user mr-2"></i> Profil
                </a>

                <div class="border-t my-1"></div>
                <a href="Auth/deconnexion" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>