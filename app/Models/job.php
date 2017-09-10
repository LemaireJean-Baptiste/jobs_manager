<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job Extends Model{

    protected $table = "jobs";

    protected $fillable = [
        'name',
        'excerpt',
        'text',
        'start',
        'finish',
        'salary',
        'level',
        'deadline'
    ];
}