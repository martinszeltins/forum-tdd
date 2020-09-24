<?php

namespace Tests\Feature;

use App\Trending;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trending = new Trending;

        $this->trending->reset();
    }

    /** @test */
    public function test_it_increments_a_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());

        $thread = factory('App\Thread')->create();

        $this->call('GET', $thread->path());

        $trending = $this->trending->get();
        
        $this->assertCount(1, $trending);
    }
}