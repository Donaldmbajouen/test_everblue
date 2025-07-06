<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    /**
     * @var string[]
     */
    protected $fillable = [
        'transaction_id',
        'amount',
        'type',
        'user_id',
        'status',
        'meta',
    ];

    protected $casts = [
        'transaction_id' => 'string',
        'amount' => 'double',
        'type' => 'integer',
        'user_id' => 'integer',
        'status' => 'boolean',
        'meta' => 'json',
    ];

    const SUCCESS = 1;

    const FAILED = 0;

    const RAZORPAY = 1;

    const PAYPAL = 2;

    const TYPE = [
        self::RAZORPAY => 'Razorpay',
        self::PAYPAL => 'Paypal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
}
