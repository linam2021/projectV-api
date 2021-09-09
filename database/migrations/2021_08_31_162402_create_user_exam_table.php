<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_exam', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('path_id')->unsigned();
            //date of user registration on path
            $table->date('path_start_date');
            $table->bigInteger('exam_id')->unsigned();
            //date which user passed exam
            $table->date('user_exam_date');
            $table->double('exam_result')->nullable();
            $table->enum('is_well_prepared',['yes','no']);
            $table->enum('is_easy_exam',['easy','hard']);
            $table->primary(['user_id', 'path_id', 'path_start_date','exam_id']);
            $table->foreign(['user_id','path_id','path_start_date'])
                  ->references(['user_id','path_id','path_start_date'])->on('user_path')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('user_exam');
    }
}
