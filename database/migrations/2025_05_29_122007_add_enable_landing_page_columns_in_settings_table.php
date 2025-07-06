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
            $table->boolean('enable_landing_page')->default(true);
            $table->boolean('new_participant_mail_to_creator')->default(true);
            $table->boolean('quiz_complete_mail_to_participant')->default(true);
            $table->boolean('quiz_complete_mail_to_creator')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('enable_landing_page');
            $table->dropColumn('new_participant_mail_to_creator');
            $table->dropColumn('quiz_complete_mail_to_participant');
            $table->dropColumn('quiz_complete_mail_to_creator');
        });
    }
};
