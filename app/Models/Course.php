<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;


    /**
     * Function specifying the many to one relationship of holes and
     * which course they belong to.
     */
    public function holes() 
    {
        return $this->hasMany(Hole::class);
    }
}
