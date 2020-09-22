<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_mentioned_users_in_a_reply_are_notified()
    {
        $john = factory('App\User')->create([
            'name' => 'JohnDoe'
        ]);

        $this->actingAs($john);

        $jane = factory('App\User')->create([
            'name' => 'JaneDoe'
        ]);

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make([
            'body' => '@JaneDoe look at this'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }
}