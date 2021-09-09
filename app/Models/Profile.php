<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'father_name', 'last_name', 'telegram', 'phone', 'country', 'gender'];

    public function  user(){
        $this -> hasOne(User::class,'profile_id', 'id');
    }
}
