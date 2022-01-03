<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $casts = [
        'token' => 'json'
    ];

    protected $guarded = ['id'];
}
