<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scores extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_name',
        'score',
        'date',
    ];


    /**
     * Specifies the player that a score belongs to.
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
