<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;

class NotifySubscribers
{
    public function handle(ThreadReceivedNewReply $event)
    {
        $event->reply->thread->subscriptions()
              ->notFor($event->reply->user_id)
              ->get()
              ->each->notify($event->reply);
    }
}
