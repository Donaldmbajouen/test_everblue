<?php

namespace App\Filament\User\Pages;

use App\Enums\SubscriptionStatus;
use App\Http\Middleware\CheckPaddingSubscription;
use App\Models\Plan;
use App\Models\Subscription;
use Filament\Pages\Page;

class UpgradeSubscription extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.user.pages.upgrade-subscription';

    /**
     * @var string | array<string>
     */
    protected static string|array $routeMiddleware = [
        CheckPaddingSubscription::class,
    ];


    protected function getViewData(): array
    {
        $data = [];

        $data['tabs'] = [
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'yearly' => 'Yearly',
        ];

        $data['currentActivePlan'] = Subscription::with('plan')->where('user_id', auth()->id())->where('status', SubscriptionStatus::ACTIVE->value)->first();

        $data['plans'] = Plan::where('status', true)
            ->where('assign_default', false)
            ->get()->groupBy('frequency')->map(function ($plans) {
                return $plans->map(function ($plan) {
                    return [
                        'id' => $plan->id,
                        'name' => $plan->name,
                        'price' => $plan->price,
                        'currency_icon' => $plan->currency->symbol,
                        'trial_days' => $plan->trial_days,
                        'no_of_quiz' => $plan->no_of_quiz,
                        'assign_default' => $plan->assign_default,
                    ];
                });
            });

        return $data;
    }
}
