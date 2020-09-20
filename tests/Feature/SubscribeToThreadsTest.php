<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_a_user_can_subscribe_to_threads()
    {
        $this->withoutExceptionHandling();

        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->fresh()->subscriptions);
    }

    /** @test */
    public function test_a_user_can_unsubscribe_from_threads()
    {
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
