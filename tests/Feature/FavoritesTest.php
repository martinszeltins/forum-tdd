<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_guests_cannot_favorite_anything()
    {
        $this->post("replies/1/favorites")
             ->assertRedirect('/login');
    
    }

    /** @test */
    public function test_an_authenticated_user_can_favorite_any_reply()
    {
        $this->actingAs(factory('App\User')->create());

        $reply = factory('App\Reply')->create();

        $this->post("replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function test_an_authenticated_user_can_unfavorite_a_reply()
    {
        $this->actingAs(factory('App\User')->create());

        $reply = factory('App\Reply')->create();

        $reply->favorite();

        $this->delete("replies/{$reply->id}/favorites");

        $this->assertCount(0, $reply->favorites);
    }

    /** @test */
    public function test_an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory('App\User')->create());

        $reply = factory('App\Reply')->create();

        try {
            $this->post("replies/{$reply->id}/favorites");
            $this->post("replies/{$reply->id}/favorites");
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record twice.');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
