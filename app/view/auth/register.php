



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - YourUdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-blur {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat"
      style="background-image: url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
    
    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <!-- Signup Container -->
    <div class="relative w-full max-w-md px-6 py-4 m-4">
        <!-- Logo Section -->
        <div class="text-center mb-4">
            <h1 class="text-3xl font-bold text-white mb-1">YourUdemy</h1>
            <p class="text-gray-200 text-sm">Votre plateforme d'apprentissage en ligne</p>
        </div>

        <!-- Signup Form -->
        <div class="bg-white bg-opacity-20 p-6 rounded-lg shadow-xl bg-blur">
            <h2 class="text-xl font-semibold text-white mb-4">Inscription</h2>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded relative mb-3">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register/register" enctype="multipart/form-data" class="space-y-4">
                <!-- Name Input -->
                <div>
                    <label for="name" class="block text-white text-sm font-medium mb-1">Nom complet</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           required 
                           class="w-full px-3 py-2 rounded-lg bg-white bg-opacity-20 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-white placeholder-gray-300"
                           placeholder="Votre nom complet">
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-white text-sm font-medium mb-1">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           class="w-full px-3 py-2 rounded-lg bg-white bg-opacity-20 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-white placeholder-gray-300"
                           placeholder="votreemail@exemple.com">
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-white text-sm font-medium mb-1">Mot de passe</label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               class="w-full px-3 py-2 rounded-lg bg-white bg-opacity-20 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-white placeholder-gray-300"
                               placeholder="••••••••">
                        <button type="button" 
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <label for="confirm_password" class="block text-white text-sm font-medium mb-1">Confirmer le mot de passe</label>
                    <input type="password" 
                           id="confirm_password" 
                           name="confirm_password" 
                           required 
                           class="w-full px-3 py-2 rounded-lg bg-white bg-opacity-20 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-white placeholder-gray-300"
                           placeholder="••••••••">
                </div>

                <!-- Role Selection -->
                <div>
                    <p class="block text-white text-sm font-medium mb-1">Vous êtes :</p>
                    <div class="flex items-center space-x-3">
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="role" 
                                   value="student" 
                                   required 
                                   class="form-radio text-blue-500 focus:ring-blue-500">
                            <span class="ml-1 text-white">Apprenant</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="role" 
                                   value="teacher" 
                                   required 
                                   class="form-radio text-blue-500 focus:ring-blue-500">
                            <span class="ml-1 text-white">Enseignant</span>
                        </label>
                    </div>
                </div>

                <!-- Avatar Upload (Optional) -->
                <div>
                    <label for="avatar" class="block text-white text-sm font-medium mb-1">Avatar (facultatif)</label>
                    <input type="file" 
                           id="avatar" 
                           name="avatar" 
                           accept="image/*"
                           class="w-full px-3 py-2 rounded-lg bg-white bg-opacity-20 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-white placeholder-gray-300">
                </div>

                <!-- Signup Button -->
                <button type="submit"   name="signup"
                        class="w-full px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition duration-200">
                    S'inscrire
                </button>
            </form>

            <!-- Login Link -->
            <p class="mt-6 text-center text-sm text-white">
                Vous avez déjà un compte ? 
                <a href="login.php" class="font-semibold text-blue-300 hover:text-blue-400">
                    Connectez-vous
                </a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            password.type = password.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
