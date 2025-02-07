<?php
//require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/pages/sweetAlert.php';



use classes\Course;
use classes\CourseTags;
use classes\Review;
use classes\ContentText;
use classes\ContentVideo;
use classes\Teacher;
use classes\Categorie;
use Classes\Enrollment;
use App\config\DataBaseManager;
use App\config\Session;
// affichage 
//session::start();
//if (Session::isLoggedIn()) {
//    // Récupérer les données de session
//    $s_userId = Session::get('user')['id'];
//    $userName = Session::get('user')['name'];
//    $userEmail = Session::get('user')['email'];
//    $userRole = Session::get('user')['role'];
//    $userAvatar = Session::get('user')['avatar'];
//}




?>

<?php require('include/navbar.php') ?>

<section class="relative bg-gray-50 py-16 mt-16">
    <img alt="Background image of a classroom with students" class="absolute inset-0 w-full h-full object-cover opacity-40" height="1080" src="https://storage.googleapis.com/a1aa/image/9L9M8eHQ-5Jsse9_75V3swpnHmhbCF115LpjcxpeoVc.jpg" width="1920" />
    <div class="relative container mx-auto px-4 flex flex-col lg:flex-row items-center">
        <div class="lg:w-1/2">
            <h2 class="text-blue-600 text-sm font-semibold">
                START TO SUCCESS
            </h2>
            <h1 class="text-3xl font-bold mt-2">
                Access To
                <span class="text-blue-500">
                    5500+
                </span>
                Courses from
                <span class="text-blue-500">
                    480
                </span>
                Instructors &amp; Institutions
            </h1>
            <p class="text-gray-600 mt-2 text-sm">
                Take your learning organisation to the next level.
            </p>
            <div class="relative mt-4">
                <input class="w-full border rounded-full py-2 px-3 pl-9 text-sm" placeholder="What do you want to learn?" type="text" />
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
            </div>
        </div>
        <div class="lg:w-1/2 mt-6 lg:mt-0 flex flex-col space-y-3">
            <div class="flex space-x-3">
                <img alt="Two people discussing" class="w-1/3 rounded-lg h-36 object-content" src="http://localhost/mvc_mina/public/uploads/avatar16.jpg" />
                <img alt="Woman working on laptop" class="w-1/3 rounded-lg h-36 object-cover" src="http://localhost/mvc_mina/public/uploads/2.jpg" />
            </div>
            <div class="flex space-x-3">
                <div class="bg-white shadow-md rounded-lg p-3 flex items-center">
                    <i class="fas fa-bell text-yellow-500 text-lg"></i>
                    <div class="ml-3">
                        <p class="text-gray-700 text-xs">
                            Tomorrow is our
                            <span class="font-bold">
                                "When I Grow Up" Spirit Day!
                            </span>
                        </p>
                    </div>
                </div>
                <img alt="Woman studying" class="w-1/2 rounded-lg h-24 object-cover" src="http://localhost/mvc_mina/public/uploads/3.jpg" />

            </div>
        </div>
    </div>
    </div>
</section>


<!-- Features Section -->
<section class="bg-indigo-800 py-2">


    <div class="container mx-auto px-4 flex flex-wrap justify-around text-white">
        <div class="flex flex-col items-center space-y-2">
            <i class="fas fa-brain text-xl">
            </i>
            <p class="text-center">
                Learn The Essential Skills
            </p>
        </div>
        <div class="flex flex-col items-center space-y-2">
            <i class="fas fa-certificate text-xl">
            </i>
            <p class="text-center">
                Earn Certificates And Degrees
            </p>
        </div>
        <div class="flex flex-col items-center space-y-2">
            <i class="fas fa-briefcase text-xl">
            </i>
            <p class="text-center">
                Get Ready for The Next Career
            </p>
        </div>
        <div class="flex flex-col items-center space-y-2">
            <i class="fas fa-star text-xl">
            </i>
            <p class="text-center">
                Master at Different Areas
            </p>
        </div>
    </div>
</section>


<!-- Categories Section -->
<section class="bg-white py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold mb-8">
            Top
            <span class="text-yellow-500">
                Categories
            </span>
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

            <?php
           // $categories = Categorie::getAll($dbManager);
            //var_dump( $categories );
            $counter = 0;
            foreach ($data['categories'] as $categorie):
                if ($counter > 11) break;
                $counter++;
            ?><a href="http://localhost/youdemy/pages/searchResult.php?id_categorie=<?= htmlspecialchars($categorie->id_categorie) ?>">
                    <div class="bg-gray-100 p-1 rounded-lg text-center">
                        <?= htmlspecialchars($categorie->name) ?>
                    </div>
                </a>


            <?php endforeach; ?>
        </div>
    </div>
</section>
<section>

    <div class="container mx-auto">
        <a class="text-blue-600 text-sm" href="#">
            Quel sera votre prochain sujet d'apprentissage ?
        </a>
        <h1 class="text-2xl font-bold mt-2">
            Découvrez les cours dispensés par des experts confirmés.
        </h1>
        <p>
            Explorez des cours conçus et enseignés par des experts de renom.
        </p>



        <?php require('include/cards.php') ?>


<!-- Pagination -->
<div class="flex justify-center mt-6 md:mt-8 mb-8 md:mb-12">
        <?php if ($data['totalPages'] > 1):
            $totalPages = $data['totalPages'] ;
            $page = $data['page'] ;?>
            <div class="flex space-x-2">
                <!-- Previous Button -->
                <?php if ($data['page'] > 1):

                    ?>
                    <a href="?page=<?= $page - 1 ?>" class="pagination-button px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm md:text-base">Previous</a>
                <?php else: ?>
                    <span class="px-4 py-2 bg-purple-300 text-white rounded-lg text-sm md:text-base cursor-not-allowed">Previous</span>
                <?php endif; ?>

                <!-- Next Button -->
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="pagination-button px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm md:text-base">Next</a>
                <?php else: ?>
                    <span class="px-4 py-2 bg-purple-300 text-white rounded-lg text-sm md:text-base cursor-not-allowed">Next</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>







    </div>
</section>





</body>

</html>