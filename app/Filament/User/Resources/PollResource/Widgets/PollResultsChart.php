<?php

namespace App\Filament\User\Resources\PollResource\Widgets;

use App\Models\Poll;
use App\Models\PollResult;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class PollResultsChart extends Widget
{
    protected static string $view = 'filament.user.resources.poll-resource.widgets.poll-results-chart';

    public ?Model $record = null;

    protected function getViewData(): array
    {
        if (!$this->record) {
            return [
                'pollResults' => [
                    'question' => 'No Data Available',
                    'optionData' => [],
                ],
            ];
        }

        $pollId = $this->record->id;
        $poll = Poll::find($pollId);
        $pollResults = PollResult::where('poll_id', $pollId)->get();

        $optionData = [];
        $options = ['option1', 'option2', 'option3', 'option4'];
        $resultsAns = $pollResults->pluck('answer')->toArray();
        $totalPerAns = array_count_values($resultsAns);
        $totalVotes = count($resultsAns);


        $colors = [
            'option1' => 'rgb(240, 178, 122)',
            'option2' => 'rgb(238, 215, 197)',
            'option3' => 'rgb(201, 228, 202)',
            'option4' => 'rgb(191, 205, 224)',
        ];

        foreach ($options as $option) {
            if (!empty($this->record->$option)) {
                $label = $this->record->$option;
                $optionCount = $totalPerAns[$label] ?? 0;
                $percentage = $totalVotes > 0 ? round(($optionCount / $totalVotes) * 100, 2) : 0;

                $bgColor = $colors[$option] ?? 'indigo';
                $optionData[] = [
                    'label' => $label,
                    'count' => $optionCount,
                    'percentage' => $percentage,
                    'color' => $bgColor,
                ];
            }
        }

        if (empty($optionData)) {
            return [
                'pollResults' => [
                    'question' => $poll->question ?? 'No Data Available',
                    'options' => [],
                ],
            ];
        }

        return [
            'pollResults' => [
                'question' => $poll->question ?? 'No Data Available',
                'options' => $optionData,
            ],
        ];
    }
}
