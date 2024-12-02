<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'success_mark',
        'full_mark',

    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'subject_users')->withPivot('mark');
    }
}
