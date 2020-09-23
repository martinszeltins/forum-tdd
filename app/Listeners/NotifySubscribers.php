<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;

class NotifySubscribers
{
    public function handle(ThreadReceivedNewReply $event)
    {
        $thread = $event->reply->thread;

        $thread->subscriptions()
               ->notFor($event->reply->user_id)
               ->get()
               ->each->notify($event->reply);
    }
}
