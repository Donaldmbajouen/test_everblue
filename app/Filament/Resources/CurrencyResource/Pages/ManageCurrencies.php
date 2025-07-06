<?php

namespace App\Filament\Resources\CurrencyResource\Pages;

use App\Filament\Resources\CurrencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCurrencies extends ManageRecords
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('messages.currency.new_currency'))
                ->createAnother(false)
                ->modalWidth('md')
                ->modalHeading(__('messages.currency.create_currency'))
                ->successNotificationTitle(__('messages.currency.currency_created_success')),
        ];
    }

    public function getTitle(): string
    {
        return __('messages.currency.currencies');
    }
}
