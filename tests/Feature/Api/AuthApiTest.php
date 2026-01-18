<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->password = 'password123';
    $this->user = User::factory()->create([
        'name' => 'testuser',
        'email' => 'test@example.com',
        'mobile_number' => '1234567890',
        'password' => Hash::make($this->password),
    ]);
});

test('user can login with email', function () {
    $response = $this->postJson('/api/v1/login', [
        'login' => 'test@example.com',
        'password' => $this->password,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data' => [
                'user',
                'token',
                'expires_in'
            ]
        ]);
});

test('user can login with mobile number', function () {
    $response = $this->postJson('/api/v1/login', [
        'login' => '1234567890',
        'password' => $this->password,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data' => [
                'user',
                'token',
                'expires_in'
            ]
        ]);
});

test('user cannot login with invalid credentials', function () {
    $response = $this->postJson('/api/v1/login', [
        'login' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401)
        ->assertJsonPath('message', 'Invalid credentials');
});

test('user can register', function () {
    $response = $this->postJson('/api/v1/register', [
        'username' => 'newuser',
        'email' => 'new@example.com',
        'mobile_number' => '0987654321',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'data' => [
                'user',
                'token',
                'expires_in'
            ]
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'new@example.com',
        'name' => 'newuser'
    ]);
});

test('user can get profile', function () {
    $token = JWTAuth::fromUser($this->user);

    $response = $this->getJson('/api/v1/profile', getHeaders($token));

    $response->assertStatus(200)
        ->assertJsonPath('data.email', $this->user->email);
});

test('user can update profile', function () {
    $token = JWTAuth::fromUser($this->user);

    $response = $this->putJson('/api/v1/profile', [
        'name' => 'Updated Name',
        'first_name' => 'John',
        'last_name' => 'Doe',
    ], getHeaders($token));

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Profile updated successfully');

    $this->user->refresh();
    expect($this->user->name)->toBe('Updated Name');
    expect($this->user->profile['first_name'])->toBe('John');
});

test('user can refresh token', function () {
    $token = JWTAuth::fromUser($this->user);

    $response = $this->postJson('/api/v1/refresh', [], getHeaders($token));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data' => [
                'token',
                'expires_in'
            ]
        ]);
});

test('user can logout', function () {
    $token = auth('api')->login($this->user);

    $response = $this->postJson('/api/v1/logout', [], getHeaders($token));

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Successfully logged out');
    
    // Clear auth state to force a fresh check of the token
     auth('api')->logout();
    
    $this->getJson('/api/v1/profile', getHeaders($token))
        ->assertStatus(401);
});

test('user can change password', function () {
    $token = JWTAuth::fromUser($this->user);

    $response = $this->postJson('/api/v1/change-password', [
        'current_password' => $this->password,
        'new_password' => 'new-password123',
        'new_password_confirmation' => 'new-password123',
    ], getHeaders($token));

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Password changed successfully');

    $this->user->refresh();
    expect(Hash::check('new-password123', $this->user->password))->toBeTrue();
});
