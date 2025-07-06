<?php

namespace App\Filament\User\Resources\UserQuizResource\Pages;

use App\Filament\User\Resources\UserQuizResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserQuizzes extends ListRecords
{
    protected static string $resource = UserQuizResource::class;
}
