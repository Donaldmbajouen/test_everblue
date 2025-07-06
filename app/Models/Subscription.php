<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Subscription extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'subscriptions';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'transaction_id',
        'plan_amount',
        'discount',
        'payable_amount',
        'plan_frequency',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'status',
        'notes',
        'payment_type',
    ];


    protected $casts = [
        'user_id' => 'integer',
        'plan_id' => 'integer',
        'transaction_id' => 'integer',
        'plan_amount' => 'double',
        'discount' => 'double',
        'payable_amount' => 'double',
        'plan_frequency' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'status' => 'integer',
        'notes' => 'string',
        'payment_type' => 'integer',
    ];

    const ATTACHMENT = 'attachment';

    const TYPE_FREE = 0;

    const TYPE_RAZORPAY = 1;

    const TYPE_PAYPAL = 2;

    const TYPE_STRIPE = 3;

    const TYPE_MANUALLY = 4;

    const PAYMENT_TYPES = [
        self::TYPE_FREE => 'Free Plan',
        self::TYPE_PAYPAL => 'PayPal',
        self::TYPE_RAZORPAY => 'RazorPay',
        self::TYPE_STRIPE => 'Stripe',
        self::TYPE_MANUALLY => 'Manually',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->getFirstMediaUrl(self::ATTACHMENT);
    }

    public static function getPaymentType(): array
    {
        $paymentSetting = getPaymentSetting();

        if ($paymentSetting->razorpay_enabled) {
            $paymentType[self::TYPE_RAZORPAY] = 'RazorPay';
        }
        if ($paymentSetting->stripe_enabled) {
            $paymentType[self::TYPE_STRIPE] = 'Stripe';
        }
        if ($paymentSetting->paypal_enabled) {
            $paymentType[self::TYPE_PAYPAL] = 'Paypal';
        }
        if ($paymentSetting->manually_enabled) {
            $paymentType[self::TYPE_MANUALLY] = 'Manually';
        }

        return $paymentType ?? [];
    }

    public function isExpired(): bool
    {
        $now = Carbon::now();

        if ($this->ends_at > $now) {
            return false;
        }

        // this means the subscription is ended.
        if ((! empty($this->trial_ends_at) && $this->trial_ends_at < $now) || $this->ends_at < $now) {
            return true;
        }

        // this means the subscription is not ended.
        return false;
    }
}
