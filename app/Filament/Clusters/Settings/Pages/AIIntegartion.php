<?php

namespace App\Filament\Clusters\Settings\Pages;

use Exception;
use App\Models\Quiz;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Enums\AdminSettingSidebar;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;


class AIIntegartion extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static string $view = 'filament.clusters.settings.pages.ai-integartion';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = AdminSettingSidebar::AI_INTEGARTION->value;

    public ?array $data;
    public  $open_ai_model;
    public  $ai_type;
    public  $open_api_key;
    public $gemini_api_key;
    public $gemini_ai_model;

    public function mount(): void
    {
        $settings = getSetting();

        $this->data = [
            'ai_type' => $settings->ai_type,
            'open_api_key' => $settings->open_api_key,
            'open_ai_model' => $settings->open_ai_model,
            'gemini_api_key' => $settings->gemini_api_key,
            'gemini_ai_model' => $settings->gemini_ai_model
        ];

        $this->form->fill($this->data);
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.setting.ai_integration');
    }

    public function getTitle(): string
    {
        return __('messages.setting.ai_integration');
    }
    public static function getSlug(): string
    {
        return 'ai-integration';
    }
    public function form(Form $form): Form
    {
        return $form
            ->live()
            ->schema([
                Select::make('ai_type')
                    ->label(__('messages.setting.choose_ai') . ':')
                    ->placeholder(__('messages.setting.choose_ai'))
                    ->required()
                    ->native(false)
                    ->options(Quiz::AI_TYPES),
                Group::make([
                    TextInput::make('open_api_key')
                        ->label(__('messages.setting.open_api_key') . ':')
                        ->placeholder(__('messages.setting.open_api_key'))
                        ->validationAttribute(__('messages.setting.open_api_key'))
                        ->required(),
                    Select::make('open_ai_model')
                        ->label(__('messages.setting.open_ai_model') . ':')
                        ->options([
                            'gpt-3.5-turbo' => 'gpt-3.5-turbo',
                            'gpt-4' => 'gpt-4',
                            'gpt-4-turbo' => 'gpt-4-turbo',
                            'gpt-4o-mini' => 'gpt-4o-mini',
                        ])
                        ->native(false)
                        ->placeholder(__('messages.setting.open_ai_model'))
                        ->validationAttribute(__('messages.setting.open_ai_model'))
                        ->required(),
                ])
                    ->columns(2)
                    ->hidden(fn($get) => $get('ai_type') == Quiz::GEMINI_AI || $get('ai_type') == null),
                Group::make([
                    TextInput::make('gemini_api_key')
                        ->label(__('messages.setting.gemini_api_key') . ':')
                        ->placeholder(__('messages.setting.gemini_api_key'))
                        ->validationAttribute(__('messages.setting.gemini_api_key'))
                        ->required(),
                    Select::make('gemini_ai_model')
                        ->label(__('messages.setting.gemini_ai_model') . ':')
                        ->options([
                            'Gemini 2.5 Pro (Preview)' => 'Gemini 2.5 Pro (Preview)',
                            'Gemini 2.5 Flash (Preview)' => 'Gemini 2.5 Flash (Preview)',
                            'gemini-2.0-flash' => 'gemini-2.0-flash',
                            'Gemini 2.0 Flash-Lite' => 'Gemini 2.0 Flash-Lite',
                            'Gemini 2.0 Flash Thinking (Experimental)' => 'Gemini 2.0 Flash Thinking (Experimental)',
                            'Gemini 2.0 Flash Live (Preview)' => 'Gemini 2.0 Flash Live (Preview)',
                            'Gemini Nano' => 'Gemini Nano',
                            'Gemini Nano with Multimodality (Pixel 9 series)' => 'Gemini Nano with Multimodality (Pixel 9 series)',
                        ])
                        ->searchable()
                        ->native(false)
                        ->placeholder(__('messages.setting.gemini_ai_model'))
                        ->validationAttribute(__('messages.setting.gemini_ai_model'))
                        ->required(),
                ])
                    ->columns(2)
                    ->hidden(fn($get) => $get('ai_type') == Quiz::OPEN_AI || $get('ai_type') == null),
            ])
            ->statePath('data')
            ->columns(1);
    }

    public function save(): void
    {
        try {
            $state = $this->form->getState();
            getSetting()->update($state);

            Notification::make()
                ->success()
                ->title(__('messages.setting.ai_integration_updated_success'))
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();
        }
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }
}
