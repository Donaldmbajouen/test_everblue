<?php

namespace App\Filament\User\Resources\QuizzesResource\Pages;

use App\Filament\User\Resources\QuizzesResource;
use App\Models\QuestionAnswer;
use App\Models\Quiz;
use App\Models\UserQuiz;
use Carbon\Carbon;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Filament\Actions;

class QuizLeaderboard extends Page
{
    protected static string $resource = QuizzesResource::class;

    public Quiz $record;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('overview')
                ->label(__('messages.common.overview'))
                ->color('gray')
                ->icon('heroicon-o-eye')
                ->url(QuizzesResource::getUrl('view', [$this->record->id])),
            Action::make('report')
                ->label(__('messages.common.reports'))
                ->color('gray')
                ->icon('heroicon-o-document-chart-bar')
                ->url(QuizzesResource::getUrl('report', [$this->record->id])),
            Action::make('participant')
                ->label(__('messages.participant.participants'))
                ->color('gray')
                ->icon('heroicon-o-users')
                ->url(QuizzesResource::getUrl('participant', [$this->record->id])),
            Actions\EditAction::make()
                ->label(__('messages.common.edit'))
                ->url(QuizzesResource::getUrl('edit', [$this->record->id])),
        ];
    }

    protected static string $view = 'filament.user.resources.quizzes-resource.pages.quiz-leaderboard';

    protected function getViewData(): array
    {
        $totalQuestionIds = $this->record->questions->pluck('id')->toArray();
        $totalQuestions = count($totalQuestionIds);
        $quizUsers = UserQuiz::selectRaw('*, TIMEDIFF(completed_at, started_at) AS total_time')
            ->where('quiz_id', $this->record->id)
            ->where('score', '>', 0)
            ->orderBy('score', 'desc')->orderBy('total_time', 'asc')->get();

        $quizUsers->map(function ($quizUser, $index) use ($totalQuestionIds, $totalQuestions) {
            $quizUser->number = ($index + 1) . 'th';

            $quizUser->unCompleteStart = 100;
            // calculate percentage
            $results = json_decode($quizUser->result, true);
            if (isset($results['current_score_percent'])) {
                $quizUser->unCompleteStart = number_format($results['current_score_percent'] + $results['wrong_score_percent'], 2);
            } else {
                $unAns = 0;
                $qusAns = QuestionAnswer::whereIn('question_id', $totalQuestionIds)
                    ->where('quiz_user_id', $quizUser->id)->get();
                $qesAnsCount = $qusAns->count();
                if ($qesAnsCount < $totalQuestions) {
                    $unAns = $totalQuestions - $qesAnsCount;
                }
                $unComplateQueAns = $qusAns->whereNull('completed_at')->count();
                if ($unComplateQueAns > 0) {
                    $unAns += $unComplateQueAns;
                }
                $unComplatePer = ($unAns / $totalQuestions) * 100;
                $quizUser->unCompleteStart = 100 - $unComplatePer;
            }

            // calculate time
            $start = Carbon::parse($quizUser->started_at);
            $end = Carbon::parse($quizUser->completed_at);
            $seconds = $start->diffInSeconds($end);
            $quizUser->time = getTimeFormat($seconds);
            return $quizUser;
        });


        return [
            'topThree' => collect($quizUsers)->take(3),
            'quizUsers' => collect($quizUsers)->skip(3),
        ];
    }

    public function getTitle(): string
    {
        return __('messages.quiz_report.quiz_leaderboard');
    }
}
