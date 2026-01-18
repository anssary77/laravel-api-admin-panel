<?php

use App\Http\Controllers\Admin\FileManagerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Create test directory structure
    $testPath = storage_path('app/public/test');
    if (!File::exists($testPath)) {
        File::makeDirectory($testPath, 0755, true);
    }
    
    // Create some test files
    File::put($testPath . '/test.txt', 'Test content');
    File::put($testPath . '/test2.txt', 'Test content 2');
    File::makeDirectory($testPath . '/subdir', 0755, true);
    File::put($testPath . '/subdir/nested.txt', 'Nested content');
});

afterEach(function () {
    // Clean up test files
    $testPath = storage_path('app/public/test');
    if (File::exists($testPath)) {
        File::deleteDirectory($testPath);
    }
});

test('file manager index returns correct view data', function () {
    $controller = new FileManagerController();
    $request = Request::create('/admin/file-manager', 'GET', ['path' => 'test']);
    
    $response = $controller->index($request);
    
    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
    expect($response->getData())->toHaveKeys(['files', 'directories', 'path', 'breadcrumbs']);
    expect($response->getData()['path'])->toBe('test');
});

test('file manager handles invalid path gracefully', function () {
    $controller = new FileManagerController();
    $request = Request::create('/admin/file-manager', 'GET', ['path' => 'nonexistent']);
    
    $response = $controller->index($request);
    
    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
    expect($response->getTargetUrl())->toContain('admin/file-manager');
});

test('file manager creates directory successfully', function () {
    $controller = new FileManagerController();
    $request = Request::create('/admin/file-manager/create-directory', 'POST', [
        'name' => 'new-folder',
        'path' => 'test'
    ]);
    
    $response = $controller->createDirectory($request);
    $data = json_decode($response->getContent(), true);
    
    expect($response)->toBeInstanceOf(\Illuminate\Http\JsonResponse::class);
    expect($data['success'])->toBeTrue();
    expect($data['message'])->toBe('Directory created successfully');
    
    // Verify directory was created
    expect(File::exists(storage_path('app/public/test/new-folder')))->toBeTrue();
});

test('file manager prevents directory traversal', function () {
    $controller = new FileManagerController();
    $request = Request::create('/admin/file-manager', 'GET', ['path' => '../../../etc']);
    
    $response = $controller->index($request);
    
    // Should redirect to safe path
    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
});

test('file manager sanitizes file names', function () {
    $controller = new FileManagerController();
    
    // Test filename sanitization through reflection
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('generateUniqueFilename');
    $method->setAccessible(true);
    
    $result = $method->invoke($controller, storage_path('app/public'), 'test.txt');
    
    expect($result)->toBeString();
    expect($result)->toContain('test');
});

test('file manager formats bytes correctly', function () {
    $controller = new FileManagerController();
    
    // Test byte formatting through reflection
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('formatBytes');
    $method->setAccessible(true);
    
    expect($method->invoke($controller, 1024))->toBe('1 KB');
    expect($method->invoke($controller, 1048576))->toBe('1 MB');
    expect($method->invoke($controller, 512))->toBe('512 B');
});