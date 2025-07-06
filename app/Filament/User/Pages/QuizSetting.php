<?php

namespace App\Filament\User\Pages;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Enums\UserSidebar;
use App\Models\UserSetting;
use Exception;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\ToggleButtons;

class QuizSetting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.quiz-setting';

    protected static ?int $navigationSort = UserSidebar::QUIZ_SETTINGS->value;

    public ?array $data = [];
    public static function getNavigationLabel(): string
    {
        return __('messages.setting.quiz_settings');
    }

    public function getTitle(): string
    {
        return __('messages.setting.quiz_settings');
    }

    public function mount()
    {
        $this->form->fill([
            'hide_participant_email_in_leaderboard' => getUserSettings('hide_participant_email_in_leaderboard') ?? 1,
        ]);
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ToggleButtons::make('hide_participant_email_in_leaderboard')
                    ->label(__('messages.setting.hide_participant_email_in_leaderboard') . ':')
                    ->options([
                        '1' => __('messages.setting.show'),
                        '0' => __('messages.setting.hide'),
                    ])
                    ->inline()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        try {
            foreach ($data as $key => $value) {
                $setting = UserSetting::where('key', $key)
                    ->where('user_id', auth()->id())
                    ->first();
                if ($setting) {
                    $setting->update(['value' => $value]);
                } else {
                    UserSetting::create([
                        'user_id' => auth()->id(),
                        'key' => $key,
                        'value' => $value,
                    ]);
                }
            }

            Notification::make()
                ->success()
                ->title(__('messages.setting.quiz_settings_updated_success'))
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();
        }
    }
}
