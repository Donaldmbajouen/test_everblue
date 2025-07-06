<?php

namespace App\Filament\Resources\CashPaymentsResource\Pages;

use App\Filament\Resources\CashPaymentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashPayments extends ListRecords
{
    protected static string $resource = CashPaymentsResource::class;

    public function getTitle(): string
    {
        return __('messages.subscription.cash_payments');
    }
}
