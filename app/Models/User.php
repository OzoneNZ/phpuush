<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

use DB;

class User extends Authenticatable
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


    /**
     *  Counts up the used filesystem space from the user
     */
    public function getTotalUploadedBytes()
    {
        // Sum all upload file sizes
        $size = DB::select('
            SELECT SUM(file_size) AS total_bytes
            FROM uploads
            WHERE user_id = ? AND is_deleted = 0
        ', [ $this->id ])[0];

        // Check for a user with no uploads
        return ($size) ? $size->total_bytes : 0;
    }
}
