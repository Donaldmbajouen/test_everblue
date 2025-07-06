<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuiz extends Model
{
    use HasFactory;

    protected $table = 'user_quizzes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'quiz_id',
        'started_at',
        'completed_at',
        'last_question_id',
        'score',
        'result',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'image' => 'integer',
        'email' => 'string',
        'quiz_id' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_question_id' => 'integer',
        'score' => 'integer',
        'result' => 'string',
    ];


    public static $rules = [
        'name' => 'required',
        'email' => 'required',
    ];


    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function questionAnswers()
    {
        return $this->hasMany(QuestionAnswer::class, 'quiz_user_id');
    }
}
