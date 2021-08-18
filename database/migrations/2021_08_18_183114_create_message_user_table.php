<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_user', function (Blueprint $table) {
            //user id
            $table->unsignedBigInteger('user_id');
            //message id
            $table->unsignedBigInteger('message_id');
            //add read to verify if message is readed, by default read ==0 (message was not read )
            $table->integer('read')->default(0);
            //add user_id as foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //add message_id as foreign key
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
            //make user_id and message_id as primary key
            $table->primary(['user_id','message_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_user');
    }
}
