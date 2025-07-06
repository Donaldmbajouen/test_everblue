<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $table = 'question_answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_user_id',
        'question_id',
        'question_title',
        'answer_id',
        'multi_answer',
        'answer_title',
        'is_correct',
        'ans_text',
        'completed_at',
        'is_time_out'
    ];

    protected $casts = [
        'multi_answer' => 'array',
    ];

    public function quizUser()
    {
        return $this->belongsTo(UserQuiz::class, 'quiz_user_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id');
    }
}
