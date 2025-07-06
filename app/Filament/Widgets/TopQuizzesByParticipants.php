<?php

namespace App\Filament\Widgets;

use App\Models\Quiz;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopQuizzesByParticipants extends BaseWidget
{
    public function getTableHeading(): string
    {
        return __('messages.dashboard.top_quizzes_by_participants');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Quiz::limit(5))
            ->defaultSort('quiz_user_count', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('quiz_user_count')
                    ->counts('quizUser')
                    ->label(__('messages.quiz.quiz_participants'))
                    ->formatStateUsing(function ($record) {
                        return $record->title . ' (' . $record->quiz_user_count . ')';
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('messages.user.user_name')),
            ])
            ->paginated(false);
    }
}
