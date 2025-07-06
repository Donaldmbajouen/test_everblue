<?php

namespace App\Filament\Clusters\Settings\Pages;

use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Enums\AdminSettingSidebar;
use App\Filament\Clusters\Settings;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\{TextInput, Toggle, Checkbox, Group};

class GoogleCaptcha extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-ripple';
    protected static string $view = 'filament.clusters.settings.pages.google-captcha';
    protected static ?int $navigationSort = AdminSettingSidebar::GOOGLE_CAPTCHA->value;
    protected static ?string $cluster = Settings::class;

    public $enable_captcha;
    public $captcha_site_key;
    public $captcha_secret_key;
    public $enabled_captcha_in_login;
    public $enabled_captcha_in_register;
    public $enabled_captcha_in_quiz;
    public ?array $data = [];

    public function mount(): void
    {

        $settings = getSetting();
       
        $this->data = [
            'enable_captcha' => $settings->enable_captcha,
            'captcha_site_key' => $settings->captcha_site_key,
            'captcha_secret_key' => $settings->captcha_secret_key,
            'enabled_captcha_in_login' => $settings->enabled_captcha_in_login,
            'enabled_captcha_in_register' => $settings->enabled_captcha_in_register,
            'enabled_captcha_in_quiz' => $settings->enabled_captcha_in_quiz,
        ];
        
        
        $this->form->fill($this->data);

    }

    public static function getNavigationLabel(): string
    {
        return __('messages.setting.google_captcha');
    }

    public function getTitle(): string
    {
        return __('messages.setting.google_captcha');
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('enable_captcha')
                ->inline(false)
                ->live()
                ->label(__('messages.setting.enable_captcha') . ':'),

            TextInput::make('captcha_site_key')
                ->label(__('messages.setting.captcha_site_key') . ':')
                ->hidden(fn ($get) => !$get('enable_captcha'))
                ->live()
                ->placeholder(__('messages.setting.captcha_site_key'))
                ->required()
                ->validationAttribute(__('messages.setting.captcha_site_key')),

            TextInput::make('captcha_secret_key')
                ->label(__('messages.setting.captcha_secret_key') . ':')
                ->placeholder(__('messages.setting.captcha_secret_key'))
                ->hidden(fn ($get) => !$get('enable_captcha'))
                ->live()
                ->required()
                ->validationAttribute(__('messages.setting.captcha_secret_key')),

            Group::make([
                Checkbox::make('enabled_captcha_in_login')->label(__('messages.setting.enable_in_login')),
                Checkbox::make('enabled_captcha_in_register')->label(__('messages.setting.enable_in_register')),
                Checkbox::make('enabled_captcha_in_quiz')->label(__('messages.setting.enable_in_quiz')),
            ])
                ->columns(1)
                ->live()
                ->hidden(fn ($get) => !$get('enable_captcha')),
            ])
            ->columns(1)
            ->statePath('data');
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $state = $this->form->getState();
            getSetting()->update($state);

            Notification::make()
                ->success()
                ->title(__('messages.setting.google_recaptcha_updated_success'))
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();
        }
    }
    

}
