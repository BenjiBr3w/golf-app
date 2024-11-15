<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;

    /**
     * Many to one relationship specifying the scores that a player has entered.
     */
    public function scores() 
    {
        return $this->hasMany(Scores::class);
    }

    /**
     * Specifies the one to one relationship bewteen a given user id and player
     * that the id links with.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
