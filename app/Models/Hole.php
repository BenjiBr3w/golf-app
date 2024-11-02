<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hole extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
