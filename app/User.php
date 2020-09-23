<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * User's threads.
     */
    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    /**
     * User's activity.
     */
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Visited Thread's key
     */
    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    /**
     * User reads a read
     */
    public function read($thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            Carbon::now()
        );
    }

    /**
     * User's last reply.
     */
    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }
    
    /**
     * Search user by name
     */
    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', "$name%")
                     ->take(3)
                     ->pluck('name');
    }
}
