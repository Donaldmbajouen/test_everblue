<?php

namespace App\Filament\User\Resources\PollResource\Pages;

use App\Filament\User\Resources\PollResource;
use App\Filament\User\Resources\PollResource\Widgets\PollResultsChart;
use App\Filament\User\Resources\PollResource\Widgets\PollResultsSummaryTable;
use App\Filament\User\Resources\PollResource\Widgets\PollResultTable;
use Filament\Actions;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPoll extends ViewRecord
{
    protected static string $resource = PollResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return '';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Livewire::make(PollResultsChart::class),
                Livewire::make(PollResultTable::class),
            ])->columns(1);
    }
}
