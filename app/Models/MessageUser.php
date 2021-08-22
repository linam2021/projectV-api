<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class MessageUser extends Model
{
    use HasFactory;
    //specify table name
    protected $table='message_user';
    protected $fillable=['user_id','message_id','read' ];

}
