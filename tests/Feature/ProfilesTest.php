<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_a_user_has_a_profile()
    {
        $this->withoutExceptionHandling();

        $user = factory('App\User')->create();

        $this->get("/profiles/{$user->name}")
             ->assertSee($user->name);
    }

    /** @test */
    public function test_profiles_display_all_threads_created_by_the_associated_user()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->create([
            'user_id' => auth()->id()
        ]);

        $this->get("/profiles/" . auth()->user()->name)
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }
}
