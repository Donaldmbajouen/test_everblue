<?php

namespace App\Filament\User\Resources\PollResource\Pages;

use App\Filament\User\Resources\PollResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreatePoll extends CreateRecord
{
    protected static string $resource = PollResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        $data['unique_code'] = generatePollUniqueCode();
        $data['user_id'] = Auth::id();
        $record = parent::handleRecordCreation($data);
        return $record;
    }

    public function getTitle(): string
    {
        return __('messages.poll.create_poll');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('messages.poll.poll_created_successfully');
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
