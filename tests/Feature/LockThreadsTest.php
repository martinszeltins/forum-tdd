<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_an_administrator_can_lock_any_thread()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body' => 'Body',
            'user_id' => auth()->id(),
        ])->assertStatus(422);
    }
}