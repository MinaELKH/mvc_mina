<?php
ob_start();

use App\helpers\SweetAlert;
use config\Session;
// affichage 

if (Session::isLoggedIn() && session::hasRole('student')) {
    // Récupérer les données de session
    $s_userId = Session::get('user')['id'];
    $userName = Session::get('user')['name'];
    $userEmail = Session::get('user')['email'];
    $userRole = Session::get('user')['role'];
    $userAvatar = Session::get('user')['avatar'];
    //  var_dump($userAvatar); 
} else {
    SweetAlert::setMessage(
        'Authentification requise ⚠️',
        'Veuillez vous authentifier pour accéder cette page.',
        'warning',
        '../auth/login.php'
    );
}

$id_course = $_GET['id_course'] ?? null;
$id_content = $_GET['id_content'] ?? null;
// charger les donnees du cours
if (!$id_course || !is_numeric($id_course)) {
    die("ID de cours invalide ou manquant.");
}
// charger les donnees du cours
try {
    $newCourse = new Course($dbManager, $id_course);
    $course = $newCourse->getDetailCourse(); // je selection d apres viewcourse 

 
    if (!$course) {
        throw new Exception("Le cours avec l'ID $id_course n'existe pas.");
    }
    // Recuperer le contenu du cours en fonction de son type
    $newContent = null;

    if ($course->type == 'texte') {
        $newContent = new ContentText($dbManager);
        $result = ContentText::getAllByIdCourse($dbManager, $id_course);
    } else if ($course->type == 'video') {
        $newContent = new ContentVideo($dbManager);
        $result = ContentVideo::getAllByIdCourse($dbManager, $id_course);
        // var_dump($newContent) ;
        // die() ;
    }


    /// on verifie si le id_content dans l url , ou on va recupere le premier chapitre par defaut 
    if (isset($_GET['id_content'])) {
        // verif si le contenu est trouve on le recupere via l id_course
        if (is_numeric($_GET['id_content'])) {
            $newContent->id_content = intval($_GET['id_content']);
            $ObjetContent = $newContent->getById();
        } else {

            throw new Exception("ID content invalide ");
        }
    } elseif ($newContent) {
        // echo "hello" ;
        // var_dump($newContent) ;
        // die() ; 
        $newContent->id_course = $id_course;
        $ObjetContent = $newContent->getByIdCourse();
        // echo "hello" ;
        // var_dump($ObjetContent) ;
        // die() ; 
    } else {
        throw new Exception("Le contenu associe au cours n'a pas ete trouve.");
    }
} catch (Exception $e) {
    // Gerer les erreurs
    error_log($e->getMessage());
    die("Erreur : Impossible de charger les donnees du cours.");
}


?>

<!-- commentaire -->

<?php
if (isset($_POST["addreview"])) {
    $newreview = new review($dbManager, 0, $_POST['textComment'], intval($course->id_course), intval($s_userId));
    $newreview->add();
}
if (isset($_POST["deletereview"])) {
    $newreview = new review($dbManager, intval($_POST['id_review']));
    $newreview->delete();
    // var_dump($newreview->delete());
}




?>



