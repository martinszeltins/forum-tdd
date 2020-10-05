<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_a_thread_creator_may_mark_any_reply_as_best_reply()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $replies = factory('App\Reply', 2)->create([ 'thread_id' => $thread->id ]);

        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function test_only_the_thread_creator_may_mark_a_reply_as_best()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $replies = factory('App\Reply', 2)->create([ 'thread_id' => $thread->id ]);

        $this->actingAs(factory('App\User')->create());

        $this->postJson(route('best-replies.store', [$replies[1]->id]))->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function test_if_a_best_reply_is_deleted_then_the_thread_is_updated_to_reflect_that()
    {
        DB::statement('PRAGMA foreign_keys=on');
        
        $this->actingAs(factory('App\User')->create());

        $reply = factory('App\Reply')->create(['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        $this->deleteJson(route('replies.destroy', $reply));

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}