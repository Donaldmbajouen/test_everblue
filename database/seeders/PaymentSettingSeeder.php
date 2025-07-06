<?php

namespace Database\Seeders;

use App\Models\PaymentSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        PaymentSetting::create([
            'razorpay_enabled' => 0,
            'razorpay_key' => null,
            'razorpay_secret' => null,
            'paypal_enabled' => 0,
            'paypal_client_id' => null,
            'paypal_secret' => null,
            'manually_enabled' => 0,
            'manual_payment_guide' => null,
            'stripe_enabled' => 0,
            'stripe_key' => null,
            'stripe_secret' => null,
        ]);
    }
}
