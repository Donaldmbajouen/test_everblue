<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload un fichier avec validation et gestion d'erreur
     *
     * @param UploadedFile $file
     * @param string $type (audio, video, document)
     * @param string $directory
     * @return string chemin du fichier stocké
     * @throws \Exception
     */
    public static function upload(UploadedFile $file, string $type, string $directory): string
    {
        $config = config('filament.file_upload');
        $allowedExtensions = $config['allowed_extensions'][$type] ?? [];
        $maxSize = $config['max_size'] ?? 102400; // en KB

        // Vérification de l'extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions)) {
            throw new \Exception("Le type de fichier .$extension n'est pas autorisé pour $type.");
        }

        // Vérification de la taille
        if ($file->getSize() > $maxSize * 1024) {
            throw new \Exception("Le fichier dépasse la taille maximale autorisée (" . ($maxSize / 1024) . "MB).");
        }

        // Upload
        $path = $file->store($directory, 'public');
        if (!$path) {
            throw new \Exception("Erreur lors de l'upload du fichier. Veuillez réessayer.");
        }
        return $path;
    }
} 