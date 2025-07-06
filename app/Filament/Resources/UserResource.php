<?php

namespace App\Filament\Resources;

use App\Enums\AdminSidebar;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-m-users';

    protected static ?int $navigationSort = AdminSidebar::TUTOR->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.user.users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(User::getForm());
    }

    public static function table(Table $table): Table
    {
        $table->modifyQueryUsing(function ($query) {
            $query->whereDoesntHave('roles', function ($query) {
                $query->where('name', User::ADMIN_ROLE);
            });
        });

        return $table
            ->emptyStateHeading(__('messages.user.no_users'))
            ->recordUrl(false)
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([10, 25, 50, 100])
            ->defaultSort('id', 'desc')
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment(getActiveLanguage()['code'] == 'ar' ? 'start' : 'end')
            ->columns([
                SpatieMediaLibraryImageColumn::make('profile_url')
                    ->collection(User::PROFILE)
                    ->label(__('messages.user.profile'))
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->label(__('messages.common.name'))
                    ->description(function (User $record) {
                        return $record->email;
                    })
                    ->searchable(['name', 'email'])
                    ->sortable()
                    ->url(function (User $record) {
                        return UserResource::getUrl('view', ['record' => $record->id]);
                    }),
                TextColumn::make('quizzes_and_polls_count')
                    ->label(__('messages.user.quizzes_and_polls'))
                    ->default(function (User $record) {
                        $quizzesCount = $record->quizzes()->count();
                        $pollsCount = $record->polls()->count();

                        return "{$quizzesCount}/{$pollsCount}";
                    })
                    ->alignCenter(),
                ToggleColumn::make('email_verified_at')
                    ->label(__('messages.user.email_verified'))
                    ->disabled(fn($record) => ! is_null($record->email_verified_at))
                    ->updateStateUsing(function ($record, $state) {
                        if (! $record->email_verified_at) {
                            $record->email_verified_at = $state ? now() : null;
                            $record->save();
                            Notification::make()->success()
                                ->title(__('messages.user.email_verified_success'))
                                ->send();
                        }
                    })
                    ->alignCenter(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label(__('messages.common.status'))
                    ->afterStateUpdated(function ($state) {
                        Notification::make()
                            ->success()
                            ->title(($state) ? __('messages.user.user_activated') : __('messages.user.user_deactivated'))
                            ->send();
                    })
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label(__('messages.common.created_at'))
                    ->date('d M, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        '1' => __('messages.common.active'),
                        '0' => __('messages.common.inactive'),
                    ])
                    ->label(__('messages.common.status') . ':')
                    ->placeholder(__('messages.common.all'))
                    ->native(false),
                TernaryFilter::make('email_verified_at')
                    ->label(__('messages.user.email_verified') . ':')
                    ->native(false)
                    ->nullable()
                    ->placeholder(__('messages.common.all')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('messages.common.edit'))
                    ->tooltip(__('messages.common.edit')),
                \App\Filament\Actions\CustomDeleteAction::make()
                    ->setCommonProperties()
                    ->modalHeading(__('messages.user.delete_user'))
                    ->successNotificationTitle(__('messages.user.user_deleted_success')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    \App\Filament\Actions\CustomDeleteBulkAction::make()
                        ->setCommonProperties()
                        ->modalHeading(__('messages.user.delete_selected_users'))
                        ->successNotificationTitle(__('messages.user.users_deleted_success')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
