<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPathTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_path', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('path_id');
            $table->integer('level');
            $table->integer('repeat_chance_no');
            $table->string('user_status');
            $table->double('score');
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
        Schema::dropIfExists('user_path');
    }
}
