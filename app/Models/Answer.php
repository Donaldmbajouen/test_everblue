<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';

    protected $fillable = [
        'title',
        'question_id',
        'is_correct',
    ];

    protected $casts = [
        'title' => 'string',
        'question_id' => 'integer',
        'is_correct' => 'boolean',
    ];


    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function quizAnswers()
    {
        return $this->hasMany(QuestionAnswer::class, 'answer_id');
    }
}
