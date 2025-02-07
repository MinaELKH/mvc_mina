<?php
namespace App\helper;

class UploadImage
{
    public static function uploadImage($file, $uploadsDir = 'uploads/', $maxSize = 2 * 1024 * 1024, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'])
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => "Aucun fichier sélectionné ou erreur lors de l'upload."];
        }

        $photoTmpName = $file['tmp_name'];
        $photoName = self::sanitizeFileName($file['name']);
        $photoSize = $file['size'];
        $photoType = mime_content_type($photoTmpName);

        if (!in_array($photoType, $allowedTypes)) {
            return ['success' => false, 'message' => "Type de fichier non supporté. Veuillez utiliser JPEG, PNG ou GIF."];
        }

        if ($photoSize > $maxSize) {
            return ['success' => false, 'message' => "Le fichier est trop volumineux. Limite de " . ($maxSize / (1024 * 1024)) . " Mo."];
        }

        // Création d'un chemin sécurisé
        $uploadPath = __DIR__ . "/../../public/$uploadsDir";
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $photoPath = $uploadPath . uniqid() . '-' . $photoName;

        if (move_uploaded_file($photoTmpName, $photoPath)) {
            return ['success' => true, 'filePath' => $uploadsDir . basename($photoPath)];
        } else {
            return ['success' => false, 'message' => "Erreur lors de l'upload de l'image."];
        }
    }

    public static function uploadVideo($file, $uploadsDir = 'uploads/', $maxSize = 50 * 1024 * 1024, $allowedTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv'])
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => "Aucun fichier sélectionné ou erreur lors de l'upload."];
        }

        $videoTmpName = $file['tmp_name'];
        $videoName = self::sanitizeFileName($file['name']);
        $videoSize = $file['size'];
        $videoType = mime_content_type($videoTmpName);

        if (!in_array($videoType, $allowedTypes)) {
            return ['success' => false, 'message' => "Type de fichier non supporté. Veuillez utiliser MP4, AVI, MOV ou WMV."];
        }

        if ($videoSize > $maxSize) {
            return ['success' => false, 'message' => "Le fichier est trop volumineux. Limite de " . ($maxSize / (1024 * 1024)) . " Mo."];
        }

        // Création d'un chemin sécurisé
        $uploadPath = __DIR__ . "/../../public/$uploadsDir";
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $videoPath = $uploadPath . uniqid() . '-' . $videoName;

        if (move_uploaded_file($videoTmpName, $videoPath)) {
            return ['success' => true, 'filePath' => $uploadsDir . basename($videoPath)];
        } else {
            return ['success' => false, 'message' => "Erreur lors de l'upload de la vidéo."];
        }
    }

    public static function isValidYouTubeURL($url)
    {
        return preg_match('/(youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url);
    }

    private static function sanitizeFileName($fileName)
    {
        return preg_replace('/[^a-zA-Z0-9\.-]/', '_', $fileName);
    }
}
