<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\AdminSettingSidebar;
use App\Filament\Clusters\Settings;
use App\Models\PaymentSetting;
use Exception;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PaymentSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static string $view = 'filament.clusters.settings.pages.payment-settings';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = AdminSettingSidebar::PAYMENT_SETTINGS->value;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(PaymentSetting::first()->toArray() ?? []);
    }

    public function form(Form $form): Form
    {
        $form->model = getPaymentSetting();

        return $form
            ->schema(PaymentSetting::getForm())
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $this->form->getState();
            PaymentSetting::first()->update($this->form->getState());
            Notification::make()
                ->success()
                ->title(__('messages.setting.payment_setting_updated_success'))
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();
        }
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.setting.payment_settings');
    }

    public function getTitle(): string
    {
        return __('messages.setting.payment_settings');
    }
}
