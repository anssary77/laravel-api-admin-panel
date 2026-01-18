<?php

use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create permissions for the api guard
    $permissions = [
        'roles.view',
        'roles.create',
        'roles.update',
        'roles.delete',
    ];

    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'api');
    }

    $this->user = User::factory()->create();
    $this->user->guard_name = 'api';
    $this->user->syncPermissions($permissions);
    
    $this->token = JWTAuth::fromUser($this->user);
});

test('can list roles', function () {
    Role::create(['name' => 'manager', 'guard_name' => 'api']);

    $response = $this->getJson('/api/v1/roles', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);
});

test('can search roles', function () {
    Role::create(['name' => 'editor', 'guard_name' => 'api', 'description' => 'Content editor']);
    Role::create(['name' => 'viewer', 'guard_name' => 'api', 'description' => 'Just a viewer']);

    $response = $this->getJson('/api/v1/roles?search=editor', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'editor');
});

test('can show a role', function () {
    $role = Role::create(['name' => 'supervisor', 'guard_name' => 'api']);

    $response = $this->getJson("/api/v1/roles/{$role->id}", getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $role->id)
        ->assertJsonPath('data.name', 'supervisor');
});

test('can create a role', function () {
    $roleData = [
        'name' => 'new_role',
        'guard_name' => 'api',
        'group' => 'admin',
        'description' => 'A new test role'
    ];

    $response = $this->postJson('/api/v1/roles', $roleData, getHeaders($this->token));

    $response->assertStatus(201)
        ->assertJsonPath('message', 'Role created successfully')
        ->assertJsonPath('data.name', 'new_role');

    $this->assertDatabaseHas('roles', [
        'name' => 'new_role',
        'guard_name' => 'api'
    ]);
});

test('can update a role', function () {
    $role = Role::create(['name' => 'old_role', 'guard_name' => 'api']);
    
    $updateData = [
        'name' => 'updated_role',
        'guard_name' => 'api'
    ];

    $response = $this->putJson("/api/v1/roles/{$role->id}", $updateData, getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Role updated successfully')
        ->assertJsonPath('data.name', 'updated_role');

    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => 'updated_role'
    ]);
});

test('can delete a role', function () {
    $role = Role::create(['name' => 'to_be_deleted', 'guard_name' => 'api']);

    $response = $this->deleteJson("/api/v1/roles/{$role->id}", [], getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Role deleted successfully');

    $this->assertDatabaseMissing('roles', ['id' => $role->id]);
});

test('can assign permissions to a role', function () {
    $role = Role::create(['name' => 'test_role', 'guard_name' => 'api']);
    Permission::findOrCreate('test_permission', 'api');

    $response = $this->postJson("/api/v1/roles/{$role->id}/permissions", [
        'permissions' => ['test_permission']
    ], getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Permissions assigned successfully');

    expect($role->hasPermissionTo('test_permission', 'api'))->toBeTrue();
});

test('unauthenticated user cannot access roles', function () {
    $response = $this->getJson('/api/v1/roles');
    $response->assertStatus(401);
});
