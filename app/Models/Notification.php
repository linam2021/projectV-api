<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'body',
        'message_id'
    ];


    //define many to many relationship
    //one notification can be sent to many users and vice-versa
    public function users(){
        return $this->belongsToMany('App\Models\User')->withTimestamps()->withPivot('read');
    }
    //define one to one relationship between notifications and messages one notification belongs to one message
    public function message(){
        return $this->belongsTo('App\Models\Message');
    }
}
