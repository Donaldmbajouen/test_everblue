<?php

namespace App\Filament\User\Pages;

use App\Actions\Subscription\CreateSubscription;
use App\Actions\Subscription\GetCurrentSubscription;
use App\Enums\PlanFrequency;
use App\Http\Middleware\CheckPaddingSubscription;
use App\Models\PaymentSetting;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class ChoosePaymentType extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'choose-payment-type/{plan}';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.choose-payment-type';

    /**
     * @var string | array<string>
     */
    protected static string|array $routeMiddleware = [
        CheckPaddingSubscription::class,
    ];

    public Plan $plan;

    public $paymentAmount = 0;

    public $paymentType = 0;

    protected function getViewData(): array
    {
        // New Plan
        $plan = $this->plan;
        $plan->currency_icon = $plan->currency->symbol;
        $plan->start_date = Carbon::now();
        $plan->end_date = Carbon::now()->addMonth()->endOfDay();
        $plan->total_days = 30;

        if ($plan->trial_days > 0) {
            $plan->end_date = Carbon::now()->addDays($plan->trial_days)->endOfDay();
        } else {
            if ($plan->frequency == PlanFrequency::MONTHLY->value) {
                $plan->end_date = Carbon::now()->addMonth()->endOfDay();
            } elseif ($plan->frequency == PlanFrequency::WEEKLY->value) {
                $plan->end_date = Carbon::now()->addWeek()->endOfDay();
            } elseif ($plan->frequency == PlanFrequency::YEARLY->value) {
                $plan->end_date = Carbon::now()->addYear()->endOfDay();
            }
        }

        $plan->total_days = floor($plan->start_date->diffInDays($plan->end_date));
        $plan->payable_amount = $plan->price > 0 ? $plan->price : 0;
        $this->paymentAmount = $plan->price > 0 ? $plan->price : 0;

        $currentActivePlan = empty(GetCurrentSubscription::run()) ? null : GetCurrentSubscription::run();

        if ($currentActivePlan) {
            $price = $plan->price - $currentActivePlan['remaining_balance'];
            $plan->payable_amount = $price > 0 ? $price : 0;
            $this->paymentAmount = $price > 0 ? $price : 0;
        }

        $manualPaymentGuide = PaymentSetting::first()->manual_payment_guide ?? null;

        return compact('plan', 'currentActivePlan', 'manualPaymentGuide');
    }

    public static function getRelativeRouteName(): string
    {
        return (string) 'choose-payment-type';
    }

    public function form(Form $form): Form
    {
        $this->data = [
            'payment_type' => $this->data['payment_type'] ?? null,
            'notes' => $this->data['notes'] ?? null,
            'attachment' => $this->data['attachment'] ?? null,
        ];

        return $form
            ->schema([
                Select::make('payment_type')
                    ->label('')
                    ->live()
                    ->native(false)
                    ->options(Subscription::getPaymentType())
                    ->placeholder(__('messages.plan.select_payment_type'))
                    ->afterStateUpdated(function (Get $get) {
                        $this->paymentType = (int) $get('payment_type');
                    })
                    ->helperText(function (Get $get) {
                        $manualPaymentGuide = PaymentSetting::first()->manual_payment_guide ?? null;

                        return $get('payment_type') == Subscription::TYPE_MANUALLY
                            ? new HtmlString($manualPaymentGuide)
                            : null;
                    }),
                SpatieMediaLibraryFileUpload::make('attachment')
                    ->label(__('messages.subscription.attachment') . ':')
                    ->disk(config('app.media_disk'))
                    ->collection(Subscription::ATTACHMENT)
                    ->image()
                    ->visible(fn(Get $get) => $get('payment_type') == Subscription::TYPE_MANUALLY),
                Textarea::make('notes')
                    ->label(__('messages.subscription.notes') . ':')
                    ->visible(fn(Get $get) => $get('payment_type') == Subscription::TYPE_MANUALLY),
            ])
            ->statePath('data');
    }

    public function save()
    {
        $data['plan'] = $this->plan->load('currency')->toArray();
        $data['user_id'] = auth()->user()->id;
        $data['payment_type'] = $this->data['payment_type'] ?? null;
        $data['attachment'] = $this->data['attachment'] ?? null;
        $data['notes'] = $this->data['notes'] ?? null;
        
        $subscription = CreateSubscription::run($data);
        
        if ($subscription) {
            Notification::make()
                ->success()
                ->title(__('messages.subscription.subscription_created_successfully'))
                ->send();

            return redirect()->route('filament.user.pages.manage-subscription');
        }
    }
}
