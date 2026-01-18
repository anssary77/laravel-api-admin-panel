<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create permissions for the api guard
    $permissions = [
        'permissions.view',
    ];

    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'api');
    }

    $this->user = User::factory()->create();
    $this->user->guard_name = 'api';
    $this->user->syncPermissions($permissions);
    
    $this->token = JWTAuth::fromUser($this->user);
});

test('can list permissions', function () {
    Permission::findOrCreate('test-permission', 'api');

    $response = $this->getJson('/api/v1/permissions', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);
});

test('can search permissions', function () {
    Permission::findOrCreate('user.view', 'api');
    Permission::findOrCreate('post.view', 'api');

    $response = $this->getJson('/api/v1/permissions?search=user', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'user.view');
});

test('can filter permissions by group', function () {
    $p1 = Permission::findOrCreate('user.create', 'api');
    $p1->group = 'users';
    $p1->save();

    $p2 = Permission::findOrCreate('post.create', 'api');
    $p2->group = 'posts';
    $p2->save();

    $response = $this->getJson('/api/v1/permissions?group=users', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'user.create');
});

test('can show a permission', function () {
    $permission = Permission::findOrCreate('supervisor-access', 'api');

    $response = $this->getJson("/api/v1/permissions/{$permission->id}", getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $permission->id)
        ->assertJsonPath('data.name', 'supervisor-access');
});

test('unauthenticated user cannot access permissions', function () {
    $response = $this->getJson('/api/v1/permissions');

    $response->assertStatus(401);
});

test('cannot view permissions without permission', function () {
    $userWithoutPermission = User::factory()->create();
    $token = JWTAuth::fromUser($userWithoutPermission);

    $response = $this->getJson('/api/v1/permissions', getHeaders($token));

    $response->assertStatus(403);
});
