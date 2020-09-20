<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class ThreadSubscription extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notify($reply)
    {
        $this->user->notify(
            new ThreadWasUpdated($this->thread, $reply)
        );
    }

    public function scopeNotFor($query, $user_id)
    {
        return $query->where('user_id', '<>', $user_id);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}