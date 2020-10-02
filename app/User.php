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
    protected $guarded = [];

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
        'confirmed' => 'boolean',
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
    
    /**
     * Save user's avatar
     */
    public function saveAvatar($file)
    {
        $this->update([
            'avatar_path' => $file->store('avatars', 'public')
        ]);
    }

    /**
     * User's avatar
     */
    public function getAvatarPathAttribute($avatar)
    {
        if (!$avatar) return asset('storage/avatars/default.png');

        return asset('storage/' . $avatar);
    }

    /**
     * Confirm the user
     */
    public function confirm()
    {
        $this->confirmed = true;

        $this->save();
    }
}
