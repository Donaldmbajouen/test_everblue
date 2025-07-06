<?php

namespace App\Filament\User\Resources\QuizzesResource\Widgets;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\UserQuiz;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class QuizReportOverview extends Widget
{
    protected static string $view = 'filament.user.resources.quizzes-resource.widgets.quiz-report-overview';

    public ?Model $record = null;

    protected function getViewData(): array
    {
        if (!$this->record) {
            return [
                'players' => 0,
                'questions' => 0,
                'time' => 0,
                'views' => 0,
                'startedPercentage' => 0,
                'avgTime' => '00:00',
                'completedPercentage' => 0,
            ];
        }

        $quizId = $this->record->id;

        $quizViewCount = Quiz::where('id', $quizId)->sum('view_count');
        $quizQuestionCount = Question::where('quiz_id', $quizId)->count();
        $playersQuiz = UserQuiz::where('quiz_id', $quizId)->count();
        $quizzesStarted = UserQuiz::where('quiz_id', $quizId)->whereNotNull('started_at')->count();
        $quizzesCompleted = UserQuiz::where('quiz_id', $quizId)->whereNotNull('completed_at')->count();

        $quizStartedPercentage = 0;
        $quizCompletedPercentage = 0;
        $averageTime = '00:00';
        $totalTime = '00:00';

        if ($playersQuiz > 0) {
            $quizStartedPercentage = $quizzesStarted . ' (' . number_format(($quizzesStarted / $playersQuiz) * 100, 2) . '%)';
            $quizCompletedPercentage = $quizzesCompleted . ' (' . number_format(($quizzesCompleted / $playersQuiz) * 100, 2) . '%)';
        }

        $completedQuizzes = UserQuiz::where('quiz_id', $quizId)
            ->whereNotNull('completed_at')
            ->whereNotNull('started_at')
            ->get();

        if ($completedQuizzes->count() > 0) {
            $totalSeconds = 0;

            foreach ($completedQuizzes as $quiz) {
                $start = Carbon::parse($quiz->started_at);
                $end = Carbon::parse($quiz->completed_at);
                $totalSeconds += $end->diffInSeconds($start);
            }
            $totalHours = floor($totalSeconds / 3600);
            $totalMinutes = floor(($totalSeconds % 3600) / 60);
            $totalSecondsRemainder = $totalSeconds % 60;


            if ($totalHours > 0) {
                $totalTime = sprintf('%02d:%02d' . ' ' . __('messages.common.hours'), $totalHours, $totalMinutes);
            } elseif ($totalMinutes > 0) {
                $totalTime = sprintf('%02d:%02d' . ' ' . __('messages.common.minutes'), $totalMinutes, $totalSecondsRemainder);
            } else {
                $totalTime = sprintf('%02d' . ' ' . __('messages.common.seconds'), $totalSecondsRemainder);
            }

            $totalAvg = $totalSeconds / $completedQuizzes->count();
            $avgHours = floor($totalAvg / 3600);
            $avgMinutes = floor(($totalAvg % 3600) / 60);
            $avgSeconds = $totalAvg % 60;

            if ($avgHours > 0) {
                $averageTime = sprintf('%02d:%02d' . ' ' . __('messages.common.hours'), $avgHours, $avgMinutes);
            } elseif ($avgMinutes > 0) {
                $averageTime = sprintf('%02d:%02d' . ' ' . __('messages.common.minutes'), $avgMinutes, $avgSeconds);
            } else {
                $averageTime = sprintf('%02d' . ' ' . __('messages.common.seconds'), $avgSeconds);
            }
        }
        return [
            'players' => $playersQuiz,
            'questions' => $quizQuestionCount,
            'time' => $totalTime,
            'views' => $quizViewCount,
            'startedPercentage' => $quizStartedPercentage,
            'avgTime' => $averageTime,
            'completedPercentage' => $quizCompletedPercentage,
        ];
    }
}
