<?php

namespace App\Filament\Resources;

use App\Enums\AdminSidebar;
use App\Filament\Resources\CurrencyResource\Pages;
use App\Models\Currency;
use App\Models\Plan;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?string $navigationIcon = 'heroicon-m-currency-dollar';

    protected static ?int $navigationSort = AdminSidebar::CURRENCIES->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.currency.currencies');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Currency::getForm())
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('messages.currency.no_currencies'))
            ->recordAction(null)
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([10, 25, 50, 100])
            ->defaultSort('id', 'desc')
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment(getActiveLanguage()['code'] == 'ar' ? 'start' : 'end')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.common.name'))
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('messages.currency.code'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('symbol')
                    ->label(__('messages.currency.symbol'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('messages.common.edit'))
                    ->tooltip(__('messages.common.edit'))
                    ->modalWidth('md')
                    ->modalHeading(__('messages.currency.edit_currency'))
                    ->successNotificationTitle(__('messages.currency.currency_updated_success')),
                \App\Filament\Actions\CustomDeleteAction::make()
                    ->setCommonProperties()
                    ->before(function (Tables\Actions\DeleteAction $action) {
                        $plan =  Plan::where('currency_id', $action->getRecord()->id)->exists();
                        if ($plan) {
                            Notification::make()
                                ->danger()
                                ->title(__('messages.currency.cannot_delete_currency'))
                                ->send();
                            $action->cancel();
                        }
                    })
                    ->modalHeading(__('messages.currency.delete_currency'))
                    ->successNotificationTitle(__('messages.currency.currency_deleted_success')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    \App\Filament\Actions\CustomDeleteBulkAction::make()
                        ->setCommonProperties()
                        ->before(function (Tables\Actions\DeleteBulkAction $action) {
                            $currencyIds = $action->getRecords()->pluck('id');
                            $planExists = Plan::whereIn('currency_id', $currencyIds)->exists();

                            if ($planExists) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('messages.currency.cannot_delete_currency'))
                                    ->send();

                                $action->cancel();
                            }
                        })
                        ->modalHeading(__('messages.currency.delete_selected_currencies'))
                        ->successNotificationTitle(__('messages.currency.currencies_deleted_success')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCurrencies::route('/'),
        ];
    }
}
