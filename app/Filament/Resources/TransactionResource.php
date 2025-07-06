<?php

namespace App\Filament\Resources;

use App\Enums\AdminSidebar;
use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Subscription;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-m-clipboard-document-list';

    protected static ?int $navigationSort = AdminSidebar::TRANSACTIONS->value;

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('messages.subscription.no_transactions'))
            ->recordUrl(false)
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([10, 25, 50, 100])
            ->defaultSort('id', 'desc')
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment('end')
            ->columns([
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label(__('messages.subscription.transaction_id'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('messages.subscription.amount'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        $currencySymbol = $record->subscription?->plan?->currency?->symbol ?? '$';
                        return getCurrencyPosition() ? $currencySymbol . ' ' . number_format($record->amount ?? 0, 2) : number_format($record->amount ?? 0, 2) . ' ' . $currencySymbol;
                    })
                    ->default('-'),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('messages.subscription.type'))
                    ->formatStateUsing(function ($state) {
                        $paymentType = Subscription::getPaymentType();
                        return $paymentType[$state] ?? __('messages.common.n/a');
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('messages.user.user_name'))
                    ->description(fn($record) => $record->user->email)
                    ->searchable(['name', 'email'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('messages.common.status'))
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return $state ? __('messages.subscription.success') : __('messages.subscription.failed');
                    })
                    ->colors([
                        'success' => fn($state) => $state,
                        'danger' => fn($state) => !$state,
                    ]),
            ])
        ;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.subscription.transactions');
    }
}
