<?php

namespace App\Filament\User\Resources;

use App\Enums\UserSidebar;
use App\Filament\User\Resources\UserQuizResource\Pages;
use App\Models\UserQuiz;
use Carbon\Carbon;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserQuizResource extends Resource
{
    protected static ?string $model = UserQuiz::class;

    protected static ?string $slug = 'participants';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = UserSidebar::PARTICIPANT_USER->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.participant.participants');
    }

    public static function getmodelLabel(): string
    {
        return __('messages.participant.participants');
    }

    public static function table(Table $table): Table
    {
        $userId = Auth::id();
        return $table
            ->recordUrl(false)
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([10, 25, 50, 100])
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment(getActiveLanguage()['code'] == 'ar' ? 'start' : 'end')
            ->query(UserQuiz::whereHas('quiz', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }))
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.common.name'))
                    ->description(fn($record) => $record->email)
                    ->searchable(['name', 'email']),
                TextColumn::make('quiz.title')
                    ->label(__('messages.common.title'))
                    ->searchable(),
                TextColumn::make('quiz_id')
                    ->label(__('messages.participant.answered'))
                    ->badge()
                    ->alignCenter()
                    ->color('success')
                    ->formatStateUsing(fn($record) => getQuestionCount($record)),
                TextColumn::make('started_at')
                    ->label(__('messages.participant.started_at'))
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('d/m/Y h:i A') : '-')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label(__('messages.participant.completed_at'))
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('d/m/Y h:i A') : '-')
                    ->sortable()
                    ->placeholder(__('messages.common.n/a')),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('completed_at')
                    ->label(__('messages.quiz_report.completed') . ':')
                    ->native(false)
                    ->nullable()
                    ->placeholder(__('messages.common.all')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUserQuizzes::route('/'),
            'view' => Pages\ViewUserQuiz::route('/{record}'),
        ];
    }
}
