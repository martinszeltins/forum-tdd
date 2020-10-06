<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body' => 'Body',
            'user_id' => auth()->id(),
        ])->assertStatus(422);
    }

    /** @test */
    public function test_non_administrators_may_not_lock_threads()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))->assertStatus(403);

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    public function test_administrators_can_lock_threads()
    {
        $this->actingAs(
            factory('App\User')->states('administrator')->create()
        );

        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue(
            !! $thread->fresh()->locked,
            'Failed asserting that the thread was locked.'
        );
    }
}