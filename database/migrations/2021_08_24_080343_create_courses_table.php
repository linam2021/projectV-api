<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('path_id')->unsigned();
            $table->string('course_name');
            $table->string('course_link');
            $table->date('course_start_date')->nullable();
            $table->integer('course_duration');
            $table->integer('stage');
            $table->integer('exam_repeated')->default(0); 
            $table->bigInteger('questionbank_course_id')->unsigned();
            $table->timestamps();

            $table->foreign('path_id')->references('id')->on('paths')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
