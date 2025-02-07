<?php
namespace App\helper;
class uploadimage
{
public static  function uploadImage($file, $uploadsDir = 'uploads/', $maxSize = 2 * 1024 * 1024, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'])
{
if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
$photoTmpName = $file['tmp_name'];
$photoName = basename($file['name']);
$photoSize = $file['size'];
$photoType = mime_content_type($photoTmpName);

    // Vérification du type
if (!in_array($photoType, $allowedTypes)) {
return ['success' => false, 'message' => "Type de fichier non supporté. Veuillez utiliser JPEG, PNG ou GIF."];
}

// Vérification de la taille
if ($photoSize > $maxSize) {
    return ['success' => false, 'message' => "Le fichier est trop volumineux. Limite de " . ($maxSize / (1024 * 1024)) . " Mo."];
}

// Création du chemin d'enregistrement avec un nom unique
$photoPath = $uploadsDir . uniqid() . '-' . $photoName;
// Déplacement du fichier
if (move_uploaded_file($photoTmpName, "../$photoPath")) {
    return ['success' => true, 'filePath' => $photoPath];
} else {
    return ['success' => false, 'message' => "Erreur lors de l'upload de l'image."];
}
} else {
    return ['success' => false, 'message' => "Aucun fichier sélectionné ou erreur lors de l'upload."];
}
}



public static function  uploadVideo($file, $uploadsDir = 'uploads/', $maxSize = 50 * 1024 * 1024, $allowedTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv'])
{
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $videoTmpName = $file['tmp_name'];
        $videoName = basename($file['name']);
        $videoSize = $file['size'];
        $videoType = mime_content_type($videoTmpName);

        // Vérification du type
        if (!in_array($videoType, $allowedTypes)) {
            return ['success' => false, 'message' => "Type de fichier non supporté. Veuillez utiliser MP4, AVI, MOV ou WMV."];
        }

        // Vérification de la taille
        if ($videoSize > $maxSize) {
            return ['success' => false, 'message' => "Le fichier est trop volumineux. Limite de " . ($maxSize / (1024 * 1024)) . " Mo."];
        }

        // Création du chemin d'enregistrement avec un nom unique
        $videoPath = $uploadsDir . uniqid() . '-' . $videoName;
        // Déplacement du fichier
        if (move_uploaded_file($videoTmpName, "../$videoPath")) {
            return ['success' => true, 'filePath' => $videoPath];
        } else {
            return ['success' => false, 'message' => "Erreur lors de l'upload de la vidéo."];
        }
    } else {
        return ['success' => false, 'message' => "Aucun fichier sélectionné ou erreur lors de l'upload."];
    }
}


function isValidYouTubeURL($url)
{
    return preg_match('/(youtube.com\/watch\?v=|youtu.be\/)([a-zA-Z0-9_-]+)/', $url);
}
}