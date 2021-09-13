<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('exam_type',['theoretical','practical', 'practicalTheoretical']);
            $table->bigInteger('course_id')->unsigned();
            //date of starting an exam
            $table->dateTime('exam_start_date');
            //duration of an exam
            $table->time('exam_duration');
            //number of exam questions
            $table->integer('questions_count');
            $table->double('sucess_mark');
            $table->double('maximum_mark');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
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
        Schema::dropIfExists('exams');
    }
}
