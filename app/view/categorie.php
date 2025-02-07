<?php
//require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';
require_once("sweetAlert.php");
ob_start();

use classes\Categorie;
use App\config\DataBaseManager;


// Ajout de catégorie

// Fonction pour afficher les catégories
function afficher($data)
{
    try {


        echo " <div class=' bg-gradient-to-br from-gray-50 to-blue-100 p-8'>
    <div class='container mx-auto'>
                      <div class='flex justify-between items-center mb-4'>
            <h1 class='text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600'>
                Categories
            </h1> </div>  ";




        if (!empty($data['categories'])) {
            echo "<table id='DataTable' class='w-full'>
                    <thead class='bg-gradient-to-r from-blue-50 to-purple-50'>
                        <tr>
                            <th class='w-1/6 px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'>Réf</th>
                            <th class='w-1/3 px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'>Nom</th>
                            <th class='w-1/3 px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'>Description</th>
                            <th class='w-1/6 px-6 py-4 text-center text-sm font-bold text-gray-700 uppercase tracking-wider'>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";



            foreach ($data['categories'] as $objet) {

                echo "<tr class='border-b border-gray-100 hover:bg-blue-50/50 transition duration-300'>
                        <td class='px-6 py-4 text-gray-800'>{$objet->id_categorie}</td>
                        <td class='px-6 py-4'>
                            <div class='font-semibold text-gray-900'>{$objet->name}</div>
                        </td>
                        <td class='px-6 py-4 text-gray-600'>{$objet->description}</td>
                        <td class='px-6 py-4 text-center'>
                            <form action='' method='post' class='flex justify-center'>
                                <input type='hidden' name='id_categorie' value='{$objet->id_categorie}'>
                                <button type='submit' name='archive' value='{$objet->id_categorie}' 
                                    class='text-red-500 hover:text-red-700 transform hover:scale-125 transition'
                                    title='Archiver la catégorie'>
                                    <i class='fas fa-archive'></i>
                                </button>
                            </form>
                        </td>
                    </tr>";
            }
            echo "</tbody>
                </table>";
        } else {
            echo "<p class='text-center text-gray-500 py-6'>Aucune catégorie trouvée.</p>";
        }

        echo "  </div>
            </div>
        </div>";

        // Add DataTables initialization script
        echo "<script>
            $(document).ready(function() {
                $('#categoriesTable').DataTable({
                    responsive: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
                    },
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false
                    }]
                });
            });
        </script>";
    } catch (Exception $e) {
        echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative' role='alert'>
                <strong class='font-bold'>Erreur : </strong>
                <span class='block sm:inline'>" . htmlspecialchars($e->getMessage()) . "</span>
              </div>";
    }
}

?>




<section class="mb-4">

    <div class=" bg-gradient-to-br from-gray-50 to-blue-100 p-8">
        <div class="container mx-auto">
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8">
                <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-6">
                    Ajouter Categorie
                </h2>
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <p class="text-gray-600">Ajouter une Catégorie</p>
                    <form method="POST" action="/mvc_mina/categorie/ajouter">
                        <div class="space-y-4">
                            <!-- Nom du Categorie -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la Catégorie</label>
                                <input type="text" id="name" name="name" class="w-full p-3 border rounded-lg" placeholder="Entrez le nom">
                            </div>
                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="description" name="description" class="w-full h-32 p-3 border rounded-lg resize-none" placeholder="Entrez la description"></textarea>
                            </div>
                            <!-- Bouton de soumission -->
                            <div>
                                <button type="submit" name="add" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                    Ajouter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

<section class="mb-2">

    <div class="bg-white p-4 rounded-lg shadow-lg">
        <?php afficher($data); ?>
    </div>
</section>


<?php
$content = ob_get_clean();
include('layout.php');
?>