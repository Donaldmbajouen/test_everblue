<?php

namespace App\Http\Middleware;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $allowedRoutes = [
            'filament.user.pages.manage-subscription',
            'filament.user.pages.upgrade-subscription',
            'filament.user.pages.choose-payment-type',
            'filament.user.auth.login',
            'filament.user.auth.logout',
            'filament.user.auth.logout',
            'filament.user.pages.dashboard',
            'filament.user.auth.profile',
        ];

        if (in_array(Route::currentRouteName(), $allowedRoutes)) {
            return $next($request);
        }

        $subscription = Subscription::with('plan')->where('status', SubscriptionStatus::ACTIVE)
            ->where('user_id', Auth::id())
            ->first();

        if (!$subscription) {
            Notification::make()
                ->danger()
                ->title(__('messages.plan.your_plan_expired_and_choose_plan'))
                ->send();
            return redirect()->route('filament.user.pages.upgrade-subscription');
        }

        if ($subscription->isExpired()) {
            Notification::make()
                ->danger()
                ->title(__('messages.plan.your_plan_expired_and_choose_plan'))
                ->send();
            return redirect()->route('filament.user.pages.manage-subscription');
        }

        return $next($request);
    }
}
