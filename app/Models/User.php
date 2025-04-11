<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'tbluser';

    protected $fillable = ['username', 'password', 'gender', 'job_id'];

    // Define relationship to UserJob
    public function job()
    {
        return $this->belongsTo(\App\Models\UserJob::class, 'job_id', 'job_id');
    }
}
