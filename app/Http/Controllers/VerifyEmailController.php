<?php

namespace App\Http\Controllers;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            Notification::make()
                ->success()
                ->title(__('messages.home.email_already_verified'))
                ->send();
            return redirect()->route('filament.auth.auth.login');
        }

        if ($user->markEmailAsVerified()) {
            Notification::make()
                ->success()
                ->title(__('messages.home.your_email_verified_success'))
                ->send();
            return redirect()->route('filament.auth.auth.login');
        }

        Notification::make()
            ->danger()
            ->title(__('messages.home.your_email_verification_failed'))
            ->send();
        return redirect()->route('filament.auth.auth.login');
    }
}
