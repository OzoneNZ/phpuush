<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    /**
     *  Fillable fields
     */
    protected $fillable = [
        /**
         *  Upload information
         */
        'user_id', 'alias', 'protect_alias',
        'ip_address', 'views', 'is_deleted',


        /**
         *  File information
         */
        'file_name', 'file_location', 'file_hash', 'mime_type'
    ];


    /**
     *  Hidden fields
     */
    protected $hidden = [
        'password'
    ];


    /**
     *  User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
