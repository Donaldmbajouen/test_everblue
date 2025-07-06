<?php

namespace App\Filament\User\Resources\PollResource\Widgets;

use App\Models\PollResult;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class PollResultTable extends BaseWidget
{
    protected function getTableHeading(): string
    {
        return __('messages.poll.poll_result_by_country');
    }

    public ?Model $record = null;

    public function getTableRecordKey(Model $record): string
    {
        return $record->country . '-' . $record->total_polls;
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('messages.poll.not_poll_result'))
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([5, 10, 20, 30])
            ->query(PollResult::selectRaw('country, COUNT(*) as total_polls')
                ->where('poll_id', $this->record->id)
                ->groupBy('country'))
            ->defaultSort('total_polls', 'desc')
            ->columns([
                TextColumn::make('country')
                    ->label(__('messages.poll.country'))
                    ->default(__('messages.common.n/a'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_polls')
                    ->label(__('messages.common.count'))
                    ->sortable(),
            ]);
    }
}
