<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hole extends Model
{
    use HasFactory;

    /**
     * Function specifying the course that holes belong to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
