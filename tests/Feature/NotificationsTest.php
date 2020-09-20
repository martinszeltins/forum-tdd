<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create()->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply',
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => factory('App\User')->create()->id,
            'body' => 'Some reply',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function test_a_user_can_fetch_their_unread_notifications()
    {
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create()->subscribe();

        $thread->addReply([
            'user_id' => factory('App\User')->create()->id,
            'body' => 'Some reply',
        ]);

        $user = auth()->user();

        $response = $this->getJson("profiles/{$user->name}/notifications/")->json();

        $this->assertCount(1, $response);
    }
}
