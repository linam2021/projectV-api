<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExam extends Model
{
    use HasFactory;
    // Define Table User_Path
    protected $table = 'user_exam';
    protected $fillable = ['user_id','path_id','path_start_date','exam_id','user_exam_date','exam_result','is_well_prepared','is_easy_exam'];

    //define relqtionship between user_exam and exams tables

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
