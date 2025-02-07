<?php
use App\config\Session;
$error = ''; // Initialize error message variable

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Youdemy</title>
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

    <!-- Login Container -->
    <div class="relative w-full max-w-md px-6 py-8 m-4">
        <!-- Logo Section -->
     <div class="text-center mb-8">
     <a href='../home.php'>
            <h1 class="text-4xl font-bold text-white mb-2">You<span class="text-yellow-500">Demy</span></h1>
            <p class="text-gray-200">Votre plateforme d'apprentissage en ligne</p>
        
    </a>
        </div>

        <!-- Login Form -->
        <div class="bg-white bg-opacity-20 p-8 rounded-2xl shadow-xl bg-blur">
            <h2 class="text-2xl font-semibold text-white mb-6">Connexion</h2>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6" action="login/authentification">
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-white text-sm font-medium mb-2">Email</label>
                    <input type="email"
                        id="email"
                        name="email"
                        required
                        class="w-full px-4 py-3 rounded-lg bg-white bg-opacity-20 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-white placeholder-gray-300"
                        placeholder="votreemail@exemple.com">
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-white text-sm font-medium mb-2">Mot de passe</label>
                    <div class="relative">
                        <input type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full px-4 py-3 rounded-lg bg-white bg-opacity-20 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-white placeholder-gray-300"
                            placeholder="••••••••">
                        <button type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox"
                            id="remember"
                            name="remember"
                            class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-white">
                            Se souvenir de moi
                        </label>
                    </div>
                    <a href="#" class="text-sm text-blue-300 hover:text-blue-400">
                        Mot de passe oublié ?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit"  name="login"
                    class="w-full px-4 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition duration-200">
                    Se connecter
                </button>

                <!-- Divider -->
                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-4 bg-transparent text-white text-sm">Ou continuez avec</span>
                    </div>
                </div>

                <!-- Social Login -->
                <div class="grid grid-cols-2 gap-4">
                    <button type="button"
                        class="px-4 py-3 rounded-lg bg-white bg-opacity-20 hover:bg-opacity-30 flex items-center justify-center transition duration-200">
                        <svg class="h-5 w-5 text-white mr-2" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.164 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.604-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.295 2.747-1.026 2.747-1.026.546 1.377.202 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.161 22 16.418 22 12c0-5.523-4.477-10-10-10z" />
                        </svg>
                        GitHub
                    </button>
                    <button type="button"
                        class="px-4 py-3 rounded-lg bg-white bg-opacity-20 hover:bg-opacity-30 flex items-center justify-center transition duration-200">
                        <svg class="h-5 w-5 text-white mr-2" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5.36 14.3c-.24.7-.71 1.2-1.21 1.63-.5.43-1.03.76-1.56 1.03-.53.27-1.07.47-1.63.62-.56.15-1.12.23-1.69.23-.57 0-1.13-.08-1.69-.23-.56-.15-1.1-.35-1.63-.62-.53-.27-1.06-.6-1.56-1.03-.5-.43-.97-.93-1.21-1.63-.24-.7-.36-1.47-.36-2.3 0-.83.12-1.6.36-2.3.24-.7.71-1.2 1.21-1.63.5-.43 1.03-.76 1.56-1.03.53-.27 1.07-.47 1.63-.62.56-.15 1.12-.23 1.69-.23.57 0 1.13.08 1.69.23.56.15 1.1.35 1.63.62.53.27 1.06.6 1.56 1.03.5.43.97.93 1.21 1.63.24.7.36 1.47.36 2.3 0 .83-.12 1.6-.36 2.3z" />
                        </svg>
                        Google
                    </button>
                </div>
            </form>

            <!-- Sign Up Link -->
            <p class="mt-8 text-center text-sm text-white">
                Pas encore de compte ?
                <a href="register.php" class="font-semibold text-blue-300 hover:text-blue-400">
                    Inscrivez-vous gratuitement
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