<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp() : void
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function test_a_thread_can_make_a_string_path()
    {
        $thread = factory('App\Thread')->create();

        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->id}",
            $thread->path()
        );
    }

    /** @test */
    public function test_a_thread_has_replies()
    {
        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $this->thread->replies
        );
    }

    /** @test */
    public function test_a_thread_has_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /** @test */
    public function test_a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function test_a_thread_belongs_to_a_chanel()
    {
        $thread = factory('App\Thread')->create();

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    public function test_a_thread_can_be_subscribed_to()
    {
        $thread = factory('App\Thread')->create();

        $thread->subscribe($userID = 1);

        $this->assertEquals(1,
            $thread->subscriptions()
                   ->where('user_id', $userID)->count()
        );
    }

    /** @test */
    public function test_a_thread_can_be_unsubscribed_from()
    {
        $thread = factory('App\Thread')->create();

        $thread->subscribe($userID = 1);

        $thread->unsubscribe($userID);

        $this->assertCount(0,
            $thread->subscriptions
        );
    }

    /** @test */
    public function test_it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = factory('App\Thread')->create();

        $this->actingAs(factory('App\User')->create());

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }
}
