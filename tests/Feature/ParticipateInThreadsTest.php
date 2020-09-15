<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_unauthenticated_users_may_not_add_replies()
    {
        $this->post('/threads/some-channel/1/replies', [])
             ->assertRedirect('/login');
    }

    /** @test */
    public function test_an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->withoutExceptionHandling();

        $user = factory('App\User')->create();
        $this->be($user);

        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    public function test_a_reply_requires_a_body()
    {
        $user = factory('App\User')->create();
        $this->be($user);

        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make(['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
             ->assertSessionHasErrors('body');
    }

    /** @test */
    public function test_unauthorized_users_cannot_delete_replies()
    {
        $reply = factory('App\Reply')->create();

        $this->delete("/replies/{$reply->id}")
             ->assertRedirect('login');

        // Sign in
        $user = factory('App\User')->create();
        $this->be($user);

        $this->delete("/replies/{$reply->id}")
             ->assertStatus(403);
    }

    /** @test */
    public function test_authorized_users_can_delete_replies()
    {
        // Sign in
        $user = factory('App\User')->create();
        $this->be($user);

        $reply = factory('App\Reply')->create([
            'user_id' => auth()->id(),
        ]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id,
        ]);
    }

    /** @test */
    public function test_authorized_users_can_update_replies()
    {
        $this->withoutExceptionHandling();

        // Sign in
        $user = factory('App\User')->create();
        $this->be($user);

        $reply = factory('App\Reply')->create([
            'user_id' => auth()->id(),
        ]);

        $this->patch("/replies/{$reply->id}", [
            'body' => 'You have been changed',
        ]);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => 'You have been changed',
        ]);
    }

    /** @test */
    public function test_unauthorized_users_cannot_update_replies()
    {
        $reply = factory('App\Reply')->create();

        $this->patch("/replies/{$reply->id}")
             ->assertRedirect('login');

        // Sign in
        $user = factory('App\User')->create();
        $this->be($user);

        $this->patch("/replies/{$reply->id}")
             ->assertStatus(403);
    }
}
