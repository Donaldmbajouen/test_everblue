<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Filament\Notifications\Notification;
use Filament\Models\Contracts\FilamentUser;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use AbanoubNassem\FilamentGRecaptchaField\Forms\Components\GRecaptcha;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class CustomLogin extends BaseLogin
{

    /**
     * @var view-string
     */
    protected static string $view = 'filament.auth.login';


    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent()->label(__('messages.user.email_address') . ':')->validationAttribute(__('messages.user.email_address'))->placeholder(__('messages.user.email_address')),
                        $this->getPasswordFormComponent()->label(__('messages.user.password') . ':')->validationAttribute(__('messages.user.password'))->placeholder(__('messages.user.password'))
                            ->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()" tabindex="3"> {{ __("messages.home.forgot_password") }}</x-filament::link>')) : null)
                            ->extraAttributes(['class' => 'password-field']),
                        // GRecaptcha::make('captcha')->visible(function () {
                        //     return enableCaptcha() && checkCaptcha('enabled_captcha_in_login');
                        // }),
                        $this->getRememberFormComponent()->label(__('messages.home.remember_me')),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction()
                ->extraAttributes(['class' => 'w-full flex items-center justify-center space-x-3 form-submit'])
                ->label(__('messages.home.sign_in')),
        ];
    }


    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (isset($data['email']) && !empty($data['email'])) {
            $user = User::whereEmail($data['email'])->first();
            if ($user) {
                // if ($user->email_verified_at == null) {
                //     Notification::make()
                //         ->title(__('messages.user.email_not_verified'))
                //         ->danger()
                //         ->send();

                //     return null;
                // }
                if ($user->status == false) {
                    Notification::make()
                        ->title(__('messages.user.account_deactivate'))
                        ->danger()
                        ->send();

                    return null;
                }
            }
        }

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
