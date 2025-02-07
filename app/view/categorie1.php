<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';
//require_once("../sweetAlert.php");
ob_start();





// Ajout de catégorie


// Archive de catégorie


// Fonction pour afficher les catégories

?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liste des Catégories</title>
    </head>
    <body>
    <h1>Liste des Catégories</h1>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nom de la Catégorie</th>
        </tr>
        <?php if (!empty($data['categories'])) :      ?>

            <?php foreach ($data['categories'] as $categorie) : ?>
            <?php
                //var_dump($categorie); die() ;
            ?>
                <tr>
                    <td><?= htmlspecialchars($categorie->id_categorie) ?></td>
                    <td><?= htmlspecialchars($categorie->name) ?></td>
                </tr>
            <?php endforeach; ?>


        <?php else : ?>
            <tr>
                <td colspan="2">Aucune catégorie trouvée.</td>
            </tr>
        <?php endif; ?>
    </table>

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


    </body>
    </html>






<?php
//$content = ob_get_clean();
//include('layout.php');
?>