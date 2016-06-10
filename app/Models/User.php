<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     *  Fillable fields
     */
    protected $fillable = [
        'email', 'password', 'api_key', 'enabled'
    ];


    /**
     *  Hidden fields
     */
    protected $hidden = [
        'password'
    ];
}
