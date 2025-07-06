<?php

namespace App\Filament\User\Resources\PollResource\Widgets;

use App\Models\Poll;
use App\Models\PollResult;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class PollResultsSummaryTable extends BaseWidget
{
    protected static ?string $heading = 'Poll Vote Summary';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 4;

    public ?Model $record = null;

    public function table(Table $table): Table
    {
        $pollId = $this->record->id;

        $poll = Poll::find($pollId);
        $totalVotes = PollResult::where('poll_id', $pollId)->count();

        return $table
            ->query(Poll::where('id', $pollId))
            ->paginated(false)
            ->columns([
                TextColumn::make('option1')
                    ->label('Option 1')
                    ->getStateUsing(fn() => $this->getOptionVoteData($pollId, 'option1', $totalVotes))
                    ->hidden(fn() => is_null($poll->option1)),

                TextColumn::make('option2')
                    ->label('Option 2')
                    ->getStateUsing(fn() => $this->getOptionVoteData($pollId, 'option2', $totalVotes))
                    ->hidden(fn() => is_null($poll->option2)),

                TextColumn::make('option3')
                    ->label('Option 3')
                    ->getStateUsing(fn() => $this->getOptionVoteData($pollId, 'option3', $totalVotes))
                    ->hidden(fn() => is_null($poll->option3)),

                TextColumn::make('option4')
                    ->label('Option 4')
                    ->getStateUsing(fn() => $this->getOptionVoteData($pollId, 'option4', $totalVotes))
                    ->hidden(fn() => is_null($poll->option4)),
            ]);
    }

    private function getOptionVoteData(int $pollId, string $optionKey, int $totalVotes): string
    {
        $poll = Poll::where('id', $pollId)->first();

        $optionVotes = PollResult::where('poll_id', $pollId)
            ->where('answer', $poll->$optionKey)
            ->count();

        $percentage = $totalVotes > 0 ? number_format(($optionVotes / $totalVotes) * 100, 2) : 0;

        return "{$poll->$optionKey} - ({$percentage}%)";
    }
}
