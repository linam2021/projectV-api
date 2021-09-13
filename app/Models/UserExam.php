<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExam extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';
    // Define Table User_Path
    protected $table = 'user_exam';
    protected $primaryKey = ['user_id', 'path_id', 'path_start_date','exam_id'];

    protected $fillable = ['user_id','path_id','path_start_date','exam_id','user_exam_date','exam_result','is_well_prepared','is_easy_exam'];

    protected function setKeysForSaveQuery($query)
    {
        return $query->where('user_id', $this->getAttribute('user_id'))
                     ->where('path_id', $this->getAttribute('path_id'))
                     ->where('path_start_date', $this->getAttribute('path_start_date'))
                     ->where('exam_id', $this->getAttribute('exam_id'));
    }
    
    //define relqtionship between user_exam and exams tables

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
