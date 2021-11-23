<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['path_id', 'course_name', 'course_link','course_start_date','course_duration','stage','questionbank_course_id'];

    protected $hidden = ['created_at', 'updated_at', 'path_id'];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
