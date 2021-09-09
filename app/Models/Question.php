<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_text',
        'question_image_url',
        'answer_a',
        'answer_b',
        'answer_c',
        'correct_answer',
        'primary_question_id',
        'exam_id'
    ];

    protected $hidden = [
        'primary_question_id',
        'exam_id',
        'created_at',
        'updated_at'
    ];

    public function sub_question()
    {
    	return $this->hasOne('App\Models\Question', 'primary_question_id','id');
    }

    public function primary_question()
    {
    	return $this->belongsTo('App\Models\Question', 'primary_question_id','id');
    }
}
