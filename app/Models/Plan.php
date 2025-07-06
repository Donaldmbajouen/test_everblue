<?php

namespace App\Models;

use App\Enums\PlanFrequency;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'frequency',
        'no_of_quiz',
        'price',
        'trial_days',
        'assign_default',
        'status',
        'currency_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

        'name' => 'string',
        'frequency' => 'integer',
        'no_of_quiz' => 'integer',
        'price' => 'double',
        'trial_days' => 'integer',
        'assign_default' => 'boolean',
        'status' => 'boolean',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public static function getForm()
    {
        return [
            Section::make()->schema([
                TextInput::make('name')
                    ->label(__('messages.common.name') . ':')
                    ->placeholder(__('messages.common.name'))
                    ->validationAttribute(__('messages.common.name'))
                    ->required()
                    ->maxLength(255),
                ToggleButtons::make('frequency')
                    ->label(__('messages.plan.frequency') . ':')
                    ->validationAttribute(__('messages.plan.frequency'))
                    ->inline()
                    ->default(PlanFrequency::MONTHLY)
                    ->options(PlanFrequency::class)
                    ->required(),
                Textarea::make('description')
                    ->label(__('messages.quiz.description') . ':')
                    ->placeholder(__('messages.quiz.description'))
                    ->validationAttribute(__('messages.quiz.description'))
                    ->required(),
                TextInput::make('trial_days')
                    ->label(__('messages.plan.trial_days') . ':')
                    ->placeholder(__('messages.plan.trial_days'))
                    ->validationAttribute(__('messages.plan.trial_days'))
                    ->numeric()
                    ->integer(),
                TextInput::make('no_of_quiz')
                    ->numeric()
                    ->label(__('messages.plan.no_of_quizzes') . ':')
                    ->placeholder(__('messages.plan.no_of_quizzes'))
                    ->validationAttribute(__('messages.plan.no_of_quizzes'))
                    ->minValue(1)
                    ->required(),
                Select::make('currency_id')
                    ->label(__('messages.currency.currency') . ':')
                    ->placeholder(__('messages.currency.currency'))
                    ->validationAttribute(__('messages.currency.currency'))
                    ->options(function () {
                        return Currency::get()->mapWithKeys(function ($currency) {
                            return [$currency->id => $currency->symbol . ' - ' . $currency->name];
                        })->toArray();
                    })
                    ->live()
                    ->searchable()
                    ->required()
                    ->preload(),
                TextInput::make('price')
                    ->label(__('messages.common.price') . ':')
                    ->placeholder(__('messages.common.price'))
                    ->validationAttribute(__('messages.common.price'))
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->prefix(function (Get $get) {
                        return $get('currency_id') ? Currency::find($get('currency_id'))->symbol : '$';
                    }),
                Group::make([
                    Toggle::make('assign_default')
                        ->label(__('messages.plan.assign_default'))
                        ->live()
                        ->afterStateUpdated(function (Set $set, $state, string $operation, ?Model $record) {
                            $default = Plan::where('assign_default', true)->exists();
                            if ($operation === 'edit') {
                                $default = Plan::where('assign_default', true)->where('id', '!=', $record->id)->exists();
                            }
                            if (!$default && !$state) {
                                $set('assign_default', true);
                                Notification::make()
                                    ->title(__('messages.plan.default_plan_cannot_turned_off'))
                                    ->danger()
                                    ->send();
                            }
                        }),
                ])->columns(2),
            ])->columns(2)
        ];
    }
}
