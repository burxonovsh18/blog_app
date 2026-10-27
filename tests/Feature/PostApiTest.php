<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_and_list_their_posts(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $this->postJson('/api/posts', [
            'title' => 'My post',
            'excerpt' => null,
            'body' => 'Post content',
            'published' => false,
        ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'My post');

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'My post',
        ]);

        $this->getJson('/api/my/posts')
            ->assertOk()
            ->assertJsonPath('data.0.title', 'My post');
    }
}
