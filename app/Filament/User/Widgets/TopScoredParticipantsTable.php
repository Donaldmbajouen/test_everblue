<?php

namespace App\Filament\User\Widgets;

use App\Models\UserQuiz;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class TopScoredParticipantsTable extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getTableHeading(): string
    {
        return __('messages.quiz_report.top_scoring_participants');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(UserQuiz::whereHas('quiz', fn($query) => $query->where('user_id', Auth::id()))->take(5))
            ->defaultSort('score', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.participant_result.participant_name'))
                    ->description(fn($record) => $record->quiz->title)
                    ->extraAttributes(['style' => 'padding: 8px;']),
                TextColumn::make('score')
                    ->width(100)
                    ->alignCenter()
                    ->badge()
                    ->color('sky')
                    ->label(__('messages.participant_result.score'))
                    ->extraAttributes(['style' => 'padding: 8px;'])
            ])
            ->paginated(false);
    }
}
