<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultLanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'name' => 'English',
                'code' => 'en',
            ],
            [
                'name' => 'Arabic',
                'code' => 'ar',
            ],
            [
                'name' => 'French',
                'code' => 'fr',
            ],
            [
                'name' => 'German',
                'code' => 'de',
            ],
            [
                'name' => 'Spanish',
                'code' => 'es',
            ],
            [
                'name' => 'Portuguese',
                'code' => 'pt',
            ],
            [
                'name' => 'Italian',
                'code' => 'it',
            ],
            [
                'name' => 'Russian',
                'code' => 'ru',
            ],
            [
                'name' => 'Turkish',
                'code' => 'tr',
            ],
            [
                'name' => 'Chinese',
                'code' => 'zh',
            ],
            [
                'name' => 'Vietnamese',
                'code' => 'vi',
            ],
            [
                'name' => 'Polish',
                'code' => 'pl',
            ],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
