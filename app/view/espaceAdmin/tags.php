<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';
require_once("../sweetAlert.php");
ob_start();

use classes\Tag;
use config\DataBaseManager;

$dbManager = new DatabaseManager();


if (isset($_POST['addTags'])) {
    // var_dump($_POST) ;
    // die();
    // Nettoyage et traitement des tags
    $tags_input = htmlspecialchars(trim($_POST['tags']));
    $tags = array_unique(array_filter(array_map('trim', explode(',', $tags_input))));

    foreach ($tags as $tag_name) {
    //   var_dump($tag_name); // Debug: Afficher le tag traité
      $tag = new Tag($dbManager, 0, $tag_name);
      // Vérifier si le tag existe
      $objetTag = $tag->getTagByName();
      if ($objetTag === null) {
        // Le tag n'existe pas, le créer
        $tag->add();
      } 
    }
  }

  
?>


<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-100 p-8">
    <div class="container mx-auto max-w-2xl">
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8">
            <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-6">
                Gestion des Tags
            </h2>

            <form  action=""  method="post" id="tagForm" class="space-y-6">
                <!-- Zone de Création de Tags -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center space-x-4 mb-4">
                        <input 
                            type="text" 
                            id="tagInput" 
                            placeholder="Entrez un nouveau tag" 
                            class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <button 
                            type="button" 
                            id="addTagButton" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition"
                        >
                            Ajouter
                        </button>
                    </div>

                    <!-- Liste des Tags -->
                    <input type="hidden" name="tags" id="tags">
                    <div 
                        id="tagList" 
                        class="flex flex-wrap gap-2 min-h-[100px] border border-dashed border-blue-300 p-2 rounded-lg"
                    >
                        <!-- Les tags seront ajoutés dynamiquement ici -->
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600" id="tagCountPreview">
                        0 tag(s)
                    </div>
                    <div class="space-x-4">
                        <button 
                            type="button" 
                            id="resetButton" 
                            class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition"
                        >
                            Réinitialiser
                        </button>
                        <button 
                            type="submit" 
                            name="addTags"
                            class="px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:scale-105 transform transition"
                        >
                            Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tagInput = document.getElementById('tagInput');
    const addTagButton = document.getElementById('addTagButton');
    const tagList = document.getElementById('tagList');
    const tagCountPreview = document.getElementById('tagCountPreview');
    const resetButton = document.getElementById('resetButton');



    const tagsHiddenInput = document.getElementById('tags');  // ici on stock les tags a recupere sur post
    

    let tags = [] ; 
    
    // Ajouter un tag
    addTagButton.addEventListener('click', () => {
        const tagText = tagInput.value.trim();
        if (tagText && !tags.includes(tagText)) {
            const tagElement = document.createElement('div');
            tagElement.className = 'bg-blue-200 px-3 py-1 rounded-full flex items-center';
            tagElement.innerHTML = `
                <span class="mr-2">${tagText}</span>
                <button type="button" class="text-red-500 ml-2">×</button>
            `;
            const removeButton = tagElement.querySelector('button');
            removeButton.addEventListener('click', () => {
                tagList.removeChild(tagElement);
                updateTagCount();
            });
            tagList.appendChild(tagElement);

            tags.push(tagText); 
            
            tagInput.value = '';
            tagsHiddenInput.value = tags.join(',');
            updateTagCount(tagText);
        }else{
            tagInput.value = '';
        }
       
    });


    tagInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTagButton.click();
        }
    });

 
    resetButton.addEventListener('click', () => {
       
        tagList.innerHTML = '';
        updateTagCount();
    });


    function updateTagCount() {
        const tagCount = tagList.children.length;

        tagCountPreview.textContent = `${tagCount} tag(s)`;
    }
});
</script>


<style>
/* Styles supplémentaires pour les interactions */
#tagList > div {
    transition: all 0.3s ease;
}

#tagList > div:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
</style>



<?php
$content = ob_get_clean();
include('layout.php');
?>