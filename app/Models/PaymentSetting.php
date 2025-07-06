<?php

namespace App\Models;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'razorpay_enabled',
        'razorpay_key',
        'razorpay_secret',
        'paypal_enabled',
        'paypal_client_id',
        'paypal_secret',
        'paypal_mode',
        'manually_enabled',
        'manual_payment_guide',
        'stripe_enabled',
        'stripe_key',
        'stripe_secret',
    ];

    const PAYPAL_MODE = [
        'sandbox' => 'Sandbox',
        'live' => 'Live',
    ];

    public static function getForm()
    {
        return [
            Toggle::make('razorpay_enabled')
                ->label(__('messages.payment.razorpay'))
                ->live(),
            Group::make([
                TextInput::make('razorpay_key')
                    ->label(__('messages.payment.razorpay_key') . ':')
                    ->placeholder(__('messages.payment.razorpay_key'))
                    ->validationAttribute(__('messages.payment.razorpay_key'))
                    ->required(),
                TextInput::make('razorpay_secret')
                    ->label(__('messages.payment.razorpay_secret') . ':')
                    ->placeholder(__('messages.payment.razorpay_secret'))
                    ->validationAttribute(__('messages.payment.razorpay_secret'))
                    ->required(),
            ])->columns(2)->visible(fn(Get $get) => $get('razorpay_enabled') == 1),
            Toggle::make('paypal_enabled')
                ->label(__('messages.payment.paypal'))
                ->live(),
            Group::make([
                TextInput::make('paypal_client_id')
                    ->label(__('messages.payment.paypal_client_id') . ':')
                    ->placeholder(__('messages.payment.paypal_client_id'))
                    ->validationAttribute(__('messages.payment.paypal_client_id'))
                    ->required(),
                TextInput::make('paypal_secret')
                    ->label(__('messages.payment.paypal_secret') . ':')
                    ->placeholder(__('messages.payment.paypal_secret'))
                    ->validationAttribute(__('messages.payment.paypal_secret'))
                    ->required(),
                Select::make('paypal_mode')
                    ->native(false)
                    ->label(__('messages.payment.paypal_mode') . ':')
                    ->placeholder(__('messages.payment.paypal_mode'))
                    ->validationAttribute(__('messages.payment.paypal_mode'))
                    ->options(self::PAYPAL_MODE)
                    ->required(),
            ])->columns(2)->visible(fn(Get $get) => $get('paypal_enabled') == 1),
            Toggle::make('stripe_enabled')
                ->label(__('messages.payment.stripe'))
                ->live(),
            Group::make([
                TextInput::make('stripe_key')
                    ->label(__('messages.payment.stripe_key') . ':')
                    ->placeholder(__('messages.payment.stripe_key'))
                    ->validationAttribute(__('messages.payment.stripe_key'))
                    ->required(),
                TextInput::make('stripe_secret')
                    ->label(__('messages.payment.stripe_secret') . ':')
                    ->placeholder(__('messages.payment.stripe_secret'))
                    ->validationAttribute(__('messages.payment.stripe_secret'))
                    ->required(),
            ])->columns(2)->visible(fn(Get $get) => $get('stripe_enabled') == 1),
            Toggle::make('manually_enabled')
                ->label(__('messages.payment.manually_payment'))
                ->live(),
            RichEditor::make('manual_payment_guide')
                ->label(__('messages.payment.manual_payment_guide') . ':')
                ->placeholder(__('messages.payment.manual_payment_placeholder'))
                ->validationAttribute(__('messages.payment.manual_payment_guide'))
                ->columnSpanFull()
                ->visible(fn(Get $get) => $get('manually_enabled') == 1),
        ];
    }
}