<!-- Main Content -->
<div class="container mx-auto px-4 py-8 text-xs">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <!-- Course Info -->
        <div class="col-span-1 md:col-span-2">
            <h1 class="text-3xl sm:text-2xl font-bold mb-6"><?= $course->title ?></h1>

            <!-- Course Meta -->
            <div class="flex items-center gap-2 mb-6">
                <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-sm"><?= $course->category_name ?></span>
            </div>




            <!-- Course Video -->
            <div class="bg-gray-200 aspect-video rounded-lg mb-2">
                <img src="<?= '../' . $course->picture ?>" alt="Course preview" class="w-full h-full object-cover rounded-lg">
            </div>
            <!-- Course Details -->
            <div class="grid grid-cols-2  lg:grid-cols-4 items-center gap-2 mb-2 text-xs text-gray-600">
                <div>
                    <div class="font-medium mb-1">Dernière mise à jour</div>
                    <div><?= $course->updated_at ?></div>
                </div>

                <div>
                    <div class="font-medium mb-1">Apprenant</div>
                    <div><?= $course->student_count ?></div>
                </div>

                <!-- Social Actions -->
                <div class="flex gap-2 mb-2">
                    <button class="flex items-center gap-2 px-6 py-2 border rounded-lg hover:bg-gray-50">
                        <i class="far fa-heart"></i>
                        <span>Wishlist</span>
                    </button>
                    <button class="flex items-center gap-2 px-6 py-2 border rounded-lg hover:bg-gray-50">
                        <i class="fas fa-share-alt"></i>
                        <span>Share</span>
                    </button>
                </div>
            </div>

            <!-- Tags -->
            <div class="flex space-x-2 mb-2  ">
                <?php
                $newtag = new CourseTags($dbManager, $course->id_course);
                $result = $newtag->getTagsBycourse();

                // Couleur 
                $colors = ['bg-blue-200', 'bg-green-200', 'bg-red-200', 'bg-yellow-200', 'bg-purple-200', 'bg-pink-200', 'bg-indigo-200', 'bg-teal-200', 'bg-orange-200', 'bg-gray-300'];

                foreach ($result as $index => $Objet_tag) {
                    // Sélectionner une couleur en fonction de l'index du tag
                    $colorClass = $colors[$index % count($colors)];
                    echo '<span class="' . $colorClass . ' text-gray-700 px-3 py-1 rounded-full">' . $Objet_tag->name_tag . '</span>';
                }
                ?>
            </div>


            <!-- content  -->
            
            <section class="bg-white rounded-lg p-2 mb-2">
                <div class="flex space-x-2 mb-2  ">
                    <?php
                    echo ($ObjetContent->display());
                 
                    ?>
                </div>
            </section>
            <!-- Reviews Section -->
            <section class="bg-white rounded-lg p-2 mb-2">

                <!-- Individual Reviews -->
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="flex items-center justify-between bg-gray-50 p-4 border-b border-gray-100">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center bg-blue-100 rounded-full px-3 py-1">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-semibold text-gray-700">
                                    <?= htmlspecialchars($course->student_count); ?>
                                    Apprenants
                                </span>
                            </div>

                            <div class="flex items-center bg-green-100 rounded-full px-3 py-1">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                                <span class="font-semibold text-gray-700">
                                    <?= htmlspecialchars($course->review_count); ?>
                                    Commentaires
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-lienjoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            <h2 class="text-2xl font-bold text-indigo-900 flex-grow">
                                Laisser un Commentaire
                            </h2>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm">
                            Partagez vos réflexions, questions ou retours sur ce cours.
                        </p>
                        <div class="mb-4">
                        <form method="post">
                            <input type=hidden name="id_article" value="<?= htmlspecialchars($course->id_course) ?>">
                            <textarea name="textComment" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write a comment..." rows="3"></textarea>
                            <div class="flex justify-between items-center mt-2">
                                <button name="addreview" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                                    commenter
                                </button>
                                <div class="flex space-x-2 text-gray-500">
                                    <i class="fas fa-paperclip">
                                    </i>
                                    <i class="fas fa-map-marker-alt">
                                    </i>
                                </div>
                            </div>
                        </form>
                    </div>
                
                    </div>
                </div>

                <div class="bg-white shadow-lg rounded-lg mt-2 p-2 relative z-10">

                
                    <div class="space-y-6 mb-6">
                        <!-- reviewaires des users -->
                        <?php
                        $newreview = new review($dbManager);
                        $newreview->id_course = $course->id_course;
                        $result = $newreview->getReviewByCourse();
                        foreach ($result as $objet_Cmt):
                        ?>

                            <div class="bg-gray-200 p-2 m-8 rounded-lg shadow-sm">
                                <div class="flex items-center mb-2">

                                    <img
                                        img src="<?= !empty($objet_Cmt->avatar) ? '../' . $objet_Cmt->avatar : '../avatar_1.jpg' ?>"
                                        alt="Profil"
                                        class="w-10 h-10 rounded-full mr-3"
                                        height="40"
                                        width="40" />
                                    <div class='w-full flex justify-between'>
                                        <div>
                                            <p class="font-semibold">
                                                <?= $objet_Cmt->name_full ?>
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <?= $objet_Cmt->created_at ?>
                                            </p>
                                        </div>
                                        <?php if ($s_userId == $objet_Cmt->id_user) : ?>

                                            <div class="ml-auto text-gray-500 cursor-pointer hover:text-red-500">
                                                <form method="post"> <input type="hidden" name="id_review" value="<?= $objet_Cmt->id_review ?>">
                                                    <button name="deletereview"> <i class="fas fa-times"></i></button>
                                                </form>
                                            </div>

                                        <?php endif; ?>
                                    </div>

                                </div>
                                <p class="text-gray-700 mb-2">
                                    <?= $objet_Cmt->comment ?>
                                </p>
                                <div class="flex items-center text-gray-500">
                                    <i class="fas fa-heart mr-1">
                                    </i>
                                    <span class="mr-4">
                                        11 Likes
                                    </span>
                                    <i class="fas fa-reply mr-1">
                                    </i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- More Reviews Button -->
                        <div class="text-center">
                            <button class="text-indigo-600 font-medium hover:underline">plusde commentaires </button>
                        </div>
            </section>
        </div>

        <!-- Sidebar -->
        <div class="col-span-1">
            <!-- Prix et Achat -->



            <!-- À propos de l'Instructeur -->
            <div class="bg-white rounded-lg shadow-sm p-6 mt-28">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">
                    À propos de l'Instructeur
                </h2>
                <div class="flex items-center mb-2">
                    <img src="<?= !empty($course->teacher_avatar) ? '../' . $course->teacher_avatar : '../avatar_1.jpg' ?>"
                        alt="Profil"
                        class="w-10 h-10 rounded-full mr-3"
                        height="40"
                        width="40" />
                        <div>
                    <h3 class="text-gray-800 font-semibold">
                        Dr. <?= $course->teacher_name ?>
                    </h3>
                    <p class="text-gray-600 text-sm">
                        Enseignant 
                    </p>
                        </div>
                </div>
            </div>

     


            <!-- sommaire -->
            <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 mt-4">
                <div class="flex items-center mb-4 border-b pb-3">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-800">Sommaire du Cours</h2>
                </div>

                <ul class="space-y-3">
                    <?php
                    $contents = $ObjetContent->getAllByIdCourse($dbManager, $id_course);
                    $index = 1;
                    foreach ($contents as $content) :
                    ?>
                        <li class="flex items-center">
                            <span class="mr-3 text-sm font-bold text-blue-600 bg-blue-50 rounded-full w-6 h-6 flex items-center justify-center">
                                <?= $index++ ?>
                            </span>
                            <a
                                href="detailCourStudent.php?id_course=<?= $id_course ?>&id_content=<?= $content->id_content ?>"
                                class="text-indigo-700  font-semibold hover:text-blue-700 hover:underline transition-colors duration-200 ease-in-out flex-grow">
                                <?= $content->title ?>
                            </a>
                            <svg class="w-5 h-5  text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Course Overview -->
            
            <h4 class="underline text-lg font-bold text-gray-800 m-4">Ce que vous apprendrez</h4>
            <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 mt-4">

                <div class="leading-relaxed  whitespace-normal break-words"> <?= $course->description ?></div>
                </section>

            </div>

        </div>
    </div>
</div>
    <?php
    $content = ob_get_clean();
    include('layout.php');
    ?>