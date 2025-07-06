<?php

namespace App\Filament\User\Resources\QuizzesResource\Widgets;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionAnswer;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class QuizAnswerStatsChart extends ChartWidget
{

    // protected static ?string $heading = 'Report By Given Answers';

    // protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '400px';

    public ?Model $record = null;

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Report By Given Answers',
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $quizId = $this->record->id;
        if (!$quizId) {
            return $this->noDataResponse();
        }

        $defaultQuestionId = Question::where('quiz_id', $quizId)->first()->id;
        $selectedQuestionId = $this->filter ?: $defaultQuestionId;

        if (!$selectedQuestionId) {
            return $this->noDataResponse();
        }

        $answerCount = QuestionAnswer::where('question_id', $selectedQuestionId)->count();
        if ($answerCount === 0) {
            return $this->noDataResponse();
        }

        $answers = Answer::where('question_id', $selectedQuestionId)->get();
        $chartColor = ['#7414db', '#05cbf2', '#FFCE56', '#4BC0C0'];

        $labels = [];
        $data = [];
        $colors = [];

        foreach ($answers as $index => $answer) {
            $count = QuestionAnswer::where('question_id', $selectedQuestionId)
                ->where('answer_id', $answer->id)
                ->count();
            $correctness = $answer->is_correct ? 'Correct' : 'Incorrect';
            $labels[] = $answer->title . ' (' . $count . ') - ' . $correctness;
            $data[] = $count;
            $colors[] = $chartColor[$index % count($chartColor)];
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getFilters(): ?array
    {
        if (!$this->record) {
            return null;
        }

        $quizId = $this->record->id;
        $questions = Question::where('quiz_id', $quizId)->pluck('title', 'id')->toArray();

        return $questions;
    }

    /**
     * Return a no data response
     *
     * @return array
     */
    protected function noDataResponse(): array
    {
        return [
            'labels' => ['No Data Available'],
            'datasets' => [
                [
                    'data' => [1],
                    'backgroundColor' => ['#e0e0e0'],
                ],
            ],
        ];
    }
}
