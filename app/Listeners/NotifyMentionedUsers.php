<?php

namespace App\Listeners;

use App\User;
use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{
    /**
     * Notify mentioned users.
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        $mentionedUsers = $event->reply->mentionedUsers();

        foreach ($mentionedUsers as $name) {
            $user = User::whereName($name)->first();
            $this->notify($user, $event);
        }
    }

    /**
     * Notify the mentioned user.
     */
    public function notify($user, $event)
    {
        if ($user) {
            $user->notify(new YouWereMentioned($event->reply));
        }
    }
}