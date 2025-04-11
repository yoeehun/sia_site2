<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserJob extends Model
{
    protected $table = 'tbluserjob';
    protected $primaryKey = 'job_id';
    public $timestamps = false;

    protected $fillable = ['job_name'];

    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'job_id', 'job_id');
    }
}
