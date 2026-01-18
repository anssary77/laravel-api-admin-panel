<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class FileManagerController extends Controller
{
    /**
     * Display the file manager interface.
     */
    public function index(Request $request)
    {
        $path = $request->get('path', '');
        $fullPath = $this->getFullPath($path);
        
        if (!File::exists($fullPath)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Path not found'], 404);
            }
            return redirect()->route('admin.file-manager.index', ['path' => '']);
        }
        
        $files = $this->getFiles($fullPath);
        $directories = $this->getDirectories($fullPath);
        $breadcrumbs = $this->getBreadcrumbs($path);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'files' => $files,
                    'directories' => $directories,
                    'path' => $path,
                    'breadcrumbs' => $breadcrumbs
                ]
            ]);
        }
        
        return view('admin.file-manager.index', compact('files', 'directories', 'path', 'breadcrumbs'));
    }
    
    /**
     * Upload a file.
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB max
            'path' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        
        $file = $request->file('file');
        $path = $request->input('path', '');
        $filename = $file->getClientOriginalName();
        
        // Sanitize filename
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        $uploadPath = $this->getFullPath($path);
        
        // Ensure directory exists
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }
        
        // Check if file already exists
        if (File::exists($uploadPath . '/' . $filename)) {
            $filename = $this->generateUniqueFilename($uploadPath, $filename);
        }
        
        try {
            $file->move($uploadPath, $filename);
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file' => [
                    'name' => $filename,
                    'size' => $this->formatBytes(File::size($uploadPath . '/' . $filename)),
                    'type' => File::extension($uploadPath . '/' . $filename),
                    'modified' => date('Y-m-d H:i:s', File::lastModified($uploadPath . '/' . $filename))
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a file or directory.
     */
    public function destroy(Request $request, $file)
    {
        $path = $request->get('path', '');
        $fullPath = $this->getFullPath($path) . '/' . $file;
        
        if (!File::exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
        
        try {
            if (File::isDirectory($fullPath)) {
                File::deleteDirectory($fullPath);
                $message = 'Directory deleted successfully';
            } else {
                File::delete($fullPath);
                $message = 'File deleted successfully';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create a new directory.
     */
    public function createDirectory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'path' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        
        $name = $request->input('name');
        $path = $request->input('path', '');
        
        // Sanitize directory name
        $name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
        
        $fullPath = $this->getFullPath($path) . '/' . $name;
        
        if (File::exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Directory already exists'
            ], 422);
        }
        
        try {
            File::makeDirectory($fullPath, 0755, true);
            
            return response()->json([
                'success' => true,
                'message' => 'Directory created successfully',
                'directory' => [
                    'name' => $name,
                    'path' => $path . ($path ? '/' : '') . $name,
                    'modified' => date('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create directory: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Rename a file or directory.
     */
    public function rename(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_name' => 'required|string',
            'new_name' => 'required|string|max:255',
            'path' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        
        $oldName = $request->input('old_name');
        $newName = $request->input('new_name');
        $path = $request->input('path', '');
        
        // Sanitize new name
        $newName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $newName);
        
        $basePath = $this->getFullPath($path);
        $oldPath = $basePath . '/' . $oldName;
        $newPath = $basePath . '/' . $newName;
        
        if (!File::exists($oldPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
        
        if (File::exists($newPath)) {
            return response()->json([
                'success' => false,
                'message' => 'A file with that name already exists'
            ], 422);
        }
        
        try {
            File::move($oldPath, $newPath);
            
            return response()->json([
                'success' => true,
                'message' => 'Renamed successfully',
                'file' => [
                    'name' => $newName,
                    'path' => $path . ($path ? '/' : '') . $newName,
                    'modified' => date('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to rename: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Download a file.
     */
    public function download(Request $request, $file)
    {
        $path = $request->get('path', '');
        $fullPath = $this->getFullPath($path) . '/' . $file;
        
        if (!File::exists($fullPath) || File::isDirectory($fullPath)) {
            abort(404, 'File not found');
        }
        
        return response()->download($fullPath);
    }
    
    /**
     * Get files in a directory.
     */
    private function getFiles($path)
    {
        if (!File::exists($path)) {
            return [];
        }
        
        $files = [];
        $items = File::files($path);
        
        foreach ($items as $file) {
            $filename = $file->getFilename();
            $extension = strtolower($file->getExtension());
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
            $url = null;
            
            if ($isImage) {
                $relativePath = str_replace($this->getFullPath(''), '', $file->getPathname());
                $url = asset('storage' . $relativePath);
            }

            $files[] = [
                'name' => $filename,
                'size' => $this->formatBytes($file->getSize()),
                'type' => $file->getExtension(),
                'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                'is_directory' => false,
                'is_image' => $isImage,
                'url' => $url
            ];
        }
        
        return $files;
    }
    
    /**
     * Get directories in a path.
     */
    private function getDirectories($path)
    {
        if (!File::exists($path)) {
            return [];
        }
        
        $directories = [];
        $items = File::directories($path);
        
        foreach ($items as $directory) {
            $directories[] = [
                'name' => basename($directory),
                'path' => str_replace($this->getFullPath(''), '', $directory),
                'modified' => date('Y-m-d H:i:s', File::lastModified($directory)),
                'is_directory' => true
            ];
        }
        
        return $directories;
    }
    
    /**
     * Get breadcrumbs for current path.
     */
    private function getBreadcrumbs($path)
    {
        $breadcrumbs = [];
        
        if (empty($path)) {
            return $breadcrumbs;
        }
        
        $parts = explode('/', trim($path, '/'));
        $currentPath = '';
        
        foreach ($parts as $part) {
            $currentPath .= ($currentPath ? '/' : '') . $part;
            $breadcrumbs[] = [
                'name' => $part,
                'path' => $currentPath
            ];
        }
        
        return $breadcrumbs;
    }
    
    /**
     * Get full path for file operations.
     */
    private function getFullPath($path)
    {
        $basePath = storage_path('app/public');
        
        if (empty($path)) {
            return $basePath;
        }
        
        // Prevent directory traversal
        $path = str_replace(['..', '//'], ['', '/'], $path);
        
        return $basePath . '/' . trim($path, '/');
    }
    
    /**
     * Generate unique filename if file exists.
     */
    private function generateUniqueFilename($path, $filename)
    {
        $counter = 1;
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        while (File::exists($path . '/' . $filename)) {
            $filename = $name . '_' . $counter . ($extension ? '.' . $extension : '');
            $counter++;
        }
        
        return $filename;
    }
    
    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}