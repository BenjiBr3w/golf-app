<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scores extends Model
{
    use HasFactory;

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
