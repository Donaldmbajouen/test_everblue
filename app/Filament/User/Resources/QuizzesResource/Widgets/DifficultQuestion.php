<?php

namespace App\Filament\User\Resources\QuizzesResource\Widgets;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\Quiz;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class DifficultQuestion extends Widget
{
    protected static string $view = 'filament.user.resources.quizzes-resource.widgets.difficult-question';

    public ?Model $record = null;

    protected function getViewData(): array
    {
        if (!$this->record) {
            return [
                'questions' => [
                    [
                        'title' => 'Difficult Questions',
                        'currentPercentage' => 0,
                        'avgTime' => 0,
                    ],
                ],
            ];
        }

        $quizId = $this->record->id;

        $quizQuestion = Question::where('quiz_id', $quizId)->pluck('id')->toArray();
        $questionAnswer = QuestionAnswer::whereIn('question_id', $quizQuestion)->get();

        $isMultipleChoice = $this->record->quiz_type == Quiz::MULTIPLE_CHOICE;

        $question = $questionAnswer->groupBy('question_id')->map(function ($qesAnsGroup) use ($isMultipleChoice) {
            $totalQuestions = $qesAnsGroup->count();
            $totalAns = $totalQuestions; // Default: for single choice
            $totalCurrentAns = $qesAnsGroup->where('is_correct', 1)->count();
            $totalWrongAns = $qesAnsGroup->where('is_correct', 0)->count();

            // Multi-choice logic
            if ($isMultipleChoice) {
                $totalAns = 0;
                $totalCurrentAns = 0;
                $totalWrongAns = 0;

                foreach ($qesAnsGroup as $questionAnswer) {
                    $multiAnswer = $questionAnswer->multi_answer;
                    if ($multiAnswer) {
                        foreach ($multiAnswer as $key => $answerId) {
                            $answer = Answer::find($answerId)?->toArray();
                            $multiAnswer[$key] = $answer;
                            $totalAns++;
                            if ($answer && ($answer['is_correct'] ?? false)) {
                                $totalCurrentAns++;
                            } else {
                                $totalWrongAns++;
                            }
                        }
                    }
                    $questionAnswer->multi_answer = $multiAnswer;
                }
            }

            $totalSeconds = 0;
            $qesAnsGroup->map(function ($qesAns) use (&$totalSeconds) {
                if ($qesAns->completed_at && $qesAns->created_at) {
                    $start = Carbon::parse($qesAns->created_at);
                    $end = Carbon::parse($qesAns->completed_at);
                    $totalSeconds += $end->diffInSeconds($start);
                }
            });

            if ($totalWrongAns > 0) {
                return [
                    'title' => $qesAnsGroup->first()->question_title,
                    'currentPercentage' => $totalAns > 0 ? round(($totalCurrentAns / $totalAns) * 100, 2) : 0,
                    'avgTime' => $totalQuestions > 0 ? round($totalSeconds / $totalQuestions, 2) : 0,
                    'totalWrong' => $totalWrongAns
                ];
            }

            return null;
        })->filter()->sortByDesc('totalWrong');

        return [
            'questions' => $question->take(5)->toArray(),
        ];
    }
}
