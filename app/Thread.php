<?php

namespace App;

use App\Reply;
use App\Visits;
use Illuminate\Support\Str;
use App\Events\ThreadReceivedNewReply;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

        static::created(function ($thread) {
            $thread->update([
                'slug' => $thread->title
            ]);
        });
    }

    /**
     * Thread's path
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    /**
     * Thread's replies
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Thread's creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Add reply to thread
     */
    public function addReply($reply)
    {
        if ($this->locked) {
            throw new Exception('Thread is locked');
        }

        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    /**
     * Thread's channel
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Filter threads
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Thread's replies count
     */
    public function replyCount()
    {
        return $this->replies()->count();
    }

    /**
     * Subscribe to a thread
     */
    public function subscribe($userID = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userID ?: auth()->id(),
        ]);

        return $this;
    }

    /**
     * Unsubscribe from a thread
     */
    public function unsubscribe($userID = null)
    {
        $this->subscriptions()
             ->where('user_id', $userID ?: auth()->id())
             ->delete();
    }

    /**
     * Thread's subscriptions
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * Is thread subscribed to
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
                    ->where('user_id', auth()->id())
                    ->exists();
    }

    /**
     * Does thread have updates for a user
     */
    public function hasUpdatesFor($user)
    {
        if (!auth()->check()) return true;
        
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    /**
     * Thread's visits
     */
    public function visits()
    {
        return new Visits($this);
    }

    /**
     * Slugify the thread title
     */
    public function setSlugAttribute($value)
    {
        $slug = Str::slug($value);

        $slug = "{$slug}-" . $this->id;

        $this->attribute['slug'] = $slug;
    }

    /**
     * Mark best reply for thread
     */
    public function markBestReply(Reply $reply)
    {
        $this->best_reply_id = $reply->id;

        $this->save();
    }

    /**
     * Lock thread
     */
    public function lock()
    {
        $this->update(['locked' => true]);
    }

    /**
     * Unlock thread
     */
    public function unlock()
    {
        $this->update(['locked' => false]);
    }
}
