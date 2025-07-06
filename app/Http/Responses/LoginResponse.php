<?php

namespace App\Http\Responses;

use App\Models\User;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Filament\Notifications\Notification;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user) {
            $role = $user->roles()->first();

            if ($role && $role->name === User::ADMIN_ROLE) {
                return redirect()->route('filament.admin.pages.dashboard');
            }

            if ($role && $role->name === User::USER_ROLE) {
                return redirect()->route('filament.user.pages.dashboard');
            }
        }

        return redirect()->route('home');
    }
}
