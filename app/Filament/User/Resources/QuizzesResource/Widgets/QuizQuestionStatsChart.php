<?php

namespace App\Filament\User\Resources\QuizzesResource\Widgets;

use App\Models\Question;
use App\Models\QuestionAnswer;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Model;

class QuizQuestionStatsChart extends ChartWidget
{
    protected static ?string $heading = '';

    // protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'sm';


    public ?string $filter = null;

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
                    'text' => 'Report By Correct / Wrong Answer',
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
        if (!$this->record) {
            return $this->noDataResponse();
        }

        $quizId = $this->record->id;
        $defaultQuestionId = Question::where('quiz_id', $quizId)->first()->id;

        $selectedQuestionId = $this->filter ?: $defaultQuestionId;
        if (!$selectedQuestionId) {
            return $this->noDataResponse();
        }

        $totalAnswers = QuestionAnswer::where('question_id', $selectedQuestionId)->count();

        if ($totalAnswers === 0) {
            return $this->noDataResponse();
        }

        $correctAnswers = QuestionAnswer::where('question_id', $selectedQuestionId)
            ->where('is_correct', true)
            ->count();
        $wrongAnswers = $totalAnswers - $correctAnswers;

        $correctPercentage = $totalAnswers > 0 ? number_format(($correctAnswers / $totalAnswers) * 100, 2) : 0;
        $wrongPercentage = $totalAnswers > 0 ? number_format(($wrongAnswers / $totalAnswers) * 100, 2) : 0;

        $labels = [
            'Correct (' . $correctAnswers . ')',
            'Wrong (' . $wrongAnswers . ')'
        ];
        $data = [$correctPercentage, $wrongPercentage];
        $colors = ['#36A2EB', '#FF6384'];

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
