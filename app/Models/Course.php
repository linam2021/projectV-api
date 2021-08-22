<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['path_id', 'name', 'course_link'];

    protected $hidden = ['created_at', 'updated_at'];
}
