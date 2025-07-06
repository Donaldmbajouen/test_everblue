<?php

namespace App\Filament\User\Widgets;

use App\Models\Quiz;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class TopQuizesTable extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getTableHeading(): string
    {
        return __('messages.dashboard.top_quizzes_by_participants');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Quiz::where('user_id', Auth::id())->take(5))
            ->defaultSort('quiz_user_count', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->wrap()
                    ->label(__('messages.participant_result.quiz_name'))
                    ->description(fn($record) => route('quiz-player', ['code' => $record->unique_code]))
                    ->extraAttributes(['style' => 'padding: 8px;']),
                TextColumn::make('quiz_user_count')
                    ->width(100)
                    ->alignCenter()
                    ->badge()
                    ->color('sky')
                    ->label(__('messages.dashboard.participants'))
                    ->counts('quizUser')
                    ->extraAttributes(['class' => 'demodemo12123'])
                    ->extraAttributes(['style' => 'padding: 8px;']),
            ])
            ->paginated(false);
    }
}
