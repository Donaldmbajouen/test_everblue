<?php

namespace App\Filament\User\Resources\QuizzesResource\Widgets;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\UserQuiz;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class TopScoringParticipant extends Widget
{
    protected static string $view = 'filament.user.resources.quizzes-resource.widgets.top-scoring-participant';

    public ?Model $record = null;

    protected function getViewData(): array
    {
        if (!$this->record) {
            return [
                'participants' => [
                    [
                        'name' => 'John Doe',
                        'email' => 'sH2vz@example.com',
                        'percentage' => 0,
                    ],
                ],
            ];
        }

        $quizId = $this->record->id;

        $quizQuestionCount = Question::where('quiz_id', $quizId)->count();
        $totalAns = $quizQuestionCount;
        $playersQuiz = UserQuiz::where('quiz_id', $quizId)->get()->map(function ($q) use ($totalAns) {
            $results = json_decode($q->result, true);
            if (isset($results['total_current_question']) && isset($results['current_score_percent'])) {
                $q->currentAnswer = $results['total_current_question'];
                $q->currentPercentage = number_format($results['current_score_percent'], 2);
            } else {
                $q->currentAnswer = $q->questionAnswers->where('is_correct', 1)->count();
                if ($q->quiz_type == Quiz::MULTIPLE_CHOICE) {
                    $totalAns = 0;
                    $q->currentAnswer = 0;
                    foreach ($q->questionAnswers as $questionAnswer) {
                        $multiAnswer = $questionAnswer->multi_answer;
                        if ($multiAnswer) {
                            foreach ($multiAnswer as $key => $answerId) {
                                $answer = Answer::find($answerId)?->toArray();
                                $totalAns++;
                                if ($answer && ($answer['is_correct'] ?? false)) {
                                    $q->currentAnswer++;
                                }
                            }
                        }
                    }
                }
                $q->currentPercentage = 0;
                if ($totalAns > 0) {
                    $q->currentPercentage = number_format(($q->currentAnswer / $totalAns) * 100);
                }
            }
            if ($q->currentPercentage > 0) {
                return [
                    'name' => $q->name,
                    'email' => $q->email,
                    'currentAnswer' => $q->currentAnswer,
                    'percentage' => $q->currentPercentage,
                ];
            }
        })->filter()->sortByDesc('currentAnswer')->sortByDesc('percentage')->take(5);

        return [
            'participants' => $playersQuiz,
        ];
    }
}
