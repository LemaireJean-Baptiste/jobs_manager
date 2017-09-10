<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recruit Extends Model{

    protected $table = "recruits";

    protected $fillable = [
        'name',
        'excerpt',
        'text',
        'number',
        'status'
    ];
}