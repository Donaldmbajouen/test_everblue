<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Enums\AdminSettingSidebar;
use App\Filament\Clusters\Settings;
use App\Models\Setting;
use Exception;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class HeroSection extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.clusters.settings.pages.hero-section';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = AdminSettingSidebar::HERO_SECTION->value;

    public function mount(): void
    {
        $generalSetting = getSetting();

        if ($generalSetting !== null) {
            $this->form->fill($generalSetting->toArray());
        } else {
            $this->form->fill([]);
        }
    }

    public function form(Form $form): Form
    {
        $form->model = getSetting();

        return $form
            ->schema([
                TextInput::make('hero_sub_title')
                    ->label(__('messages.setting.tagline') . ':')
                    ->placeholder(__('messages.setting.tagline'))
                    ->validationAttribute(__('messages.setting.tagline')),
                TextInput::make('hero_title')
                    ->label(__('messages.quiz.title') . ':')
                    ->placeholder(__('messages.quiz.title'))
                    ->validationAttribute(__('messages.quiz.title')),
                Textarea::make('hero_description')
                    ->label(__('messages.quiz.description') . ':')
                    ->placeholder(__('messages.quiz.description'))
                    ->validationAttribute(__('messages.quiz.description'))
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('hero_section_img')
                    ->label(__('messages.setting.hero_section_img') . ':')
                    ->validationAttribute(__('messages.setting.hero_section_img'))
                    ->image()
                    ->disk(config('app.media_disk'))
                    ->collection(Setting::HERO_SECTION_IMG),
                SpatieMediaLibraryFileUpload::make('login_page_img')
                    ->label(__('messages.setting.login_page_img') . ':')
                    ->validationAttribute(__('messages.setting.login_page_img'))
                    ->image()
                    ->disk(config('app.media_disk'))
                    ->collection(Setting::LOGIN_PAGE_IMG),
            ])
            ->columns(2)
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
            $this->form->getState();
            getSetting()->update($this->form->getState());
            Notification::make()
                ->success()
                ->title(__('messages.setting.hero_section_updated_success'))
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
        return __('messages.setting.hero_section');
    }

    public function getTitle(): string
    {
        return __('messages.setting.hero_section');
    }
}
