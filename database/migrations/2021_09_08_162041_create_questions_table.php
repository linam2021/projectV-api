<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('question_text');
            $table->string('question_image_url')->nullable();
            $table->text('answer_a');
            $table->text('answer_b');
            $table->text('answer_c');
            $table->enum('correct_answer',['A','B','C']);
            $table->bigInteger('primary_question_id')->unsigned()->nullable();
            $table->bigInteger('exam_id')->unsigned();
            $table->foreign('primary_question_id')
                ->references('id')->on('questions')
                ->onDelete('cascade');
            $table->foreign('exam_id')
                ->references('id')->on('exams')
                ->onDelete('cascade');
            $table->timestamps();
        });


        

        // Add the constraint
    // DB::statement('ALTER TABLE questions ADD CONSTRAINT sub_question_fk foreign('exam_id')
    // ->references('id')->on('exams')
    // ->onDelete('cascade');');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
