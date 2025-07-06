<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollResult extends Model
{
    use HasFactory;

    protected $table = 'poll_results';

    protected $fillable = [
        'poll_id',
        'answer',
        'ip_address',
        'country',
    ];

    protected $casts = [
        'poll_id' => 'integer',
        'answer' => 'string',
        'ip_address' => 'string',
        'country' => 'string',
    ];

    public static $rules = [
        'poll_id' => 'required',
        'answer' => 'required|string',
        'ip_address' => 'nullable|string'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }
}
