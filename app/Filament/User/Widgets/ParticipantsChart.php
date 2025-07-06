<?php

namespace App\Filament\User\Widgets;

use App\Models\Quiz;
use App\Models\UserQuiz;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ParticipantsChart extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '400px';

    public ?string $filter = null;

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $query = UserQuiz::whereHas('quiz', fn($query) => $query->where('user_id', Auth::id()))->selectRaw('DATE(created_at) as date, COUNT(*) as total');

        if ($this->filter && $this->filter > 0) {
            $query->whereHas('quiz', function ($query) {
                $query->whereId($this->filter);
            });
        }

        $participants = $query->groupBy('date')
            ->orderBy('date')
            ->get();

        if ($participants->count() > 1) {
            foreach ($participants as $participant) {
                $data[] = $participant->total;
                $labels[] = Carbon::parse($participant->date)->format('d/m/Y');
            }
        } else {
            $data[] = null;
            $labels[] = null;
            foreach ($participants as $participant) {
                $data[] = $participant->total;
                $labels[] = Carbon::parse($participant->date)->format('d/m/Y');
            }
            $data[] = null;
            $labels[] = null;
        }

        return [
            'datasets' => [
                [
                    'label' => __('messages.dashboard.total_participants'),
                    'data' => $data,
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels,
        ];
    }



    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        $quizzes = Quiz::where('user_id', getLoggedInUserId())->get()->pluck('title', 'id')->toArray();

        return [0 => __('messages.dashboard.all_quizzes')] + $quizzes ?? [];
    }


    public function getHeading(): string
    {
        return __('messages.dashboard.participants');
    }
}
