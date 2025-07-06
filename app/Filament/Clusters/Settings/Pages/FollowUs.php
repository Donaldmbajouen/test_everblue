<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\AdminSettingSidebar;
use App\Filament\Clusters\Settings;
use App\Models\Setting;
use Exception;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class FollowUs extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static string $view = 'filament.clusters.settings.pages.follow-us';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = AdminSettingSidebar::FOLLOW_US->value;

    public function mount()
    {
        $seeting = Setting::first();

        $this->form->fill([
            'facebook_url' => $seeting->facebook_url,
            'twitter_url' => $seeting->twitter_url,
            'linkedin_url' => $seeting->linkedin_url,
            'instagram_url' => $seeting->instagram_url,
            // 'pinterest_url' => $seeting->pinterest_url,
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
                TextInput::make('facebook_url')
                    ->label(__('messages.setting.facebook') . ':')
                    ->placeholder(__('messages.setting.facebook'))
                    ->prefixIcon('heroicon-o-link')
                    ->url(),
                TextInput::make('twitter_url')
                    ->label(__('messages.setting.twitter') . ':')
                    ->placeholder(__('messages.setting.twitter'))
                    ->prefixIcon('heroicon-o-link')
                    ->url(),
                TextInput::make('linkedin_url')
                    ->label(__('messages.setting.linkedin') . ':')
                    ->placeholder(__('messages.setting.linkedin'))
                    ->prefixIcon('heroicon-o-link')
                    ->url(),
                TextInput::make('instagram_url')
                    ->label(__('messages.setting.instagram') . ':')
                    ->placeholder(__('messages.setting.instagram'))
                    ->prefixIcon('heroicon-o-link')
                    ->url(),
                // TextInput::make('pinterest_url')
                //     ->label(__('messages.setting.pinterest') . ':')
                //     ->placeholder(__('messages.setting.pinterest'))
                //     ->prefixIcon('heroicon-o-link')
                //     ->url(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save()
    {
        try {
            $data = $this->form->getState();
            Setting::first()->update($data);
            Notification::make()
                ->success()
                ->title(__('messages.setting.follow_us_updated_success'))
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();
        }
    }
}
