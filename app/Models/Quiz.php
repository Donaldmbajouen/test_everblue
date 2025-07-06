<?php

namespace App\Models;

use Carbon\Carbon;
use Filament\Forms\Get;
use Spatie\MediaLibrary\HasMedia;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\FileUpload;
use Closure;

class Quiz extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'quizzes';

    public const QUIZ_PATH = 'quiz_document';

    protected $fillable = [
        'title',
        'quiz_description',
        'user_id',
        'status',
        'type',
        'category_id',
        'diff_level',
        'quiz_type',
        'max_questions',
        'unique_code',
        'view_count',
        'time_configuration',
        'time',
        'time_type',
        'quiz_expiry_date',
        'is_show_home',
    ];

    protected $casts = [
        'title' => 'string',
        'quiz_description' => 'string',
        'user_id' => 'integer',
        'status' => 'boolean',
        'type' => 'integer',
        'diff_level' => 'integer',
        'quiz_type' => 'integer',
        'max_questions' => 'integer',
        'unique_code' => 'string',
        'view_count' => 'integer',
    ];

    const TEXT_TYPE = 1;

    const SUBJECT_TYPE = 2;

    const URL_TYPE = 3;

    const UPLOAD_TYPE = 4;

    const TIME_OVER_QUESTION = 1;

    const TIME_OVER_QUIZ = 2;

    const QUIZ_INPUT_TYPE = [
        self::TEXT_TYPE => 'Text',
        self::SUBJECT_TYPE => 'Subject',
        self::URL_TYPE => 'URL',
        self::UPLOAD_TYPE => 'Upload File',
    ];

    const OPEN_AI = 1;

    const GEMINI_AI = 2;

    const AI_TYPES = [
        self::OPEN_AI => 'Open AI',
        self::GEMINI_AI => 'Gemini AI',
    ];

    protected $appends = [
        'quiz_document',
        'question_count',
    ];

    public function getQuizDocumentAttribute()
    {
        return $this->getFirstMediaUrl(self::QUIZ_PATH);
    }

    const MULTIPLE_CHOICE = 0;
    const SINGLE_CHOICE = 1;
    const QUIZ_TYPE = [
        self::MULTIPLE_CHOICE => 'Multiple Choices',
        self::SINGLE_CHOICE => 'Single Choice',
    ];

    public static function getQuizTypeOptions()
    {
        return [
            0 => __('messages.home.multiple_choice'),
            1 => __('messages.home.single_choice'),
        ];
    }

    const DIFF_LEVEL = [
        0 => 'Basic',
        1 => 'Intermediate',
        2 => 'Advanced',
    ];

    public static function getDiffLevelOptions()
    {
        return [
            0 => __('messages.quiz.basic'),
            1 => __('messages.quiz.intermediate'),
            2 => __('messages.quiz.advanced'),
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    protected function getQuestionCountAttribute()
    {
        return $this->questions()->count();
    }

    public function quizUser()
    {
        return $this->hasMany(UserQuiz::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function  getForm(): array
    {
        return [
            Section::make()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    Group::make([
                                        TextInput::make('title')
                                            ->label(__('messages.quiz.title') . ':')
                                            ->placeholder(__('messages.quiz.quiz_title'))
                                            ->validationAttribute(__('messages.quiz.title'))
                                            ->required(),
                                        Select::make('category_id')
                                            ->label(__('messages.quiz.select_category') . ':')
                                            ->placeholder(__('messages.quiz.select_category'))
                                            ->validationAttribute(__('messages.quiz.category'))
                                            ->options(function () {
                                                return Category::all()->pluck('name', 'id');
                                            })
                                            ->searchable()
                                            ->required()
                                            ->preload()
                                            ->native(false)
                                    ]),
                                    Section::make()
                                        ->schema([
                                            Select::make('quiz_type')
                                                ->label(__('messages.quiz.question_type') . ':')
                                                ->options(Quiz::getQuizTypeOptions())
                                                ->default(0)
                                                ->searchable()
                                                ->required()
                                                ->preload()
                                                ->live()
                                                ->native(false)
                                                ->placeholder(__('messages.quiz.select_question'))
                                                ->validationAttribute(__('messages.quiz.question_type')),
                                            Select::make('diff_level')
                                                ->label(__('messages.quiz.difficulty') . ':')
                                                ->options(Quiz::getDiffLevelOptions())
                                                ->default(0)
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->native(false)
                                                ->placeholder(__('messages.quiz.select_difficulty'))
                                                ->validationAttribute(__('messages.quiz.difficulty')),
                                            TextInput::make('max_questions')
                                                ->numeric()
                                                ->rules(['integer', 'max:25'])
                                                ->integer()
                                                ->required()
                                                ->minValue(1)
                                                ->maxValue(25)
                                                ->label(__('messages.quiz.num_of_questions') . ':')
                                                ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('messages.quiz.max_no_of_quiz'))
                                                ->placeholder(__('messages.quiz.number_of_questions'))
                                                ->validationAttribute(__('messages.quiz.num_of_questions')),
                                            Select::make('language')
                                                ->label(__('messages.home.language') . ':')
                                                ->options(getAllLanguages())
                                                ->preload()
                                                ->searchable()
                                                ->native(false)
                                                ->default('en')
                                                ->validationAttribute(__('messages.home.language'))
                                        ])
                                        ->columns(2),
                                ])
                                ->columnSpan(1),

                            Grid::make(1)
                                ->schema([
                                    Tabs::make('Tabs')
                                        ->tabs([
                                            Tab::make('Text')
                                                ->label(__('messages.quiz.text'))
                                                ->schema([
                                                    Textarea::make('quiz_description_text')
                                                        ->label(__('messages.quiz.description') . ':')
                                                        ->placeholder(__('messages.quiz.quiz_description'))
                                                        ->formatStateUsing(function ($get, $operation) {
                                                            if ($operation == 'edit' && $get('type') == 1) {
                                                                return $get('quiz_description');
                                                            }
                                                        })
                                                        ->required(function ($get) {
                                                            return getTabType() == 1 || $get('type') == 1;
                                                        })
                                                        ->live()
                                                        ->validationAttribute(__('messages.quiz.description'))
                                                        ->rows(5)
                                                        ->cols(10),
                                                ]),
                                            Tab::make('Subject')
                                                ->label(__('messages.quiz.subject'))
                                                ->schema([
                                                    TextInput::make('quiz_description_sub')
                                                        ->label(__('messages.quiz.subject') . ':')
                                                        ->placeholder(__('messages.quiz.e_g_biology'))
                                                        ->formatStateUsing(function ($get, $operation) {
                                                            if ($operation == 'edit' && $get('type') == 2) {
                                                                return $get('quiz_description');
                                                            }
                                                        })
                                                        ->required(function ($get) {
                                                            return getTabType() == 2 || $get('type') == 2;
                                                        })
                                                        ->live()
                                                        ->validationAttribute(__('messages.quiz.subject'))
                                                        ->maxLength(250)
                                                        ->helperText(__('messages.quiz.enter_a_subject_to_generate_question_about'))
                                                        ->autocomplete('off'),
                                                ]),
                                            Tab::make('URL')
                                                ->label(__('messages.quiz.url'))
                                                ->schema([
                                                    TextInput::make('quiz_description_url')
                                                        ->label(__('messages.quiz.url') . ':')
                                                        ->formatStateUsing(function ($get, $operation) {
                                                            if ($operation == 'edit' && $get('type') == 3) {
                                                                return $get('quiz_description');
                                                            }
                                                        })
                                                        ->required(function ($get) {
                                                            return getTabType() == 3 || $get('type') == 3;
                                                        })
                                                        ->live()
                                                        ->validationAttribute(__('messages.quiz.url'))
                                                        ->url()
                                                        ->placeholder(__('messages.quiz.please_enter_url')),
                                                ]),
                                            Tab::make('Upload')
                                                ->label(__('messages.quiz.upload'))
                                                ->schema([
                                                    SpatieMediaLibraryFileUpload::make('file_upload')
                                                        ->label(__('messages.quiz.document') . ':')
                                                        ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('messages.quiz.file_upload_hint'))
                                                        ->validationAttribute(__('messages.quiz.document'))
                                                        ->disk(config('app.media_disk'))
                                                        ->required(function ($get) {
                                                            return getTabType() == 4 || $get('type') == 4;
                                                        })
                                                        ->live()
                                                        ->collection(Quiz::QUIZ_PATH)
                                                        ->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                                                ]),
                                        ])
                                        ->activeTab(function ($get) {
                                            return $get('type') ?? 1;
                                        })
                                        ->extraAttributes([
                                            'wire:click' => 'currentActiveTab',
                                        ])
                                        ->persistTabInQueryString(),
                                    Section::make()
                                        ->schema([
                                            Toggle::make("time_configuration")
                                                ->live()
                                                ->label(__('messages.quiz.time_configuration') . ':'),
                                            DatePicker::make('quiz_expiry_date')
                                                ->placeholder(__('messages.quiz.expiry_date'))
                                                ->minDate(now()->format('Y-m-d'))
                                                ->label(__('messages.quiz.expiry_date') . ':')
                                                ->native(false)
                                                ->hintAction(
                                                    Action::make('clearDate')
                                                        ->iconButton()
                                                        ->icon('heroicon-o-x-circle')
                                                        ->tooltip(__('messages.common.clear_date'))
                                                        ->action(function (\Filament\Forms\Set $set) {
                                                            $set('quiz_expiry_date', null);
                                                        })
                                                ),
                                            Section::make()
                                                ->schema([
                                                    TextInput::make('time')
                                                        ->numeric()
                                                        ->placeholder(__('messages.quiz.time'))
                                                        ->required()
                                                        ->minValue(1)
                                                        ->rules(['integer', 'min:1'])
                                                        ->label(__('messages.quiz.time_label') . ':')
                                                        ->extraAttributes([
                                                            'onkeydown' => "if(event.key === '-' || event.key === '+' || event.key === 'e'){ event.preventDefault(); }"
                                                        ]),

                                                    Radio::make('time_type')
                                                        ->options([
                                                            1 => __('messages.quiz.time_question'),
                                                            2 => __('messages.quiz.time_quiz'),
                                                        ])
                                                        ->required()
                                                        ->label(__('messages.quiz.time_type') . ':'),

                                                ])->live()->columns(2)->hidden(function ($get) {
                                                    return !$get('time_configuration');
                                                }),
                                        ])
                                        ->columns(2),
                                ])
                                ->columnSpan(1),
                        ])
                        ->columns(2),
                ]),

            

        ];
    }
}
