<?php

namespace App\Filament\Resources;

use App\Enums\AdminSidebar;
use App\Enums\SubscriptionStatus;
use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-m-swatch';

    protected static ?int $navigationSort = AdminSidebar::SUBSCRIPTIONS->value;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('ends_at')
                    ->label(__('messages.subscription.end_date') . ':')
                    ->placeholder(__('messages.subscription.end_date'))
                    ->validationAttribute(__('messages.subscription.end_date'))
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->minDate(function ($record) {
                        if ($record && $record->ends_at) {
                            return Carbon::parse($record->ends_at)->format('Y-m-d');
                        }
                        return Carbon::now()->startOfDay()->format('Y-m-d');
                    })
                    ->maxDate(Carbon::now()->copy()->addDays(36500)->format('Y-m-d')),
            ])->columns(1);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('user.name')
                    ->label(__('messages.common.name') . ':'),
                TextEntry::make('plan.name')
                    ->label(__('messages.plan.plan_name') . ':'),
                TextEntry::make('plan_amount')
                    ->formatStateUsing(function ($record) {
                        $currencySymbol = $record->plan->currency->symbol;
                        return $currencySymbol . ' ' . number_format($record->plan_amount ?? 0, 2);
                    })
                    ->default('-')
                    ->label(__('messages.plan.plan_amount') . ':'),
                TextEntry::make('payable_amount')
                    ->formatStateUsing(function ($record) {
                        $currencySymbol = $record->plan->currency->symbol;
                        return $currencySymbol . ' ' . number_format($record->payable_amount ?? 0, 2);
                    })
                    ->default('-')
                    ->label(__('messages.subscription.payable_amount') . ':'),
                TextEntry::make('payment_type')
                    ->label(__('messages.subscription.payment_type') . ':')
                    ->formatStateUsing(function (string $state): string {
                        return Subscription::PAYMENT_TYPES[$state];
                    }),
                TextEntry::make('ends_at')
                    ->dateTime('d/m/Y')
                    ->label(__('messages.subscription.end_date') . ':'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table->modifyQueryUsing(function ($query) {
            $query->where('status', SubscriptionStatus::ACTIVE->value);
        });

        return $table
            ->emptyStateHeading(__('messages.subscription.no_subscriptions'))
            ->defaultSort('id', 'desc')
            ->recordAction(null)
            ->paginated([10, 25, 50, 100])
            ->searchPlaceholder(__('messages.common.search'))
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment(getActiveLanguage()['code'] == 'ar' ? 'start' : 'end')
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('messages.user.user_name'))
                    ->searchable(['name', 'email'])
                    ->description(fn($record) => $record->user->email)
                    ->limit(100)
                    ->wrap()
                    ->sortable(['name']),
                TextColumn::make('plan.name')
                    ->label(__('messages.plan.plan_name'))
                    ->searchable()
                    ->limit(100)
                    ->wrap()
                    ->sortable(),
                TextColumn::make('plan.price')
                    ->label(__('messages.plan.plan_amount'))
                    ->alignEnd()
                    ->formatStateUsing(function ($record) {
                        $currencySymbol = $record->plan->currency->symbol;
                        return $currencySymbol . ' ' . number_format($record->plan->price ?? 0, 2);
                    })
                    ->default('-')
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->dateTime('d/m/Y')
                    ->label(__('messages.subscription.start_date'))
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->dateTime('d/m/Y')
                    ->label(__('messages.subscription.end_date'))
                    ->sortable(),
                ToggleColumn::make('status')
                    ->disabled()
                    ->label(__('messages.common.status')),
            ])
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
                Tables\Actions\ViewAction::make()
                    ->label(__('messages.common.view'))
                    ->tooltip(__('messages.common.view'))
                    ->modalWidth('md')
                    ->modalHeading(__('messages.subscription.subscription_plan_details')),
                Tables\Actions\EditAction::make()
                    ->modalWidth('md')
                    ->modalHeading(__('messages.subscription.edit_subscription'))
                    ->label(__('messages.common.edit'))
                    ->tooltip(__('messages.common.edit'))
                    ->successNotificationTitle(__('messages.subscription.subscription_date_updated_success')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSubscriptions::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.subscription.subscriptions');
    }
}
