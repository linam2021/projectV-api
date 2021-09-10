<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserPath extends Model
{
    use HasFactory;

    // Define Table User_Path
    protected $table = 'user_path';
    //protected $primaryKey = ['user_id', 'path_id', 'path_start_date'];

    protected $fillable = [
        'user_id', 'path_id', 'path_start_date', 'user_status', 'repeat_chance_no', 'score', 'answer_join', 'answer_accept_order',
    ];

    protected function setKeysForSaveQuery($query)
    {
        return $query->where('user_id', $this->getAttribute('user_id'))
                     ->where('path_id', $this->getAttribute('path_id'))
                     ->where('path_start_date', $this->getAttribute('path_start_date'));
    }
}
