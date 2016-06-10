<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration
{
    /**
     *  Create uploads table
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->engine = 'InnoDB';


            /**
             *  Table keys
             */
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');


            /**
             *  Upload information
             */
            $table->string('alias', 4);
            $table->string('protect_alias', 6);
            $table->string('ip_address', 45);
            $table->integer('views');
            $table->boolean('is_deleted');


            /**
             *  File information
             */
            $table->string('file_name', 256);
            $table->string('file_location', 256);
            $table->integer('file_size');
            $table->string('file_hash' , 32);
            $table->string('mime_type', 256);


            /**
             *  Timestamps
             */
            $table->timestamps();
        });
    }


    /**
     *  Drop uploads table
     */
    public function down()
    {
        Schema::drop('uploads');
    }
}
