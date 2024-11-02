<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function holes() 
    {
        return $this->hasMany(Hole::class);
    }
}
