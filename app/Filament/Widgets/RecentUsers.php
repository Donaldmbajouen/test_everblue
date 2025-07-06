<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentUsers extends BaseWidget
{
    public function getTableHeading(): string
    {
        return __('messages.dashboard.recent_users');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::role(User::USER_ROLE)->limit(5))
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.user.user_name')),
                Tables\Columns\TextColumn::make('subscription.plan.name')
                    ->label(__('messages.plan.plan')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.common.created_at'))
                    ->date('d/m/Y')
                    ->width(100),
            ])
            ->paginated(false);
    }
}
