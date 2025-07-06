<?php

namespace App\Filament\Resources;

use App\Enums\AdminSidebar;
use App\Enums\SubscriptionStatus;
use App\Filament\Resources\CashPaymentsResource\Pages;
use App\Models\Subscription;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CashPaymentsResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-m-banknotes';

    protected static ?int $navigationSort = AdminSidebar::CASH_PAYMENTS->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.subscription.cash_payments');
    }

    public static function table(Table $table): Table
    {
        $table = $table->modifyQueryUsing(function ($query) {
            $query->where('payment_type', Subscription::TYPE_MANUALLY);
        });

        return $table
            ->emptyStateHeading(__('messages.subscription.no_cash_payments'))
            ->defaultSort('id', 'desc')
            ->recordAction(null)
            ->paginated([10, 25, 50, 100])
            ->searchPlaceholder(__('messages.common.search'))
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment('end')
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('messages.user.user_name'))
                    ->limit(100)
                    ->wrap()
                    ->sortable(),
                TextColumn::make('plan.name')
                    ->label(__('messages.plan.plan_name'))
                    ->limit(100)
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('plan_amount')
                    ->label(__('messages.plan.plan_amount'))
                    ->formatStateUsing(function ($record) {
                        $currencySymbol = $record->plan->currency->symbol;
                        return getCurrencyPosition() ? $currencySymbol . ' ' . number_format($record->plan_amount ?? 0, 2) : number_format($record->plan_amount ?? 0, 2) . ' ' . $currencySymbol;
                    })
                    ->default('-')
                    ->sortable()
                    ->alignEnd()
                    ->searchable(),
                TextColumn::make('payable_amount')
                    ->label(__('messages.subscription.payable_amount'))
                    ->formatStateUsing(function ($record) {
                        $currencySymbol = $record->plan->currency->symbol;
                        return getCurrencyPosition() ? $currencySymbol . ' ' . number_format($record->payable_amount ?? 0, 2) : number_format($record->payable_amount ?? 0, 2) . ' ' . $currencySymbol;
                    })
                    ->default('-')
                    ->sortable()
                    ->alignEnd()
                    ->searchable(),
                TextColumn::make('starts_at')
                    ->label(__('messages.subscription.start_date'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label(__('messages.subscription.end_date'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
                SelectColumn::make('status')
                    ->label(__('messages.common.status'))
                    ->placeholder(__('messages.common.pending'))
                    ->options([
                        SubscriptionStatus::ACTIVE->value => __('messages.common.approved'),
                        SubscriptionStatus::REJECTED->value => __('messages.common.rejected'),
                    ])
                    ->updateStateUsing(function ($record, $state) {
                        if ($record->status === SubscriptionStatus::PENDING->value) {
                            $record->status = (int) $state;
                            $record->save();

                            if ((int) $record->status === SubscriptionStatus::ACTIVE->value) {
                                Subscription::where('user_id', $record->user_id)
                                    ->whereNot('id', $record->id)
                                    ->whereIn('status', [SubscriptionStatus::ACTIVE->value])
                                    ->update(['status' => SubscriptionStatus::INACTIVE->value]);
                            }

                            Notification::make()
                                ->success()
                                ->title(__('messages.subscription.subscription_status_updated'))
                                ->duration(2000)
                                ->send();
                            return $state;
                        } else {
                            Notification::make()
                                ->danger()
                                ->title(__('messages.subscription.you_cannot_update_subscription_status'))
                                ->duration(2000)
                                ->send();
                        }
                    })
            ])

            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Select::make('status')
                            ->label(__('messages.common.status'))
                            ->native(false)
                            ->options([
                                SubscriptionStatus::ACTIVE->value => __('messages.common.approved'),
                                SubscriptionStatus::REJECTED->value => __('messages.common.rejected'),
                                SubscriptionStatus::PENDING->value => __('messages.common.pending'),
                            ]),

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['status'])) {
                            if ($data['status'] == SubscriptionStatus::ACTIVE->value) {
                                return $query->where('status', SubscriptionStatus::ACTIVE->value);
                            }
                            if ($data['status'] == SubscriptionStatus::REJECTED->value) {
                                return $query->where('status', SubscriptionStatus::REJECTED->value);
                            }

                            if ($data['status'] == SubscriptionStatus::PENDING->value) {
                                $query->where('status', SubscriptionStatus::PENDING->value)->orWhere('status', SubscriptionStatus::INACTIVE->value);
                            }
                        }

                        return $query;
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['status']) {
                            return null;
                        }
                        if ($data['status'] == SubscriptionStatus::PENDING->value) {
                            return __('messages.common.pending');
                        }
                        if ($data['status'] == SubscriptionStatus::ACTIVE->value) {
                            return __('messages.common.approved');
                        }
                        if ($data['status'] == SubscriptionStatus::REJECTED->value) {
                            return __('messages.common.rejected');
                        }
                    })

            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('messages.common.view'))
                    ->tooltip(__('messages.common.view'))
                    ->modalWidth('md')
                    ->modalHeading(__('messages.subscription.subscription_plan_details')),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('user.name')
                    ->label(__('messages.user.user_name') . ':'),
                TextEntry::make('plan.name')
                    ->label(__('messages.plan.plan_name') . ':'),
                TextEntry::make('plan_amount')
                    ->formatStateUsing(function ($record) {
                        $currencySymbol = $record->plan->currency->symbol;
                        return getCurrencyPosition() ? $currencySymbol . ' ' . number_format($record->plan_amount ?? 0, 2) : number_format($record->plan_amount ?? 0, 2) . ' ' . $currencySymbol;
                    })
                    ->label(__('messages.plan.plan_amount') . ':'),
                TextEntry::make('payable_amount')
                    ->formatStateUsing(function ($record) {
                        $currencySymbol = $record->plan->currency->symbol;
                        return getCurrencyPosition() ? $currencySymbol . ' ' . number_format($record->payable_amount ?? 0, 2) : number_format($record->payable_amount ?? 0, 2) . ' ' . $currencySymbol;
                    })
                    ->label(__('messages.subscription.payable_amount') . ':'),
                TextEntry::make('starts_at')
                    ->label(__('messages.subscription.start_date') . ':')
                    ->dateTime('d/m/Y'),
                TextEntry::make('ends_at')
                    ->label(__('messages.subscription.end_date') . ':')
                    ->dateTime('d/m/Y'),
                ViewEntry::make('AttachmentUrl')
                    ->label(__('messages.subscription.attachment') . ':')
                    ->view('infolists.components.attachment-dawnload-view'),
                TextEntry::make('notes')
                    ->label(__('messages.subscription.notes') . ':')
                    ->default(__('messages.common.n/a'))
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCashPayments::route('/'),
        ];
    }
}
