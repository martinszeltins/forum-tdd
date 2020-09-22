<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_it_checks_for_invalid_keywords()
    {
        $spam = new Spam;

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException('Exception');

        $spam->detect('Yahoo Customer Support');
    }

    /** @test */
    public function test_checks_for_any_key_being_held_down()
    {
        $spam = new Spam;

        $this->expectException('Exception');
        
        $spam->detect('Hello world aaaaaaaaaaaa');
    }
}