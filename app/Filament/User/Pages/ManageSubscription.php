<?php

namespace App\Filament\User\Pages;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ManageSubscription extends Page implements HasTable
{
    use InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.user.pages.manage-subscription';

    public ?array $data = [];

    public function getModel(): string
    {
        return Subscription::class;
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('messages.subscription.no_subscriptions'))
            ->query(Subscription::query()->where('user_id', auth()->id()))
            ->paginated([10, 25, 50, 100])
            ->columns([
                TextColumn::make('plan.name')
                    ->label(__('messages.common.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('plan_amount')
                    ->label(__('messages.plan.plan_amount'))
                    ->hidden()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payable_amount')
                    ->label(__('messages.subscription.payable_amount'))
                    ->formatStateUsing(function ($record) {
                        $currencySymbol = $record->plan->currency->symbol;
                        return getCurrencyPosition() ? $currencySymbol . ' ' . number_format($record->payable_amount ?? 0, 2) : number_format($record->payable_amount ?? 0, 2) . ' ' . $currencySymbol;
                    })
                    ->default('-')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('starts_at')
                    ->label(__('messages.subscription.start_date'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label(__('messages.subscription.end_date'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('messages.common.status'))
                    ->formatStateUsing(function (int $state, Subscription $record) {
                        if ($record->ends_at < Carbon::now()) {
                            return __('messages.common.expired');
                        } elseif (SubscriptionStatus::PENDING->value == $state) {
                            return __('messages.common.pending');
                        } elseif (SubscriptionStatus::ACTIVE->value == $state) {
                            return __('messages.common.active');
                        } elseif (SubscriptionStatus::REJECTED->value == $state) {
                            return __('messages.common.rejected');
                        } else {
                            return __('messages.common.closed');
                        }
                    })
                    ->badge()
                    ->color(function (int $state, Subscription $record) {
                        if ($record->ends_at < Carbon::now()) {
                            return 'danger';
                        } elseif (SubscriptionStatus::PENDING->value == $state) {
                            return 'warning';
                        } elseif (SubscriptionStatus::ACTIVE->value == $state) {
                            return 'success';
                        } elseif (SubscriptionStatus::REJECTED->value == $state) {
                            return 'danger';
                        } else {
                            return 'info';
                        }
                    }),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Action::make('invoice')
                    ->visible(function ($record) {
                        return $record->payable_amount > 0;
                    })
                    ->icon('heroicon-o-arrow-down-tray')
                    ->label('')
                    ->tooltip(__('messages.subscription.subscription_invoice'))
                    ->url(function ($record) {
                        return route('subscription.invoice', ['subscription' => $record->id]);
                    }, $openUrlInNewTab = true),
            ]);
    }
}
