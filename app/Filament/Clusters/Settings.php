<?php

namespace App\Filament\Clusters;

use App\Enums\AdminSidebar;
use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-m-cog';

    protected static ?int $navigationSort = AdminSidebar::SETTINGS->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.setting.settings');
    }
}
