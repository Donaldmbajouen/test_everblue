<?php

namespace App\Filament\User\Resources\PollResource\Pages;

use App\Filament\User\Resources\PollResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPolls extends ListRecords
{
    protected static string $resource = PollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('messages.poll.new_poll')),
        ];
    }
}
