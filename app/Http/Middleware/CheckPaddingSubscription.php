<?php

namespace App\Http\Middleware;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPaddingSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request = $next($request);

        $paddingSubscription = Subscription::where('user_id', auth()->id())->where('status', SubscriptionStatus::PENDING->value)->first();

        if ($paddingSubscription !== null && !empty($paddingSubscription)) {
            Notification::make()
                ->danger()
                ->title(__('messages.subscription.manual_transaction_request_is_pending'))
                ->send();
            return redirect()->route('filament.user.pages.manage-subscription');
        }

        return $request;
    }
}
