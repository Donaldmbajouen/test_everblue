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
            $table->boolean('enable_captcha')->default(false);
            $table->text('captcha_site_key')->nullable();
            $table->text('captcha_secret_key')->nullable();
            $table->boolean('enabled_captcha_in_login')->default(false);
            $table->boolean('enabled_captcha_in_register')->default(false);
            $table->boolean('enabled_captcha_in_quiz')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('enable_captcha');
            $table->dropColumn('captcha_site_key');
            $table->dropColumn('captcha_secret_key');
            $table->dropColumn('enabled_captcha_in_login');
            $table->dropColumn('enabled_captcha_in_register');
            $table->dropColumn('enabled_captcha_in_quiz');
        });
    }
};
