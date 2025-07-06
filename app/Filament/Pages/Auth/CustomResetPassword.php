<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\PasswordReset\ResetPassword;
use Filament\Forms\Form;

class CustomResetPassword extends ResetPassword
{
    protected static string $view = 'filament.auth.reset_password';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent()->label(__('messages.user.email_address') . ':')->placeholder(__('messages.user.email_address'))->validationAttribute(__('messages.user.email_address')),
                $this->getPasswordFormComponent()->label(__('messages.user.password') . ':')->placeholder(__('messages.user.password'))->extraAttributes(['class' => 'password-field']),
                $this->getPasswordConfirmationFormComponent()->label(__('messages.user.confirm_password') . ':')->placeholder(__('messages.user.confirm_password'))->extraAttributes(['class' => 'password-field']),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getResetPasswordFormAction()
                ->extraAttributes(['class' => 'w-full flex items-center justify-center space-x-3 form-submit'])
                ->label(__('messages.home.reset_password')),
        ];
    }
}
