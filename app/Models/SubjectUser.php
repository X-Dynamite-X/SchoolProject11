<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectUser extends Model
{
    //
    protected $table = 'subject_users';

    protected $fillable = ['user_id', 'subject_id','mark'];

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
