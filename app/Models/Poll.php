<?php

namespace App\Models;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Poll extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'polls';

    protected $fillable = [
        'question',
        'user_id',
        'option1',
        'option2',
        'option3',
        'option4',
        'end_at',
    ];

    protected $casts = [
        'question' => 'string',
        'user_id' => 'integer',
        'option1' => 'string',
        'option2' => 'string',
        'option3' => 'string',
        'option4' => 'string',
        'end_at' => 'datetime',
    ];

    protected $appends = [
        'poll_images_1_url',
        'poll_images_2_url',
        'poll_images_3_url',
        'poll_images_4_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pollResults(): HasMany
    {
        return $this->hasMany(PollResult::class);
    }

    public function getPollResultsCountAttribute()
    {
        return $this->pollResults()->count();
    }
    const POLL_IMAGES_1 = 'poll_images_1';
    const POLL_IMAGES_2 = 'poll_images_2';
    const POLL_IMAGES_3 = 'poll_images_3';
    const POLL_IMAGES_4 = 'poll_images_4';

    public function getPollImages1UrlAttribute()
    {
        return $this->getMedia(self::POLL_IMAGES_1)->first()?->getUrl() ?? null;
    }

    public function getPollImages2UrlAttribute()
    {
        return $this->getMedia(self::POLL_IMAGES_2)->first()?->getUrl() ?? null;
    }

    public function getPollImages3UrlAttribute()
    {
        return $this->getMedia(self::POLL_IMAGES_3)->first()?->getUrl() ?? null;
    }

    public function getPollImages4UrlAttribute()
    {
        return $this->getMedia(self::POLL_IMAGES_4)->first()?->getUrl() ?? null;
    }

    public static function getForm(): array
    {
        return [
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('question')
                        ->label(__('messages.quiz.title') . ':')
                        ->required()
                        ->maxLength(255)
                        ->placeholder(__('messages.quiz.title')),
                    DateTimePicker::make('end_at')
                        ->label(__('messages.poll.end_at') . ':')
                        ->seconds(false)
                        ->required()
                        ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('messages.poll.this_time_will_be_utc'))
                        ->timezone('UTC'),
                    TextInput::make('option1')
                        ->required()
                        ->label(__('messages.poll.option_1') . ':')
                        ->maxLength(181)
                        ->placeholder(__('messages.poll.option_1')),
                    SpatieMediaLibraryFileUpload::make('option1_image')
                        ->label(__('messages.poll.option_1_image') . ':')
                        ->disk(config('app.media_disk'))
                        ->collection(Poll::POLL_IMAGES_1)
                        ->image()
                        ->imagePreviewHeight('100px'),
                    TextInput::make('option2')
                        ->required()
                        ->label(__('messages.poll.option_2') . ':')
                        ->maxLength(181)
                        ->placeholder(__('messages.poll.option_2')),
                    SpatieMediaLibraryFileUpload::make('option2_image')
                        ->label(__('messages.poll.option_2_image') . ':')
                        ->disk(config('app.media_disk'))
                        ->collection(Poll::POLL_IMAGES_2)
                        ->image()
                        ->imagePreviewHeight('100px'),
                    TextInput::make('option3')
                        ->label(__('messages.poll.option_3') . ':')
                        ->maxLength(181)
                        ->placeholder(__('messages.poll.option_3')),
                    SpatieMediaLibraryFileUpload::make('option3_image')
                        ->label(__('messages.poll.option_3_image') . ':')
                        ->disk(config('app.media_disk'))
                        ->collection(Poll::POLL_IMAGES_3)
                        ->image()
                        ->imagePreviewHeight('100px'),
                    TextInput::make('option4')
                        ->label(__('messages.poll.option_4') . ':')
                        ->maxLength(181)
                        ->placeholder(__('messages.poll.option_4')),
                    SpatieMediaLibraryFileUpload::make('option4_image')
                        ->label(__('messages.poll.option_4_image') . ':')
                        ->disk(config('app.media_disk'))
                        ->collection(Poll::POLL_IMAGES_4)
                        ->image()
                        ->imagePreviewHeight('100px'),
                ])
        ];
    }
}
