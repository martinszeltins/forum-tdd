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

    /** @test */
    public function test_it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        factory('App\User')->create(['name' => 'johndoe']);
        factory('App\User')->create(['name' => 'johndoe2']);
        factory('App\User')->create(['name' => 'janedoe']);

        $results = $this->json('GET', '/api/users', [
            'name' => 'joh'
        ]);

        $this->assertCount(2, $results->json());
    }
}