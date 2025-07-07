<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class HandleFileUploadErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier les erreurs d'upload dans les fichiers
        if ($request->hasFile('answers')) {
            $files = $request->file('answers');
            
            if (is_array($files)) {
                foreach ($files as $index => $fileGroup) {
                    if (is_array($fileGroup)) {
                        foreach ($fileGroup as $field => $file) {
                            if ($file && $file->getError() !== UPLOAD_ERR_OK) {
                                $errorMessage = $this->getUploadErrorMessage($file->getError(), $field, $index);
                                Log::error('File upload error', [
                                    'error_code' => $file->getError(),
                                    'error_message' => $errorMessage,
                                    'file_name' => $file->getClientOriginalName(),
                                    'file_size' => $file->getSize(),
                                    'max_size' => ini_get('upload_max_filesize'),
                                    'post_max_size' => ini_get('post_max_size')
                                ]);
                                
                                return response()->json([
                                    'error' => $errorMessage,
                                    'details' => [
                                        'field' => $field,
                                        'index' => $index,
                                        'file_name' => $file->getClientOriginalName()
                                    ]
                                ], 422);
                            }
                        }
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Retourne un message d'erreur spécifique selon le code d'erreur PHP
     */
    private function getUploadErrorMessage(int $errorCode, string $field, int $index): string
    {
        $fieldLabel = match($field) {
            'audio_content' => 'audio',
            'video_content' => 'vidéo',
            'file_content' => 'fichier',
            default => 'fichier'
        };

        return match($errorCode) {
            UPLOAD_ERR_INI_SIZE => "Le fichier $fieldLabel pour la réponse " . ($index + 1) . " dépasse la taille maximale autorisée par le serveur (" . ini_get('upload_max_filesize') . ").",
            UPLOAD_ERR_FORM_SIZE => "Le fichier $fieldLabel pour la réponse " . ($index + 1) . " dépasse la taille maximale autorisée par le formulaire (" . ini_get('post_max_size') . ").",
            UPLOAD_ERR_PARTIAL => "Le fichier $fieldLabel pour la réponse " . ($index + 1) . " n'a été que partiellement uploadé. Veuillez réessayer.",
            UPLOAD_ERR_NO_FILE => "Aucun fichier $fieldLabel n'a été uploadé pour la réponse " . ($index + 1) . ".",
            UPLOAD_ERR_NO_TMP_DIR => "Erreur serveur : dossier temporaire manquant pour la réponse " . ($index + 1) . ". Contactez l'administrateur.",
            UPLOAD_ERR_CANT_WRITE => "Erreur serveur : impossible d'écrire le fichier $fieldLabel pour la réponse " . ($index + 1) . ". Contactez l'administrateur.",
            UPLOAD_ERR_EXTENSION => "Le fichier $fieldLabel pour la réponse " . ($index + 1) . " a été rejeté par une extension PHP.",
            default => "Erreur inconnue lors de l'upload du fichier $fieldLabel pour la réponse " . ($index + 1) . "."
        };
    }
}
