<?php

namespace App\Http\Middleware;

use App\Enums\SubscriptionStatus;
use App\Models\Quiz;
use App\Models\Subscription;
use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckNoOfQuiz
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Route::currentRouteName() == 'filament.user.resources.quizzes.create') {

            $subscription = Subscription::with('plan')
                ->where('status', SubscriptionStatus::ACTIVE)
                ->where('user_id', Auth::id())
                ->first();

            if ($subscription && $subscription->plan) {
                $quizCount = Quiz::where('user_id', Auth::id())->whereBetween('created_at', [$subscription->starts_at, $subscription->ends_at])->count();

                if ($quizCount > $subscription->plan->no_of_quiz) {
                    Notification::make()
                        ->danger()
                        ->title(__('messages.quiz.reached_maximum_no_of_quiz'))
                        ->send();
                    return redirect()->route('filament.user.resources.quizzes.index');
                }
            } else {
                Notification::make()
                    ->danger()
                    ->title(__('You do not have an active subscription.'))
                    ->send();
                return redirect()->route('filament.user.resources.quizzes.index');
            }
        }


        return $next($request);
    }
}
