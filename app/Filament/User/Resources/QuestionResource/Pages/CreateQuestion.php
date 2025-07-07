<?php

namespace App\Filament\User\Resources\QuestionResource\Pages;

use App\Filament\User\Resources\QuestionResource;
use App\Models\Answer;
use App\Models\Question;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $quiz = \App\Models\Quiz::find($data['quiz_id']);
        $currentCount = \App\Models\Question::where('quiz_id', $quiz->id)->count();
        
        if ($currentCount >= $quiz->max_questions) {
            Notification::make()
                ->title('Limite atteinte')
                ->body('Vous avez déjà atteint le nombre maximum de questions pour ce quiz.')
                ->danger()
                ->send();
            $this->halt();
        }

        // Validation : au moins deux réponses
        if (!isset($data['answers']) || !is_array($data['answers']) || count($data['answers']) < 2) {
            Notification::make()
                ->title('Erreur')
                ->body('Vous devez ajouter au moins deux choix de réponse.')
                ->danger()
                ->send();
            $this->halt();
        }

        // Récupérer le type de la question
        $questionType = $data['type'] ?? 'text';

        // Validation : chaque réponse doit avoir du contenu selon le type de la question
        foreach ($data['answers'] as $index => $answer) {
            $hasContent = false;
            $errorMessage = null;

            switch ($questionType) {
                case 'text':
                    $hasContent = !empty(trim($answer['title'] ?? ''));
                    if (!$hasContent) {
                        $errorMessage = "La réponse " . ($index + 1) . " doit contenir du texte.";
                    }
                    break;
                case 'audio':
                    $fileData = $answer['audio_content'] ?? null;
                    $hasContent = $this->validateFileContent($fileData, $errorMessage, 'audio', $index + 1);
                    if (!$hasContent && !$errorMessage) {
                        $errorMessage = "La réponse " . ($index + 1) . " doit contenir un fichier audio.";
                    }
                    break;
                case 'video':
                    $fileData = $answer['video_content'] ?? null;
                    $hasContent = $this->validateFileContent($fileData, $errorMessage, 'video', $index + 1);
                    if (!$hasContent && !$errorMessage) {
                        $errorMessage = "La réponse " . ($index + 1) . " doit contenir un fichier vidéo.";
                    }
                    break;
                case 'file':
                    $fileData = $answer['file_content'] ?? null;
                    $hasContent = $this->validateFileContent($fileData, $errorMessage, 'file', $index + 1);
                    if (!$hasContent && !$errorMessage) {
                        $errorMessage = "La réponse " . ($index + 1) . " doit contenir un fichier joint.";
                    }
                    break;
            }

            if (!$hasContent) {
                Notification::make()
                    ->title('Erreur')
                    ->body($errorMessage ?? "La réponse " . ($index + 1) . " doit avoir du contenu selon son type.")
                    ->danger()
                    ->send();
                $this->halt();
            }
        }

        // Validation : au moins une bonne réponse
        $hasCorrectAnswer = collect($data['answers'])->contains(function ($answer) {
            return isset($answer['is_correct']) && $answer['is_correct'] === true;
        });

        if (!$hasCorrectAnswer) {
            Notification::make()
                ->title('Erreur')
                ->body('Vous devez sélectionner au moins une bonne réponse.')
                ->danger()
                ->send();
            $this->halt();
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            // Créer la question
            $question = Question::create([
                'quiz_id' => $data['quiz_id'],
                'title' => $data['title'],
                'status' => true,
            ]);

            // Créer les réponses
            if (isset($data['answers']) && is_array($data['answers'])) {
                foreach ($data['answers'] as $index => $answerData) {
                    $type = $answerData['type'] ?? 'text';
                    $content = null;
                    $title = null;

                    try {
                        // Déterminer le contenu selon le type
                        switch ($type) {
                            case 'text':
                                $title = trim($answerData['title'] ?? '');
                                break;
                            case 'audio':
                                $content = $this->processFileUpload($answerData['audio_content'] ?? null, 'audio', $index + 1);
                                $title = 'Réponse audio';
                                break;
                            case 'video':
                                $content = $this->processFileUpload($answerData['video_content'] ?? null, 'video', $index + 1);
                                $title = 'Réponse vidéo';
                                break;
                            case 'file':
                                $content = $this->processFileUpload($answerData['file_content'] ?? null, 'file', $index + 1);
                                $title = 'Réponse fichier';
                                break;
                        }

                        // Créer la réponse seulement si on a du contenu
                        if (!empty($title) || !empty($content)) {
                            Answer::create([
                                'question_id' => $question->id,
                                'title' => $title,
                                'type' => $type,
                                'content' => $content,
                                'is_correct' => $answerData['is_correct'] ?? false,
                            ]);
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Erreur lors du traitement de la réponse ' . ($index + 1))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                        
                        // Supprimer la question créée en cas d'erreur
                        $question->delete();
                        throw $e;
                    }
                }
            }

            return $question;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Erreur lors de la création de la question')
                ->body('Une erreur est survenue : ' . $e->getMessage())
                ->danger()
                ->send();
            throw $e;
        }
    }

    /**
     * Traite l'upload de fichier avec gestion d'erreur
     */
    private function processFileUpload($fileData, string $type, int $answerIndex): ?string
    {
        if (empty($fileData)) {
            throw new \Exception("Aucun fichier fourni pour la réponse $answerIndex");
        }

        // Si c'est déjà un tableau (cas d'erreur d'upload)
        if (is_array($fileData) && isset($fileData['error'])) {
            $errorCode = $fileData['error'];
            $errorMessage = $this->getUploadErrorMessage($errorCode, $type, $answerIndex);
            throw new \Exception($errorMessage);
        }

        // Si c'est un chemin de fichier (upload réussi)
        if (is_string($fileData)) {
            return $fileData;
        }

        // Si c'est un objet UploadedFile
        if ($fileData instanceof \Illuminate\Http\UploadedFile) {
            try {
                $directory = match($type) {
                    'audio' => 'quiz-audio',
                    'video' => 'quiz-video',
                    'file' => 'quiz-files',
                    default => 'quiz-files'
                };

                $path = $fileData->store($directory, 'public');
                
                if (!$path) {
                    throw new \Exception("Impossible de sauvegarder le fichier pour la réponse $answerIndex");
                }

                return $path;
            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la sauvegarde du fichier pour la réponse $answerIndex : " . $e->getMessage());
            }
        }

        throw new \Exception("Format de fichier non reconnu pour la réponse $answerIndex");
    }

    /**
     * Retourne un message d'erreur spécifique selon le code d'erreur PHP
     */
    private function getUploadErrorMessage(int $errorCode, string $type, int $answerIndex): string
    {
        $typeLabel = match($type) {
            'audio' => 'audio',
            'video' => 'vidéo',
            'file' => 'fichier',
            default => 'fichier'
        };

        return match($errorCode) {
            UPLOAD_ERR_INI_SIZE => "Le fichier $typeLabel pour la réponse $answerIndex dépasse la taille maximale autorisée par le serveur.",
            UPLOAD_ERR_FORM_SIZE => "Le fichier $typeLabel pour la réponse $answerIndex dépasse la taille maximale autorisée par le formulaire.",
            UPLOAD_ERR_PARTIAL => "Le fichier $typeLabel pour la réponse $answerIndex n'a été que partiellement uploadé.",
            UPLOAD_ERR_NO_FILE => "Aucun fichier $typeLabel n'a été uploadé pour la réponse $answerIndex.",
            UPLOAD_ERR_NO_TMP_DIR => "Erreur serveur : dossier temporaire manquant pour la réponse $answerIndex.",
            UPLOAD_ERR_CANT_WRITE => "Erreur serveur : impossible d'écrire le fichier $typeLabel pour la réponse $answerIndex.",
            UPLOAD_ERR_EXTENSION => "Le fichier $typeLabel pour la réponse $answerIndex a été rejeté par une extension PHP.",
            default => "Erreur inconnue lors de l'upload du fichier $typeLabel pour la réponse $answerIndex."
        };
    }

    /**
     * Valide le contenu d'un fichier uploadé
     */
    private function validateFileContent($fileData, &$errorMessage, string $type, int $answerIndex): bool
    {
        if (empty($fileData)) {
            $errorMessage = "Aucun fichier $type n'a été fourni pour la réponse $answerIndex.";
            return false;
        }

        // Si c'est un tableau avec une erreur
        if (is_array($fileData) && isset($fileData['error'])) {
            $errorCode = $fileData['error'];
            $errorMessage = $this->getUploadErrorMessage($errorCode, $type, $answerIndex);
            return false;
        }

        // Si c'est un chemin de fichier (upload réussi)
        if (is_string($fileData)) {
            return true;
        }

        // Si c'est un objet UploadedFile
        if ($fileData instanceof \Illuminate\Http\UploadedFile) {
            // Vérifier si le fichier a été uploadé avec succès
            if (!$fileData->isValid()) {
                $errorMessage = "Le fichier $type pour la réponse $answerIndex n'a pas été uploadé correctement.";
                return false;
            }

            // Vérifier la taille du fichier
            $maxSize = match($type) {
                'audio' => 10240, // 10MB
                'video' => 51200, // 50MB
                'file' => 10240,  // 10MB
                default => 10240
            };

            if ($fileData->getSize() > $maxSize * 1024) {
                $errorMessage = "Le fichier $type pour la réponse $answerIndex dépasse la taille maximale autorisée (" . ($maxSize / 1024) . "MB).";
                return false;
            }

            return true;
        }

        $errorMessage = "Format de fichier non reconnu pour la réponse $answerIndex.";
        return false;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Question créée avec succès';
    }

    protected function getRedirectUrl(): string
    {
        $recordId = $this->record->id ?? null;
        return $recordId ? $this->getResource()::getUrl('edit', ['record' => $recordId]) : $this->getResource()::getUrl('index');
    }
}
