<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recruit_detail Extends Model{

    protected $table = "recruit_details";

    protected $fillable = [
        'text',
        'deadline',
        'status'
    ];
}