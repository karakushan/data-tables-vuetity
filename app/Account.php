<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name',
        'position',
        'office',
        'age',
        'start_date',
        'salary',
    ];
}
