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
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('path_id')->unsigned();
            $table->date('path_start_date');
            $table->integer('level');
            $table->integer('repeat_chance_no');
            $table->string('user_status');
            $table->double('score');
            $table->timestamps();

            $table->primary(['user_id', 'path_id', 'path_start_date']);
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('path_id')
                ->references('id')->on('paths')
                ->onDelete('cascade');
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
