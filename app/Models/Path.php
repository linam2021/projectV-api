<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    use HasFactory;

    protected $fillable = ['path_name','current_stage'];

    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    /**
     * The users that belong to the Path
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_path');
    }

    /**
     * Get all of the courses for the Path
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
