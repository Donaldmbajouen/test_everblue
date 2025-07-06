<?php

namespace App\Models;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'role',
        'description',
    ];

    const ICON = 'testimonial';

    public function getIconAttribute()
    {
        return $this->getFirstMediaUrl(self::ICON);
    }

    public static function getForm(): array
    {
        return [
            Group::make([
                SpatieMediaLibraryFileUpload::make('icon')
                    ->label(__('messages.user.profile') . ':')
                    ->placeholder(__('messages.user.profile'))
                    ->validationAttribute(__('messages.user.profile'))
                    ->disk(config('app.media_disk'))
                    ->collection(Testimonial::ICON)
                    ->image()
                    ->avatar(),
                Group::make([
                    TextInput::make('name')
                        ->label(__('messages.common.name') . ':')
                        ->placeholder(__('messages.common.name'))
                        ->validationAttribute(__('messages.common.name'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('role')
                        ->label(__('messages.home.role') . ':')
                        ->placeholder(__('messages.home.role'))
                        ->validationAttribute(__('messages.home.role'))
                        ->required()
                        ->maxLength(255),
                ])->columns(1)->columnSpan(2),
            ])->columns(3),
            Textarea::make('description')
                ->label(__('messages.quiz.description') . ':')
                ->placeholder(__('messages.quiz.description'))
                ->validationAttribute(__('messages.quiz.description'))
                ->required()
                ->maxLength(1000),
        ];
    }
}
