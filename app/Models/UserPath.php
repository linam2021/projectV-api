<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPath extends Model
{
    use HasFactory;

    // Define Table User_Path
    protected $table = 'user_path';

    protected $fillable = [
        'user_id', 'path_id', 'path_start_date', 'user_status', 'level', 'repeat_chance_no', 'score',
    ];
}
