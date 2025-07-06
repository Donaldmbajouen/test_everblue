<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'symbol',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string',
        'symbol' => 'string',
    ];

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label(__('messages.common.name') . ':')
                ->placeholder(__('messages.common.name'))
                ->validationAttribute(__('messages.common.name'))
                ->required()
                ->unique(
                    'currencies',
                    'name',
                    null,
                    false,
                    function ($rule, $record) {
                        if ($record) {
                            $rule->whereNot('id', $record->id);
                        }
                        return $rule;
                    }
                )
                ->maxLength(255),
            TextInput::make('code')
                ->label(__('messages.currency.code') . ':')
                ->placeholder(__('messages.currency.code'))
                ->validationAttribute(__('messages.currency.code'))
                ->required()
                ->unique(
                    'currencies',
                    'code',
                    null,
                    false,
                    function ($rule, $record) {
                        if ($record) {
                            $rule->whereNot('id', $record->id);
                        }
                        return $rule;
                    }
                )
                ->maxLength(255),
            TextInput::make('symbol')
                ->label(__('messages.currency.symbol') . ':')
                ->placeholder(__('messages.currency.symbol'))
                ->validationAttribute(__('messages.currency.symbol'))
                ->required()
                ->maxLength(255),
        ];
    }
}
