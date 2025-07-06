<?php

namespace App\Filament\User\Resources\QuizzesResource\Pages;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use Filament\Actions\Action;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\User\Resources\QuizzesResource;

class EditQuizzes extends EditRecord
{
    protected static string $resource = QuizzesResource::class;

    public static $tab = Quiz::TEXT_TYPE;
    public function currentActiveTab()
    {
        $pre = URL::previous();
        parse_str(parse_url($pre)['query'] ?? '', $queryParams);
        $tab = $queryParams['tab'] ?? null;
        $tabType = [
            '-subject-tab' => Quiz::SUBJECT_TYPE,
            '-text-tab' => Quiz::TEXT_TYPE,
            '-url-tab' => Quiz::URL_TYPE,
            '-upload-tab' => Quiz::UPLOAD_TYPE,
        ];

        $tabType[$tab] ?? Quiz::TEXT_TYPE;
    }

    // protected function afterValidate(): void
    // {
    //     $data = $this->form->getState();

    //     if (empty($this->data['file_upload']) && empty($data['quiz_description_text']) && empty($data['quiz_description_sub']) && empty($data['quiz_description_url'])) {
    //         Notification::make()
    //             ->danger()
    //             ->title(__('messages.quiz.quiz_description_required'))
    //             ->send();
    //         $this->halt();
    //     }
    // }


    public function fillForm(): void
    {
        $data = $this->record->attributesToArray();
        $data = $this->mutateFormDataBeforeFill($data);

        // Charger les questions et réponses existantes
        $questions = Question::where('quiz_id', $this->record->id)->with('answers')->get();
        $data['questions'] = [];

        foreach ($questions as $question) {
            $answersOption = $question->answers->map(function ($answer) {
                return [
                    'title' => $answer->title,
                    'is_correct' => $answer->is_correct
                ];
            })->toArray();

            $data['questions'][] = [
                'title' => $question->title,
                'answers' => $answersOption,
                'question_id' => $question->id
            ];
        }

        $this->form->fill($data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('messages.common.back'))
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data['type'] = getTabType();
        if ($data['type'] == Quiz::TEXT_TYPE) {
            $data['quiz_description'] = $data['quiz_description_text'];
        } elseif ($data['type'] == Quiz::SUBJECT_TYPE) {
            $data['quiz_description'] = $data['quiz_description_sub'];
        } elseif ($data['type'] == Quiz::URL_TYPE) {
            $data['quiz_description'] = $data['quiz_description_url'];
        }

        // Supprimer les anciennes questions et réponses
        $record->questions()->delete();

        // Sauvegarder les nouvelles questions et réponses
        if (isset($data['questions']) && is_array($data['questions'])) {
            foreach ($data['questions'] as $questionData) {
                if (isset($questionData['title']) && !empty(trim($questionData['title']))) {
                    $question = Question::create([
                        'quiz_id' => $record->id,
                        'title' => trim($questionData['title']),
                        'status' => true,
                    ]);

                    // Sauvegarder les réponses
                    if (isset($questionData['answers']) && is_array($questionData['answers'])) {
                        $validAnswers = collect($questionData['answers'])->filter(function ($answerData) {
                            return isset($answerData['title']) && !empty(trim($answerData['title']));
                        });
                        
                        foreach ($validAnswers as $answerData) {
                            Answer::create([
                                'question_id' => $question->id,
                                'title' => trim($answerData['title']),
                                'is_correct' => $answerData['is_correct'] ?? false,
                            ]);
                        }
                    }
                }
            }
        }

        // Nettoyer les données avant la mise à jour
        unset($data['questions']);
        unset($data['quiz_description_text']);
        unset($data['quiz_description_sub']);
        unset($data['quiz_description_url']);
        unset($data['active_tab']);
        $data['max_questions'] = $record->questions()->count();

        $record->update($data);

        return $record;
    }


    public function getTitle(): string
    {
        return __('messages.quiz.edit_quiz');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('messages.quiz.quiz_updated_success');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            parent::getFormActions()[0],
            Action::make('cancel')
                ->label(__('messages.common.cancel'))
                ->color('gray')
                ->url(QuizzesResource::getUrl('index')),
        ];
    }


}
