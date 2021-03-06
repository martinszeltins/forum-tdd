<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_only_members_can_add_avatars()
    {
        $this->json('POST', 'api/users/1/avatar')
             ->assertStatus(401);
    }

    /** @test */
    public function test_a_valid_avatar_must_be_provided()
    {
        $this->actingAs(factory('App\User')->create());
        
        $this->json('POST', 'api/users/' . auth()->id() . '/avatar', [
            'avatar' => 'not-an-image'
        ])->assertStatus(422);
    }

    /** @test */
    public function test_a_user_may_add_an_avatar_to_their_profile()
    {
        $this->actingAs(factory('App\User')->create());

        Storage::fake('public');
        
        $this->json('POST', 'api/users/' . auth()->id() . '/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals(asset('storage/avatars/' . $file->hashName()), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}