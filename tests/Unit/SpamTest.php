<?php

namespace Tests\Unit;

use App\Spam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_it_validates_spam()
    {
        $spam = new Spam;

        $this->assertFalse($spam->detect('Innocent reply here'));
    }
}