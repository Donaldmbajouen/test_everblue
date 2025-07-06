<?php

namespace App\Filament\User\Resources\UserQuizResource\Widgets;

use App\Models\Answer;
use App\Models\QuestionAnswer;
use App\Models\Quiz;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class ParticipantQuestionTable extends BaseWidget
{


    public function getTableHeading(): string  | null
    {
        return __('messages.participant.participant_responses');
    }

    public ?Model $record = null;

    public function table(Table $table): Table
    {
        if (!$this->record) {
            return $table;
        }

        return $table
            ->paginated(false)
            ->query(
                QuestionAnswer::query()
                    ->where('quiz_user_id', $this->record->id)
                    ->with('question', 'answer')
            )
            ->columns([
                Tables\Columns\TextColumn::make('question.title')
                    ->label(__('messages.common.question'))
                    ->wrap()
                    ->width(500)
                    ->weight(2),

                Tables\Columns\TextColumn::make('answer.title')
                    ->label(__('messages.common.answer'))
                    ->wrap()
                    ->alignCenter()
                    ->formatStateUsing(function (string $state, $record) {
                        $html = '<div class="flex flex-wrap gap-x-2 gap-y-1">';
                        if ($record->is_time_out) {
                            $html .= '<div class="flex w-max"><span style="--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);" class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger">';
                            $html .= '<span class="mt-1.5"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"><path fill="#dc2626" d="M12 1.75a10.25 10.25 0 1 1-10.25 10.25A10.262 10.262 0 0 1 12 1.75Zm0 18.5a8.25 8.25 0 1 0-8.25-8.25 8.259 8.259 0 0 0 8.25 8.25Z" /><path fill="#dc2626" d="M12.75 7.25a.75.75 0 0 0-1.5 0v5a.75.75 0 0 0 .38.65l3.5 2a.75.75 0 1 0 .73-1.3l-3.11-1.78V7.25Z" /></svg></span><span class="truncate">';
                            $html .= __('messages.common.time_out');
                            $html .= '</span></span></div>';
                        } else {
                            if ($this->record->quiz->quiz_type == Quiz::MULTIPLE_CHOICE) {
                                $multiAnswer = $record->multi_answer;
                                if ($multiAnswer) {
                                    foreach ($multiAnswer as $key => $answerId) {
                                        $answer = Answer::find($answerId)?->toArray();
                                        $isCurrent = (int) $answer['is_correct'];
                                        $classColor = $isCurrent ? 'fi-color-success' : 'fi-color-danger';
                                        $isStyle = $isCurrent ? '--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);' : '--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);';
                                        $html .= '<div class="flex w-max"><span style="' . $isStyle . '" class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 ' . $classColor . '"><span class="grid"><span class="truncate">';
                                        $html .= $answer['title'] ?? '';
                                        $html .= '</span></span></span></div>';
                                    }
                                }
                            } else {
                                $isCurrent = $record->is_correct;
                                $classColor = $isCurrent ? 'fi-color-success' : 'fi-color-danger';
                                $isStyle = $isCurrent ? '--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);' : '--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);';
                                $html .= '<div class="flex w-max"><span style="' . $isStyle . '" class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 ' . $classColor . '"><span class="grid"><span class="truncate">';
                                $html .= $record->answer->title ?? '';
                                $html .= '</span></span></span></div>';
                            }
                        }
                        $html .= '</div>';
                        return new HtmlString($html);
                    })
                    ->default(function ($record) {
                        if (empty($record->answer)) {
                            return $record->is_time_out ?  __('messages.common.time_out') : '';
                        }
                        return;
                    }),
                Tables\Columns\TextColumn::make('time_taken')
                    ->label(__('messages.participant.time_taken'))
                    ->default(fn($record) => calculateAnswerTime($record, $this->record)),
            ]);
    }
}
