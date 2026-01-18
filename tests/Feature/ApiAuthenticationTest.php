<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

/**
 * Helper function to authenticate user with JWT token
 */
function authenticateWithJwt($user): string
{
    return JWTAuth::fromUser($user);
}

beforeEach(function () {
    // Disable email verification for tests
    config(['mail.default' => 'array']);
    // Disable email verification events by removing the listener
    Event::fake([
        \Illuminate\Auth\Events\Registered::class,
    ]);
});

test('user can register via api', function () {
    $response = $this->postJson('/api/v1/register', [
        'username' => 'testuser',
        'email' => 'test@example.com',
        'mobile_number' => '+1234567890',
        'password' => 'password',
        'password_confirmation' => 'password'
    ]);
    
    $response->assertStatus(201)
             ->assertJsonStructure([
                 'message',
                 'data' => [
                     'user' => [
                         'id',
                         'name',
                         'email',
                         'mobile_number'
                     ],
                     'token',
                     'expires_in'
                 ]
             ]);
    
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'testuser',
        'mobile_number' => '+1234567890'
    ]);
    
    // Verify password was hashed correctly
    $user = User::where('email', 'test@example.com')->first();
    expect(Hash::check('password', $user->password))->toBeTrue();
});

test('user can login via api with email', function () {
    // Create user with factory default password ('password')
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password') // Explicitly hash the password
    ]);
    
    $response = $this->postJson('/api/v1/login', [
        'login' => 'test@example.com',
        'password' => 'password'
    ]);
    
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'message',
                 'data' => [
                     'user' => [
                         'id',
                         'name',
                         'email'
                     ],
                     'token'
                 ]
             ]);
});

test('user can login via api with mobile number', function () {
    // Create user with factory default password ('password')
    $user = User::factory()->create([
        'mobile_number' => '+1234567890',
        'password' => bcrypt('password') // Explicitly hash the password
    ]);
    
    $response = $this->postJson('/api/v1/login', [
        'login' => '+1234567890',
        'password' => 'password'
    ]);
    
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'message',
                 'data' => [
                     'user' => [
                         'id',
                         'name',
                         'email'
                     ],
                     'token'
                 ]
             ]);
});

test('user can logout via api', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password') // Explicitly hash the password
    ]);
    
    // Authenticate with JWT
    $token = authenticateWithJwt($user);
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/v1/logout');
    
    $response->assertStatus(200)
             ->assertJson([
                 'message' => 'Successfully logged out'
             ]);
});

test('user cannot login with invalid credentials', function () {
    // Create user with factory default password ('password')
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password') // Explicitly hash the password
    ]);
    
    $response = $this->postJson('/api/v1/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword'
    ]);
    
    $response->assertStatus(401)
             ->assertJson([
                 'message' => 'Invalid credentials'
             ]);
});

test('unauthenticated user cannot access protected routes', function () {
    $response = $this->getJson('/api/v1/profile');
    
    $response->assertStatus(401);
});

test('authenticated user can access protected routes', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password') // Explicitly hash the password
    ]);
    
    // Authenticate with JWT
    $token = authenticateWithJwt($user);
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/v1/profile');
    
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     'id',
                     'name',
                     'email'
                 ]
             ]);
});