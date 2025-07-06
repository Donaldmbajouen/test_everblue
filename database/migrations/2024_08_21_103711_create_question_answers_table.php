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
        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_user_id');
            $table->unsignedBigInteger('question_id');
            $table->text('question_title')->nullable();
            $table->unsignedBigInteger('answer_id')->nullable();
            $table->text('answer_title')->nullable();
            $table->boolean('is_correct')->default(0);
            $table->longText('ans_text')->nullable();
            $table->timestamps();

            //champ pour stocker le chemin et le type de fichier de la reponsesi necessaire
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();


            $table->foreign('quiz_user_id')->references('id')->on('user_quizzes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('question_id')->references('id')->on('questions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('answer_id')->references('id')->on('answers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_answers');
    }
};
