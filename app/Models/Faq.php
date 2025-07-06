<?php

namespace App\Models;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    public $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'status',
    ];

    public static function getForm()
    {
        return [
            TextInput::make('question')
                ->label(__('messages.common.question') . ':')
                ->placeholder(__('messages.common.question'))
                ->validationAttribute(__('messages.common.question'))
                ->required(),
            Textarea::make('answer')
                ->label(__('messages.common.answer') . ':')
                ->placeholder(__('messages.common.answer'))
                ->validationAttribute(__('messages.common.answer'))
                ->required(),
        ];
    }
}
