<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('messages.common.back'))
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make([
                    SpatieMediaLibraryImageEntry::make('profile')
                        ->label('')
                        ->disk(config('app.media_disk'))
                        ->collection(User::PROFILE),
                    Group::make([
                        TextEntry::make('name')
                            ->label(__('messages.common.name') . ':'),
                        TextEntry::make('email')
                            ->label(__('messages.user.email') . ':'),
                        TextEntry::make('status')
                            ->label(__('messages.common.status') . ':')
                            ->formatStateUsing(fn($state) => $state ? __('messages.common.active') : __('messages.common.inactive'))
                            ->badge()
                            ->color(fn($state) => $state ? 'success' : 'danger'),
                        TextEntry::make('email_verified_at')
                            ->label(__('messages.user.email_verified') . ':')
                            ->default('')
                            ->formatStateUsing(fn($state) => $state ? __('messages.user.verified') : __('messages.user.unverified'))
                            ->badge()
                            ->color(fn($state) => $state ? 'success' : 'danger'),
                    ])->columns(2)->columnSpan(2)
                ])->columns(3),
            ])->columns(3);
    }

    public function getTitle(): string
    {
        return __('messages.user.view_user');
    }
}
