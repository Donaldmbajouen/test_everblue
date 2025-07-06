<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'app_name' => 'QuizWhiz AI',
            'email' => 'admin@gmail.com',
            'contact' => '',
            'prefix_code' => 'IN',
        ]);
    }
}
