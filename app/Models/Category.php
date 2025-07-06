<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
    ];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }


    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label(__('messages.common.name') . ':')
                ->placeholder(__('messages.common.name'))
                ->validationAttribute(__('messages.common.name'))
                ->required()
                ->maxLength(255),
        ];
    }
}
