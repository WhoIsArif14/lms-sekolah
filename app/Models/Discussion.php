<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $fillable = ['course_id', 'user_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
