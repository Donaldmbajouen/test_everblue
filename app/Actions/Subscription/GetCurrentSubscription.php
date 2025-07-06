<?php

namespace App\Actions\Subscription;

use App\Enums\PlanFrequency;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCurrentSubscription
{
    use AsAction;

    public function handle(): array
    {
        $currentPlan = Subscription::where('user_id', auth()->id())->where('status', SubscriptionStatus::ACTIVE->value)->first();

        if (!empty($currentPlan) && !$currentPlan->isExpired()) {
            $currentPlan['currency_icon'] = $currentPlan['plan']['currency']['symbol'];
            $currentPlan['used_days'] = round(abs(now()->diffInDays($currentPlan['starts_at'])));

            if ($currentPlan['plan_frequency'] == PlanFrequency::MONTHLY->value) {
                $currentPlan['total_days'] = 30;
            } elseif ($currentPlan['plan_frequency'] == PlanFrequency::WEEKLY->value) {
                $currentPlan['total_days'] = 7;
            } elseif ($currentPlan['plan_frequency'] == PlanFrequency::YEARLY->value) {
                $currentPlan['total_days'] = 365;
            }

            $currentPlan['remaining_days'] = round($currentPlan['total_days'] - $currentPlan['used_days']);
            $perDayPrice = round($currentPlan['plan_amount'] / $currentPlan['total_days'], 2);
            $currentPlan['remaining_balance'] = round($currentPlan['plan_amount'] - ($perDayPrice * $currentPlan['used_days']));
            $currentPlan['used_balance'] = round($currentPlan['plan_amount'] - $currentPlan['remaining_balance']);
        } else {
            return $currentPlan = [];
        }

        return $currentPlan->toArray();
    }
}
