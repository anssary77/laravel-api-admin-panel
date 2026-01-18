<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create permissions for the api guard
    $permissions = [
        'users.view',
        'users.create',
        'users.update',
        'users.delete',
    ];

    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'api');
    }

    $this->user = User::factory()->create();
    $this->user->guard_name = 'api';
    $this->user->syncPermissions($permissions);
    
    $this->token = JWTAuth::fromUser($this->user);
});

test('can list users', function () {
    User::factory()->count(5)->create();

    $response = $this->getJson('/api/v1/users', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);
});

test('can search users by name', function () {
    User::factory()->create(['name' => 'Specific User Name']);
    User::factory()->create(['name' => 'Other Name']);

    $response = $this->getJson('/api/v1/users?search=Specific', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Specific User Name');
});

test('can filter users by status', function () {
    User::factory()->create(['status' => 'suspended']);
    
    $response = $this->getJson('/api/v1/users?status=suspended', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.status', 'suspended');
});

test('can show a user', function () {
    $user = User::factory()->create();

    $response = $this->getJson("/api/v1/users/{$user->id}", getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonPath('data.email', $user->email);
});

test('can create a user', function () {
    $userData = [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'status' => 'active',
        'profile' => [
            'first_name' => 'New',
            'last_name' => 'User',
            'phone' => '+1234567890'
        ]
    ];

    $response = $this->postJson('/api/v1/users', $userData, getHeaders($this->token));

    $response->assertStatus(201)
        ->assertJsonPath('message', 'User created successfully')
        ->assertJsonPath('data.email', 'newuser@example.com');

    $this->assertDatabaseHas('users', [
        'email' => 'newuser@example.com'
    ]);
});

test('validates user creation', function () {
    $response = $this->postJson('/api/v1/users', [], getHeaders($this->token));

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

test('can update a user', function () {
    $user = User::factory()->create();
    
    $updateData = [
        'name' => 'Updated Name'
    ];

    $response = $this->putJson("/api/v1/users/{$user->id}", $updateData, getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('message', 'User updated successfully')
        ->assertJsonPath('data.name', 'Updated Name');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name'
    ]);
});

test('can delete a user', function () {
    $user = User::factory()->create();

    $response = $this->deleteJson("/api/v1/users/{$user->id}", [], getHeaders($this->token));

    $response->assertStatus(204);

    $this->assertSoftDeleted('users', ['id' => $user->id]);
});

test('unauthenticated user cannot access users', function () {
    $response = $this->getJson('/api/v1/users');
    $response->assertStatus(401);
});
