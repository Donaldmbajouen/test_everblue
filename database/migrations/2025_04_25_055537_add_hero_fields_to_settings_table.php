<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('open_api_key')->nullable()->after('cookie_policy');
            $table->string('hero_sub_title')->nullable()->after('open_api_key');
            $table->string('hero_title')->nullable()->after('hero_sub_title');
            $table->text('hero_description')->nullable()->after('hero_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['hero_sub_title', 'hero_title', 'hero_description']);
        });
    }
};
