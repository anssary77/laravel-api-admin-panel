<?php

use App\Models\User;
use App\Models\SystemSetting;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create permissions for the api guard
    $permissions = [
        'settings.view',
        'settings.update',
    ];

    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'api');
    }

    $this->user = User::factory()->create();
    $this->user->guard_name = 'api';
    $this->user->syncPermissions($permissions);
    
    $this->token = JWTAuth::fromUser($this->user);
});

test('can list settings', function () {
    SystemSetting::create([
        'key' => 'site_name',
        'value' => 'Laravel Admin',
        'group' => 'general',
        'type' => 'string'
    ]);

    $response = $this->getJson('/api/v1/settings', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonStructure(['data']);
});

test('can filter settings by group', function () {
    SystemSetting::create([
        'key' => 'site_name',
        'value' => 'Laravel Admin',
        'group' => 'general',
        'type' => 'string'
    ]);
    SystemSetting::create([
        'key' => 'mail_host',
        'value' => 'smtp.mailtrap.io',
        'group' => 'email',
        'type' => 'string'
    ]);

    $response = $this->getJson('/api/v1/settings?group=email', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.key', 'mail_host');
});

test('can update settings', function () {
    SystemSetting::create([
        'key' => 'site_name',
        'value' => 'Old Name',
        'group' => 'general',
        'type' => 'string'
    ]);

    $updateData = [
        'site_name' => 'New Name'
    ];

    $response = $this->putJson('/api/v1/settings', $updateData, getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Settings updated successfully');

    $this->assertDatabaseHas('system_settings', [
        'key' => 'site_name',
        'value' => 'New Name'
    ]);
});

test('can show settings by group', function () {
    SystemSetting::create([
        'key' => 'site_name',
        'value' => 'Laravel Admin',
        'group' => 'general',
        'type' => 'string'
    ]);

    $response = $this->getJson('/api/v1/settings/general', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('unauthenticated user cannot access settings', function () {
    $response = $this->getJson('/api/v1/settings');
    $response->assertStatus(401);
});
