<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '400px';

    public ?string $filter = 'week';


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
        $data = [];
        $labels = [];
        $currentFilter = $this->filter;

        if ($currentFilter == 'today') {
            $totalAmount = Subscription::whereDate('created_at', Carbon::now())->sum('payable_amount');
            $labels = ['', Carbon::now()->format('d/m/Y'), ''];
            $data = ['', $totalAmount, ''];
        } elseif ($currentFilter == 'week') {
            $subscriptions = Subscription::whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])->get();
            for ($i = 6; $i >= 0; $i--) {
                $data[] = $subscriptions
                    ->where('created_at', '>=', Carbon::now()->subDays($i)->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->subDays($i)->endOfDay())
                    ->sum('payable_amount');
                $label = Carbon::now()->subDays($i)->format('d/m/Y');
                $labels[] = $label;
            }
        } elseif ($currentFilter == 'month') {
            $subscriptions = Subscription::whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])->get();
            for ($i = 29; $i >= 0; $i--) {
                $data[] = $subscriptions
                    ->where('created_at', '>=', Carbon::now()->subDays($i)->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->subDays($i)->endOfDay())
                    ->sum('payable_amount');
                $label = Carbon::now()->subDays($i)->format('d/m/Y');
                $labels[] = $label;
            }
        } elseif ($currentFilter == 'year') {
            $startOfYear = Carbon::now()->startOfYear();
            $endOfYear = Carbon::now()->endOfYear();
            $interval = $startOfYear->diffInMonths($endOfYear);
            for ($i = 0; $i <= $interval; $i++) {
                $label = $startOfYear->copy()->addMonths($i)->format('M');
                $labels[] = __('messages.month.' . $label);
                $data[] = Subscription::whereYear('created_at', $startOfYear->copy()->addMonths($i)->year)
                    ->whereMonth('created_at', $startOfYear->copy()->addMonths($i)->month)
                    ->sum('payable_amount');
            }
        }


        return [
            'datasets' => [
                [
                    'label' => __('messages.dashboard.total_revenue'),
                    'data' => $data,
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels,
        ];
    }



    protected function getFilters(): ?array
    {
        return [
            'today' => __('messages.dashboard.today'),
            'week' => __('messages.dashboard.last_week'),
            'month' => __('messages.dashboard.last_month'),
            'year' => __('messages.dashboard.this_year'),
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        return __('messages.dashboard.revenue_by_dates');
    }
}
