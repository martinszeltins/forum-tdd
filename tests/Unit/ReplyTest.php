<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_it_has_an_owner()
    {
        $reply = factory('App\Reply')->create();

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    public function test_it_knows_if_it_was_just_published()
    {
        $reply = factory('App\Reply')->create();
        
        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function test_it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = factory('App\Reply')->create([
            'body' => 'Hello @Martins',
        ]);
        
        $this->assertEquals(
            'Hello <a href="/profiles/Martins">@Martins</a>',
            $reply->body
        );
    }

    /** @test */
    public function test_it_knows_if_it_is_the_best_reply()
    {
        $reply = factory('App\Reply')->create();

        $this->assertFalse($reply->isBest());

        $reply->thread->update([
            'best_reply_id' => $reply->id,
        ]);

        $this->assertTrue($reply->fresh()->isBest());
    }
}