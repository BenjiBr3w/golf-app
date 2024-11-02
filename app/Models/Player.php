<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    public function scores() 
    {
        return $this->hasMany(Scores::class);
    }
}
