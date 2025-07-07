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
        'type',
        'content',
        'question_id',
        'is_correct',
    ];

    protected $casts = [
        'title' => 'string',
        'type' => 'string',
        'content' => 'string',
        'question_id' => 'integer',
        'is_correct' => 'boolean',
    ];

    // Types de réponses disponibles
    const TYPE_TEXT = 'text';
    const TYPE_AUDIO = 'audio';
    const TYPE_VIDEO = 'video';
    const TYPE_FILE = 'file';

    const ANSWER_TYPES = [
        self::TYPE_TEXT => 'Texte',
        self::TYPE_AUDIO => 'Audio',
        self::TYPE_VIDEO => 'Vidéo',
        self::TYPE_FILE => 'Fichier',
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
