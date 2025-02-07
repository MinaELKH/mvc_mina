
<?php
ob_start();

use App\config\Session;

if (Session::isLoggedIn()) {
    // Récupérer les données de session
    $s_userId = Session::get('user')['id'];
    $userName = Session::get('user')['name'];
    $userEmail = Session::get('user')['email'];
    $userRole = Session::get('user')['role'];
    $userAvatar = Session::get('user')['avatar'];
}
?>

    <section class="lg:mx-32">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Cours en Cours</h2>
            <a href="#" class="text-sm text-blue-600 hover:underline">Voir tout</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php 
            $courses = $data['courses'];
            foreach ($courses as $course): ?>
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                    <div class="relative">
                    <img
                                alt="<?= htmlspecialchars($course->title); ?>"
                                class="w-full h-32 object-cover"
                                src="<?php echo  '../'.$course->picture ?: 'https://storage.googleapis.com/a1aa/image/noC8xPRPcuBWd_C1xQmYNlvvgBKWFaAuuJPWmdQmvPM.jpg'; ?>" />
                            
                        <div class="absolute top-2 right-2 bg-blue-500 text-white px-1.5 py-0.5 rounded-full text-[10px]">
                            En cours
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="flex justify-between items-start mb-2">
                            <div class="w-3/4">
                                <h3 class="font-semibold text-sm truncate mb-1">
                                    <?php echo htmlspecialchars($course->title); ?>
                                </h3>
                                <p class="text-xs text-gray-500">
                                    <?php echo htmlspecialchars($course->teacher_name); ?>
                                </p>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-yellow-400 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-[10px] text-gray-600">4.5</span>
                            </div>
                        </div>
                        <div class="flex items-center mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-1 mr-2">
                                <div class="bg-blue-600 h-1 rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="text-[10px] text-gray-500">45%</span>
                        </div>
                        <div class="mt-2 flex justify-between items-center">
                        <a href="detailCourStudent.php?id_course=<?= htmlspecialchars($course->id_course); ?>" class="block">
                            <button class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-2 py-1 rounded-lg text-[10px] transition-colors">
                                Continuer
                            </button>
                            </a>

                            <span class="text-[10px] text-gray-400">
                                <?php echo htmlspecialchars($course->duration ?? '6h 30m'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


<?php
$content = ob_get_clean();
include('layout.php');
?>