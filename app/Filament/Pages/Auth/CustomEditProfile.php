<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile;

class CustomEditProfile extends EditProfile
{
    public static function getLabel(): string
    {
        return __('messages.user.account_settings');
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Section::make(__('messages.user.your_account_information'))
                            ->columns(4)
                            ->schema([
                                Group::make([
                                    SpatieMediaLibraryFileUpload::make('profile')
                                        ->label(__('messages.user.profile') . ':')
                                        ->validationAttribute(__('messages.user.profile'))
                                        ->disk(config('app.media_disk'))
                                        ->collection(User::PROFILE)
                                        ->image()
                                        ->imagePreviewHeight(150)
                                        ->imageEditor('cropper')
                                        ->required(),
                                ]),
                                Group::make([
                                    TextInput::make('name')
                                        ->label(__('messages.common.name') . ':')
                                        ->placeholder(__('messages.common.name'))
                                        ->validationAttribute(__('messages.common.name'))
                                        ->required()
                                        ->maxLength(255)
                                        ->autofocus(),
                                    TextInput::make('email')
                                        ->label(__('messages.user.email') . ':')
                                        ->placeholder(__('messages.user.email'))
                                        ->validationAttribute(__('messages.user.email'))
                                        ->email()
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true),
                                ])->columnSpan(3)->columns(1),
                            ]),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data'),
            ),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->hasRole('user')) {
            return route('filament.user.pages.dashboard');
        }

        return route('filament.admin.pages.dashboard');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('messages.user.account_settings_updated');
    }
}
