<?php

namespace App\Filament\Resources;

use App\Enums\AdminSidebar;
use App\Filament\Resources\LanguageResource\Pages;
use App\Filament\Resources\LanguageResource\RelationManagers;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static ?int $navigationSort = AdminSidebar::LANGUAGES->value;

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('name')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\TextInput::make('code')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\Toggle::make('is_active')
    //                 ->required(),
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('messages.common.no_records'))
            ->defaultSort('id', 'asc')
            ->paginated([10, 25, 50, 100])
            ->recordUrl(false)
            ->searchPlaceholder(__('messages.common.search'))
            ->actionsAlignment('end')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('messages.common.name')),
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->label(__('messages.common.code')),
                ToggleColumn::make('is_active')
                    ->sortable()
                    ->label(__('messages.common.is_active'))
                    ->updateStateUsing(function ($record) {

                        if ($record->code === 'en') {
                            Notification::make()
                                ->title(__('messages.common.cannot_deactivate_default_language'))
                                ->danger()
                                ->send();
                            return;
                        }
                        $record->is_active = !$record->is_active;
                        $record->save();
                        return Notification::make()
                            ->title(__('messages.common.status_updated_success'))
                            ->success()
                            ->send();
                        return $record->is_active;
                    })->afterStateUpdated(fn() => Redirect::to(self::getUrl('index'))),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLanguages::route('/'),
            'create' => Pages\CreateLanguage::route('/create'),
            'edit' => Pages\EditLanguage::route('/{record}/edit'),
        ];
    }
}
