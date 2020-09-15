<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_a_channel_consists_of_threads()
    {
        $channel = factory('App\Channel')->create();

        $thread = factory('App\Thread')->create([
            'channel_id' => $channel->id,
        ]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}