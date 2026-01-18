<?php

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->user->guard_name = 'api';
    $this->token = JWTAuth::fromUser($this->user);
    
    // Create permission for api guard
    Permission::findOrCreate('activity-logs.view', 'api');
    $this->user->syncPermissions(['activity-logs.view']);

    // Clear any activity logs created during setup (e.g., user creation)
    ActivityLog::truncate();
});

test('can list activity logs', function () {
    ActivityLog::create([
        'log_name' => 'user',
        'description' => 'User logged in',
        'causer_id' => $this->user->id,
        'causer_type' => User::class,
    ]);

    $response = $this->getJson('/api/v1/activity-logs', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('can filter activity logs by log_name', function () {
    ActivityLog::create([
        'log_name' => 'user',
        'description' => 'User logged in',
    ]);
    ActivityLog::create([
        'log_name' => 'post',
        'description' => 'Post created',
    ]);

    $response = $this->getJson('/api/v1/activity-logs?log_name=user', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.log_name', 'user');
});

test('can search activity logs by description', function () {
    ActivityLog::create([
        'description' => 'Unique description',
    ]);
    ActivityLog::create([
        'description' => 'Another log',
    ]);

    $response = $this->getJson('/api/v1/activity-logs?description=Unique', getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.description', 'Unique description');
});

test('can show a specific activity log', function () {
    $activity = ActivityLog::create([
        'log_name' => 'user',
        'description' => 'User logged in',
    ]);

    $response = $this->getJson("/api/v1/activity-logs/{$activity->id}", getHeaders($this->token));

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $activity->id)
        ->assertJsonPath('data.description', 'User logged in');
});

test('cannot view activity logs without permission', function () {
    $userWithoutPermission = User::factory()->create();
    $token = JWTAuth::fromUser($userWithoutPermission);

    $response = $this->getJson('/api/v1/activity-logs', getHeaders($token));

    $response->assertStatus(403);
});
