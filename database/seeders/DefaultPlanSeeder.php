<?php

namespace Database\Seeders;

use App\Enums\PlanFrequency;
use App\Models\Currency;
use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currency = Currency::where('code', 'USD')->first();

        if (!$currency) {
            $currency = Currency::create([
                'name' => 'USD Dollar',
                'code' => 'USD',
                'symbol' => '$',
            ]);
        }

        Plan::create([
            'name' => 'Default Plan',
            'frequency' => PlanFrequency::WEEKLY,
            'no_of_quiz' => 2,
            'assign_default' => 1,
            'price' => 0,
            'status' => 1,
            'currency_id' => $currency->id,
        ]);
    }
}
