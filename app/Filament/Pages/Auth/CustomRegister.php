<?php

namespace App\Filament\Pages\Auth;

use App\Models\Plan;
use App\Models\User;
use App\Models\Subscription;
use Filament\Pages\Auth\Register;
use Filament\Events\Auth\Registered;
use Filament\Notifications\Notification;
use App\Actions\Subscription\CreateSubscription;
use Filament\Http\Responses\Auth\RegistrationResponse;
use AbanoubNassem\FilamentGRecaptchaField\Forms\Components\GRecaptcha;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class CustomRegister extends Register
{
    /**
     * @var view-string
     */
    protected static string $view = 'filament.auth.register';

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent()
                            ->label(__('messages.common.name') . ':')
                            ->placeholder(__('messages.common.name')),
                        $this->getEmailFormComponent()
                            ->label(__('messages.user.email_address') . ':')
                            ->placeholder(__('messages.user.email_address')),
                        $this->getPasswordFormComponent()
                            ->label(__('messages.user.password') . ':')
                            ->placeholder(__('messages.user.password'))
                            ->extraAttributes(['class' => 'password-field']),
                        $this->getPasswordConfirmationFormComponent()
                            ->label(__('messages.user.confirm_password') . ':')
                            ->placeholder(__('messages.user.confirm_password'))
                            ->extraAttributes(['class' => 'password-field']),
                        // GRecaptcha::make('captcha')->visible(function () {
                        //     return enableCaptcha() && checkCaptcha('enabled_captcha_in_register');
                        // }),

                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getRegisterFormAction()
                ->extraAttributes(['class' => 'w-full flex items-center justify-center space-x-3 form-submit mt-5'])
                ->label(__('messages.home.sign_up')),
        ];
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {

            $data = $this->form->getState();

            $data = $this->mutateFormDataBeforeRegister($data);

            $user = $this->handleRegistration($data);

            $user->assignRole(User::USER_ROLE);

            $this->form->model($user)->saveRelationships();

            return $user;
        });

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

        event(new Registered($user));

        // $user->sendEmailVerificationNotification();
        // Notification::make()
        //     ->success()
        //     ->title(__('messages.home.email_verification_link_sent'))
        //     ->send();

        return app(RegistrationResponse::class);
    }
}
