<?php

namespace App\Filament\User\Resources\QuizzesResource\Widgets;

use App\Models\Quiz;
use App\Models\UserQuiz;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class QuizOverview extends BaseWidget
{
    // protected static ?int $sort = 1;

    public ?Model $record = null;

    protected function getStats(): array
    {

        if (!$this->record) {
            return [
                Stat::make('views', 0)
                    ->label(__('Views')),
                Stat::make('started', 0)
                    ->label(__('Started')),
                Stat::make('completed', 0)
                    ->label(__('Completed')),
                Stat::make('average_time', '00:00')
                    ->label(__('Average Time')),
            ];
        }

        $quizId = $this->record->id;

        $quizViewCount = Quiz::where('id', $quizId)->sum('view_count');
        $totalQuizzes = UserQuiz::where('quiz_id', $quizId)->count();
        $quizzesStarted = UserQuiz::where('quiz_id', $quizId)->whereNotNull('started_at')->count();
        $quizzesCompleted = UserQuiz::where('quiz_id', $quizId)->whereNotNull('completed_at')->count();

        $quizStartedPercentage = 0;
        $quizCompletedPercentage = 0;
        $averageTime = '00:00';

        if ($totalQuizzes > 0) {
            $quizStartedPercentage = $totalQuizzes . ' (' . number_format(($quizzesStarted / $totalQuizzes) * 100) . '%)';
            $quizCompletedPercentage =  $totalQuizzes . '  (' . number_format(($quizzesCompleted / $totalQuizzes) * 100) . '%)';
        }

        $completedQuizzes = UserQuiz::where('quiz_id', $quizId)
            ->whereNotNull('completed_at')
            ->whereNotNull('started_at')
            ->get();

        if ($completedQuizzes->count() > 0) {
            $totalMinutes = 0;

            foreach ($completedQuizzes as $quiz) {
                $start = Carbon::parse($quiz->started_at);
                $end = Carbon::parse($quiz->completed_at);
                $totalMinutes += $end->diffInMinutes($start);
            }

            $hours = floor($totalMinutes / 60);
            $minutes = $totalMinutes % 60;

            $averageTime = sprintf('%02d:%02d', $hours, $minutes);
        }

        return [
            Stat::make('views', $quizViewCount)
                ->label(__('Views')),
            Stat::make('started', $quizStartedPercentage)
                ->label(__('Started')),
            Stat::make('completed', $quizCompletedPercentage)
                ->label(__('Completed')),
            Stat::make('average_time', $averageTime)
                ->label(__('Average Time')),
        ];
    }
}
