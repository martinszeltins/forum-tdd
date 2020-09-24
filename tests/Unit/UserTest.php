<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_a_user_can_fetch_their_most_recent_reply()
    {
        $user = factory('App\User')->create();

        $reply = factory('App\Reply')->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    public function test_a_user_can_determine_their_avatar_path()
    {
        $user = factory('App\User')->create();
        
        $this->assertEquals(asset('storage/avatars/default.png'), $user->avatar_path);

        $user->avatar_path = 'avatars/me.jpg';
        
        $this->assertEquals(asset('storage/avatars/me.jpg'), $user->avatar_path);
    }
}