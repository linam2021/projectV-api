<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = ['exam_type','course_id','exam_start_date','exam_duration','questions_count','sucess_mark','maximum_mark'];

}
