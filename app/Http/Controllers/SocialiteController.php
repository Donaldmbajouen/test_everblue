<?php

namespace App\Http\Controllers;

use App\Actions\Subscription\CreateSubscription;
use App\Http\Responses\LoginResponse;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $user = User::firstWhere(['email' => $socialUser->getEmail()]);

        if ($user) {
            $user->update([$provider . '_id' => $socialUser->getId()]);
        } else {
            /** @var User $user */
            $user = User::create([
                'name' => $socialUser['name'],
                'email' => $socialUser['email'],
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt(Str::random(40)),
                'status' => 1,
                $provider . '_id' => $socialUser->getId(),
            ])->assignRole(User::USER_ROLE);

            $plan = Plan::where('assign_default', true)->first();
            if ($plan) {
                $data['plan'] = $plan->load('currency')->toArray();
                $data['user_id'] = $user->id;
                $data['payment_type'] = Subscription::TYPE_FREE;
                if ($plan->trial_days != null && $plan->trial_days > 0) {
                    $data['trial_days'] = $plan->trial_days;
                }
                CreateSubscription::run($data);
            }
        }

        auth()->login($user);

        return app(LoginResponse::class);
    }
}
