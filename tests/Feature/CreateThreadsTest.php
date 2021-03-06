<?php

namespace Tests\Feature;

use App\Activity;
use App\Rules\Recaptcha;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $m = Mockery::mock(Recaptcha::class);

        $m->shouldReceive('passes')->andReturn(true);

        app()->singleton(Recaptcha::class, $m);
    }

    /** @test */
    public function test_guests_may_not_create_threads()
    {
        $this->get('/threads/create')
             ->assertRedirect('/login');

        $this->post(route('threads'))
             ->assertRedirect('/login');
    }

    /** @test */
    public function test_a_user_can_create_new_forum_threads()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->make();

        $response = $this->post('/threads', $thread->toArray() + [
            'g-recaptcha-response' => 'token'
        ]);
        
        $this->get($response->headers->get('Location'))
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }

    /** @test */
    public function test_a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
             ->assertSessionHasErrors('title');
    }

    /** @test */
    public function test_a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
             ->assertSessionHasErrors('body');
    }

    /** @test */
    public function test_a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();
        
        $this->publishThread(['channel_id' => null])
             ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
             ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->make($overrides);

        return $this->post(route('threads'), $thread->toArray());
    }

    /** @test */
    public function test_authorized_users_can_delete_threads()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->create([
            'user_id' => auth()->id(),
        ]);

        $reply = factory('App\Reply')->create([
            'thread_id' => $thread->id,
        ]);
        
        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', [
            'id' => $thread->id,
        ]);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id,
        ]);

        $this->assertEquals(0, Activity::count());
    }

    /** @test */
    public function test_unauthorized_users_may_not_delete_threads()
    {
        $thread = factory('App\Thread')->create();

        $this->delete($thread->path())->assertRedirect('/login');

        $this->actingAs(factory('App\User')->create());

        $this->delete($thread->path())->assertStatus(403);
    }

    /** @test */
    public function test_new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->actingAs($user);

        $thread = factory('App\Thread')->make();

        $this->post(route('threads'), $thread->toArray())
             ->assertRedirect(route('threads'))
             ->assertSessionHas('flash', 'You must first confirm you email address');
    }

    /** @test */
    public function test_a_thread_requires_a_unique_slug()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->create([
            'title' => 'Title',
            'slug' => 'title',
        ]);

        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(Thread::whereSlug('title-2')->exists());
    }

    /** @test */
    public function test_a_thread_requires_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'test'])
             ->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    public function test_a_thread_can_be_updated()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->create([
            'user_id' => auth()->id(),
        ]);

        $this->patchJson($thread->path(), [
            'title' => 'Changed',
            'body' => 'Changed body.',
        ]);

        $this->assertEquals('Changed', $thread->fresh()->title);
        $this->assertEquals('Changed body.', $thread->fresh()->body);
    }
}