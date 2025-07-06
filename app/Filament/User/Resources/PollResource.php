<?php

namespace App\Filament\User\Resources;

use App\Enums\UserSidebar;
use App\Filament\User\Resources\PollResource\Pages;
use App\Models\Poll;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PollResource extends Resource
{
    protected static ?string $model = Poll::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?int $navigationSort = UserSidebar::POLL->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.poll.polls');
    }

    public static function getPluralLabel(): ?string
    {
        return __('messages.poll.polls');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Poll::getForm());
    }

    public static function table(Table $table): Table
    {
        $userId = Auth::id();
        return $table
            ->paginated([10, 25, 50, 100])
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment(getActiveLanguage()['code'] == 'ar' ? 'start' : 'end')
            ->query(Poll::whereHas('user', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }))
            ->columns([
                TextColumn::make('question')
                    ->label(__('messages.common.questions'))
                    ->searchable()
                    ->description(fn($record) => route('poll.create', ['code' => $record->unique_code]))
                    ->icon('heroicon-o-document-duplicate')
                    ->iconPosition(IconPosition::After)
                    ->copyable()
                    ->copyableState(fn($record) => route('poll.create', ['code' => $record->unique_code])),
                TextColumn::make('poll_results_count')
                    ->label(__('messages.poll.total_voting'))
                    ->counts('pollResults'),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->modalHeading(__('messages.poll.delete_poll'))->successNotificationTitle(__('messages.poll.poll_deleted_successfully'))->size('lg'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    \App\Filament\Actions\CustomDeleteBulkAction::make()
                        ->setCommonProperties()
                        ->modalHeading(__('messages.poll.delete_selected_poll'))
                        ->successNotificationTitle(__('messages.poll.polls_deleted_success')),
                ])
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
            'index' => Pages\ListPolls::route('/'),
            'create' => Pages\CreatePoll::route('/create'),
            'view' => Pages\ViewPoll::route('/{record}'),
            'edit' => Pages\EditPoll::route('/{record}/edit'),
        ];
    }
}
