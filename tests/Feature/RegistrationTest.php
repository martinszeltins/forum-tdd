<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_a_confirmation_email_is_sent_upon_registration()
    {
        $user = factory('App\User')->create();

        Mail::fake();

        event(new Registered($user));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function test_user_can_fully_confirm_their_email_addresses()
    {
        factory('App\User')->create([
            'name' => 'John',
            'confirmation_token' => Str::random(),
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $response = $this->get('/register/confirm?token=' . $user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect('/threads');
    }
}