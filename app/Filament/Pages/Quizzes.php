<?php

namespace App\Filament\Pages;

use App\Models\Quiz;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Enums\AdminSidebar;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\ToggleColumn;

use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;


class Quizzes extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static string $view = 'filament.pages.quizzes';

    protected static ?int $navigationSort = AdminSidebar::QUIZZES->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.quiz.quizzes');
    }

    public function getTitle(): string
    {
        return __('messages.quiz.quizzes');
    }
    public  function table(Table $table): Table
    {
        return $table
            ->query(Quiz::withCount('quizUser'))
            ->emptyStateHeading(__('messages.quiz.no_quizzes'))
            ->recordUrl(false)
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([5, 10, 20, 30])
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
                    ->searchable()
                    ->iconPosition(IconPosition::After),
                TextColumn::make('user.name')
                    ->searchable()
                    ->label(__('messages.user.users')),
                TextColumn::make('quiz_type')
                    ->searchable()
                    ->label(__('messages.quiz.question_type'))
                    ->formatStateUsing(fn($state) => Quiz::QUIZ_TYPE[$state] ?? __('messages.common.n/a')),
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
                ToggleColumn::make('is_show_home') 
                    ->label(__('messages.quiz.show_in_home'))
                    ->updateStateUsing(function ($record, $state) {
                        $record->is_show_home = $state;
                        $record->save();
                        Notification::make()
                            ->success()
                            ->title($state ? __('messages.common.status_updated_success') : __('messages.common.status_updated_success'))
                            ->send();
                        return $state;
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('messages.common.status'))
                    ->native(false)
                    ->options([
                        1 => __('messages.common.active'),
                        0 => __('messages.common.inactive'),
                    ]),
                SelectFilter::make('is_show_home')
                    ->label(__('messages.quiz.show_in_home'))
                    ->native(false)
                    ->options([
                        1 => __('messages.common.active'),
                        0 => __('messages.common.inactive'),
                    ]),
                SelectFilter::make('user_id')
                    ->label(__('messages.user.users'))
                    ->native(false)
                    ->preload()
                    ->multiple()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->placeholder(__('messages.common.all')),
                SelectFilter::make('quiz_type')
                    ->label(__('messages.quiz.question_type'))
                    ->native(false)
                    ->options(Quiz::QUIZ_TYPE)
                    ->placeholder(__('messages.common.all')),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
