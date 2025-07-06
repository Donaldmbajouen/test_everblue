<?php

namespace App\Filament\User\Resources\QuizzesResource\Pages;

use App\Filament\User\Resources\QuizzesResource;
use App\Filament\User\Resources\UserQuizResource;
use App\Models\QuestionAnswer;
use App\Models\Quiz;
use App\Models\UserQuiz;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action as FilamentAction;
use Filament\Tables\Actions\Action;
use Filament\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ParticipantUserReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = QuizzesResource::class;

    protected static string $view = 'filament.user.resources.quizzes-resource.pages.participant-user-report';

    public Quiz $record;

    public function getTitle(): string
    {
        return __('messages.participant.participants');
    }

    protected function getActions(): array
    {
        return [
            FilamentAction::make('leaderboard')
                ->label(__('messages.quiz_report.leaderboard'))
                ->color('gray')
                ->icon('heroicon-o-trophy')
                ->url(QuizzesResource::getUrl('leaderboard', [$this->record->id])),
            FilamentAction::make('overview')
                ->label(__('messages.common.overview'))
                ->color('gray')
                ->icon('heroicon-o-eye')
                ->url(QuizzesResource::getUrl('view', [$this->record->id])),
            FilamentAction::make('report')
                ->label(__('messages.common.reports'))
                ->color('gray')
                ->icon('heroicon-o-document-chart-bar')
                ->url(QuizzesResource::getUrl('report', [$this->record->id])),
            Actions\EditAction::make()
                ->label(__('messages.common.edit'))
                ->url(QuizzesResource::getUrl('edit', [$this->record->id])),
        ];
    }

    public function table(Table $table): Table
    {
        $quizQuestion = $this->record->questions;

        return $table
            ->emptyStateHeading(__('messages.participant.no_participants_found'))
            ->query(UserQuiz::whereHas('quiz', function ($query) {
                $query->where('quiz_id', $this->record->id);
            }))
            ->paginated([10, 25, 50, 100])
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.common.name'))
                    ->description(fn($record) => $record->name),
                TextColumn::make('score')
                    ->label(__('messages.participant.correct_answers'))
                    ->viewData([
                        'quiz' => $this->record,
                        'quizQuestionIds' => $quizQuestion->pluck('id')->toArray(),
                        'totalQuestions' => $quizQuestion->count(),
                    ])
                    ->view('filament.user.resources.quizzes-resource.widgets.correct-answers-column'),
                TextColumn::make('unanswered')
                    ->label(__('messages.participant.unanswered'))
                    ->alignCenter()
                    ->default(__('messages.common.n/a'))
                    ->formatStateUsing(function ($record) use ($quizQuestion) {
                        $unAns = 0;
                        $results = json_decode($record->result, true);
                        if (isset($results['total_unanswered'])) {
                            $unAns = (int) $results['total_unanswered'];
                            return ($unAns > 0) ? $unAns : __('messages.common.n/a');
                        }
                        $quizQuestionIds = $quizQuestion->pluck('id')->toArray();
                        $totalQuestions = count($quizQuestionIds);
                        $userQesAns = QuestionAnswer::whereIn('question_id', $quizQuestionIds)
                            ->where('quiz_user_id', $record->id)
                            ->get();
                        $qesAnsCount = $userQesAns->count();
                        if ($qesAnsCount < $totalQuestions) {
                            $unAns = $totalQuestions - $qesAnsCount;
                        }
                        $unComplateQueAns = $userQesAns->whereNull('completed_at')->count();
                        if ($unComplateQueAns > 0) {
                            $unAns += $unComplateQueAns;
                        }
                        return ($unAns > 0) ? $unAns : __('messages.common.n/a');
                    }),
            ])
            ->actions([
                // Action::make('forceStopQuiz')
                //     ->tooltip('Force Stop Quiz')
                //     ->icon('heroicon-o-play-circle')
                //     ->iconButton()
                //     ->requiresConfirmation()
                //     ->color('warning')
                //     ->modalIcon('heroicon-o-stop')
                //     ->modalHeading('Forcefully Stop Active Quiz')
                //     ->modalDescription('Are you sure you want to force stop this quiz? This action will mark the quiz as completed even if the participant hasn’t finished it.')
                //     ->hidden(fn($record) => $record->completed_at)
                //     ->action(function ($record) {
                //         dd($record);
                //     }),
                Action::make('view')
                    ->label(__('messages.common.view'))
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn($record) => UserQuizResource::getUrl('view', [$record->id]))
                    ->openUrlInNewTab(),
            ]);
    }
}
