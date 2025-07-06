<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Enums\AdminSettingSidebar;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Setting;

class SeoSetting extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.clusters.settings.pages.seo-setting';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = AdminSettingSidebar::SEO_SETTINGS->value;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(getSetting()->toArray());
    }

    public function form(Form $form): Form
    {
        $form->model = getSetting();

        return $form
            ->schema(Setting::getSeoSettingsForm())
            ->columns(2)
            ->statePath('data');
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.setting.seo_settings');
    }

    public function getTitle(): string
    {
        return __('messages.setting.seo_settings');
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
            $this->form->getState();
            getSetting()->update($this->form->getState());
            Notification::make()
                ->success()
                ->title(__('messages.setting.seo_settings_updated_success'))
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();
        }
    }
}
