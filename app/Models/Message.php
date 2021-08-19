<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'body',
        'admin_id'
    ];

    //define one to many relationship between users and messages tables
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    //define many to many relationship
    //one message can be sent to many users and vice-versa
    public function users(){
        return $this->belongsToMany('App\Models\User')->withTimestamps()->withPivot('read');
    }

    //define one to one relationship between messages and nbotifications tables, a message has one notification
    public function nitification(){
        return $this->hasOne('App\Models\Notification');
    }
}
