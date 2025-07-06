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
        // Créer la question
        $question = Question::create([
            'quiz_id' => $data['quiz_id'],
            'title' => $data['title'],
            'status' => true,
        ]);

        // Créer les réponses
        if (isset($data['answers']) && is_array($data['answers'])) {
            foreach ($data['answers'] as $answerData) {
                if (isset($answerData['title']) && !empty(trim($answerData['title']))) {
                    Answer::create([
                        'question_id' => $question->id,
                        'title' => trim($answerData['title']),
                        'is_correct' => $answerData['is_correct'] ?? false,
                    ]);
                }
            }
        }

        return $question;
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
