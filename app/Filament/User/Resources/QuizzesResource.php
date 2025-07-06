<?php

namespace App\Filament\User\Resources;

use App\Enums\UserSidebar;
use App\Filament\User\Resources\QuizzesResource\Pages;
use App\Models\Quiz;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class QuizzesResource extends Resource implements HasForms
{
    use InteractsWithForms;

    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?int $navigationSort = UserSidebar::QUIZ->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.quiz.quizzes');
    }

    public static function getmodelLabel(): string
    {
        return __('messages.quiz.quizzes');
    }

    public static function canCreate(): bool
    {
        $totalQuizzes = Quiz::where('user_id', auth()->id())->count();
        if (getActiveSubscription() && getActiveSubscription()->plan && getActiveSubscription()->plan->no_of_quiz > 0) {
            return $totalQuizzes < getActiveSubscription()->plan->no_of_quiz;
        }
        return true;
    }

    public static function form(Form $form): Form
    {
        $schema = Quiz::getForm();

        return $form
            ->schema($schema)
            ->statePath('data')
        ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Quiz::withCount('quizUser')->where('user_id', Auth::id()))
            ->emptyStateHeading(__('messages.quiz.no_quizzes'))
            ->recordUrl(false)
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([10, 25, 50, 100])
            ->defaultSort('id', 'desc')
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment(getActiveLanguage()['code'] == 'ar' ? 'start' : 'end')
            ->columns([
                TextColumn::make('title')
                    ->wrap()
                    ->label(__('messages.common.title'))
                    ->description(fn($record) => route('quiz-player', ['code' => $record->unique_code]))
                    ->url(fn($record) => route('quiz-player', ['code' => $record->unique_code]), true)
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconPosition(IconPosition::After),
                TextColumn::make('quiz_type')
                    ->label(__('messages.quiz.question_type'))
                    ->formatStateUsing(function ($state, $record) {
                        $options = Quiz::QUIZ_TYPE;

                        return $options[$state] ?? __('messages.common.n/a');
                    }),
                TextColumn::make('quiz_user_count')
                    ->label(__('messages.quiz.participant_count'))
                    ->counts('quizUser')
                    ->alignment(Alignment::Center),
                TextColumn::make('question_count')
                    ->label(__('messages.quiz.no_of_questions'))
                    ->badge()
                    ->alignment(Alignment::Center),
                ToggleColumn::make('status')
                    ->label(__('messages.common.status'))
                    ->updateStateUsing(function ($record, $state) {
                        $record->status = $state;
                        $record->save();
                        Notification::make()
                            ->success()
                            ->title($state ? __('messages.quiz.quiz_active_successfully') : __('messages.quiz.quiz_deactive_successfully'))
                            ->send();
                        return $state;
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('participants')
                    ->label(__('messages.quiz.participants'))
                    ->icon('heroicon-o-users')
                    ->tooltip(__('messages.quiz.participants'))
                    ->url(fn(Quiz $record) => QuizzesResource::getUrl('participant', [$record->id]))
                    ->openUrlInNewTab()
                    ->hiddenLabel()
                    ->visible(fn(Quiz $record): bool => $record->questions()->exists())
                    ->size('lg'),
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel()
                    ->tooltip(__('messages.common.view'))
                    ->size('lg')
                    ->visible(fn(Quiz $record): bool => $record->questions()->exists()),
                Tables\Actions\EditAction::make()->hiddenLabel()->size('lg')->tooltip(__('messages.common.edit')),
                \App\Filament\Actions\CustomDeleteAction::make()
                    ->setCommonProperties()
                    ->successNotificationTitle(__('messages.quiz.quiz_deleted_success'))
                    ->hiddenLabel()
                    ->size('lg'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    \App\Filament\Actions\CustomDeleteBulkAction::make()
                        ->setCommonProperties()
                        ->modalHeading(__('messages.quiz.delete_selected_quizzes'))
                        ->successNotificationTitle(__('messages.quiz.quizzes_deleted_success')),
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
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuizzes::route('/create'),
            'view' => Pages\ViewQuizzes::route('/{record}'),
            'edit' => Pages\EditQuizzes::route('/{record}/edit'),
            'report' => Pages\QuizReport::route('/report/{record}'),
            'participant' => Pages\ParticipantUserReport::route('/participant/{record}'),
            'leaderboard' => Pages\QuizLeaderboard::route('/{record}/leaderboard'),
        ];
    }
}
