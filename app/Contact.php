<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{

    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'city',
        'state',
        'zip'
    ];
}
