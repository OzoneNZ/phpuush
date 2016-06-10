<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     *  Create users table
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';


            /**
             *  User information
             */
            $table->increments('id');
            $table->string('email', 255)->unique();
            $table->string('password', 64);
            $table->string('api_key', 64);
            $table->boolean('enabled');
            $table->timestamps();
        });
    }


    /**
     *  Drop users table
     */
    public function down()
    {
        Schema::drop('users');
    }
}
