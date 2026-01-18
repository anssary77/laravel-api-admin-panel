<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create admin role
    Role::create(['name' => 'admin']);
    // Disable email verification for tests
    config(['mail.default' => 'array']);
    // Disable email verification events by removing the listener
    Event::fake([
        \Illuminate\Auth\Events\Registered::class,
    ]);
});

test('admin can login via web interface', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('password') // Use factory default password
    ]);
    $admin->assignRole('admin');
    
    $response = $this->post('/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'password'
    ]);
    
    $response->assertRedirect('/admin/dashboard');
    $this->assertAuthenticated();
});

test('admin cannot login with invalid credentials', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('password') // Use factory default password
    ]);
    $admin->assignRole('admin');
    
    $response = $this->post('/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'wrongpassword'
    ]);
    
    $response->assertRedirect('/'); // Laravel redirects to home by default for failed login
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

test('admin can logout', function () {
    $admin = User::factory()->create([
        'password' => bcrypt('password') // Explicitly hash the password
    ]);
    $admin->assignRole('admin');
    
    $response = $this->actingAs($admin)
                     ->post('/admin/logout');
    
    $this->assertGuest();
    $response->assertRedirect('/admin/login');
});

test('non-admin user cannot access admin dashboard', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password') // Explicitly hash the password
    ]);
    
    $response = $this->actingAs($user)
                    ->get('/admin/dashboard');
    
    $response->assertStatus(403);
});

test('guest cannot access admin dashboard', function () {
    $response = $this->get('/admin/dashboard');
    
    $response->assertRedirect('/admin/login');
});

test('admin can access admin dashboard', function () {
    $admin = User::factory()->create([
        'password' => bcrypt('password') // Explicitly hash the password
    ]);
    $admin->assignRole('admin');
    
    $response = $this->actingAs($admin)
                    ->get('/admin/dashboard');
    
    $response->assertStatus(200);
    $response->assertViewIs('admin.dashboard');
});