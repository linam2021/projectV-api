<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The paths that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function paths()
    {
        return $this->belongsToMany(Path::class, 'user_path');
    }

    /**
     * Get all of the pathUser for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userPaths()
    {
        return $this->hasMany(UserPath::class);
    }
    //define many to many relationship between users and messages
    public function messages()
    {
        return $this->belongsToMany('App\Models\Message')->withTimestamps()->withPivot('read');;
    }
    //define many to many relationship between users and notifications
    public function notifications()
    {
        return $this->belongsToMany('App\Models\Notification');
    }
}
