<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_user', function (Blueprint $table) {
            //user id
            $table->unsignedBigInteger('user_id');
            //notification id
            $table->unsignedBigInteger('notification_id');
            //add read to verify if notification is readed, by default read ==0 (notification was not read )
            $table->integer('read')->default(0);
            //add user_id as foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //add notification_id as foreign key
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
            //make user_id and message_id as primary key
            $table->primary(['user_id','notification_id']);
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
        Schema::dropIfExists('notification_user');
    }
}
