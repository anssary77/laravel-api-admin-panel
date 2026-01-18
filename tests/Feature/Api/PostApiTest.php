<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create permissions for the api guard
    $permissions = [
        'posts.view',
        'posts.create',
        'posts.update',
        'posts.delete',
    ];

    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'api');
    }

    $this->user = User::factory()->create();
    $this->user->guard_name = 'api';
    $this->user->syncPermissions($permissions);
    
    $this->token = JWTAuth::fromUser($this->user);
});

test('can list posts', function () {
    Post::factory()->count(5)->create();

    $response = $this->getJson('/api/v1/posts', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);
});

test('can filter posts by status', function () {
    Post::factory()->create(['status' => 'published']);
    Post::factory()->create(['status' => 'draft']);

    $response = $this->getJson('/api/v1/posts?status=published', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.status', 'published');
});

test('can search posts by title', function () {
    Post::factory()->create(['title' => 'Specific Post Title']);
    Post::factory()->create(['title' => 'Other Title']);

    $response = $this->getJson('/api/v1/posts?title=Specific', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Specific Post Title');
});

test('can show a post', function () {
    $post = Post::factory()->create();

    $response = $this->getJson("/api/v1/posts/{$post->id}", getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $post->id)
        ->assertJsonPath('data.title', $post->title);
});

test('can create a post', function () {
    $postData = [
        'title' => 'New Post Title',
        'description' => 'New post description that is long enough.',
        'contact_phone_number' => '+1234567890',
        'content' => 'Full content of the post.',
        'status' => 'published'
    ];

    $response = $this->postJson('/api/v1/posts', $postData, getHeaders($this->token));

    $response->assertStatus(201)
        ->assertJsonPath('message', 'Post created successfully')
        ->assertJsonPath('data.title', 'New Post Title')
        ->assertJsonPath('data.user_id', $this->user->id);

    $this->assertDatabaseHas('posts', [
        'title' => 'New Post Title',
        'user_id' => $this->user->id
    ]);
});

test('validates post creation', function () {
    $response = $this->postJson('/api/v1/posts', [], getHeaders($this->token));

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'description', 'contact_phone_number']);
});

test('can update a post', function () {
    $post = Post::factory()->create(['user_id' => $this->user->id]);
    
    $updateData = [
        'title' => 'Updated Title'
    ];

    $response = $this->putJson("/api/v1/posts/{$post->id}", $updateData, getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Post updated successfully')
        ->assertJsonPath('data.title', 'Updated Title');

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Title'
    ]);
});

test('can delete a post', function () {
    $post = Post::factory()->create(['user_id' => $this->user->id]);

    $response = $this->deleteJson("/api/v1/posts/{$post->id}", [], getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Post deleted successfully');

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

test('unauthenticated user cannot access posts', function () {
    $response = $this->getJson('/api/v1/posts');
    $response->assertStatus(401);
});
