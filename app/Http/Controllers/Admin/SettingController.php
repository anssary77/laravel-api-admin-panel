<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    /**
     * Send a test email to verify SMTP settings.
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // Load settings from database
            $settings = SystemSetting::where('group', 'email')
                ->orWhereIn('key', ['site_name', 'contact_email'])
                ->get()
                ->pluck('value', 'key');

            // Set configuration dynamically
            config([
                'mail.default' => $settings['mail_mailer'] ?? config('mail.default'),
                'mail.mailers.smtp.host' => $settings['mail_host'] ?? config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => $settings['mail_port'] ?? config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.username' => $settings['mail_username'] ?? config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => $settings['mail_password'] ?? config('mail.mailers.smtp.password'),
                'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption'),
                'mail.from.address' => $settings['contact_email'] ?? config('mail.from.address'),
                'mail.from.name' => $settings['site_name'] ?? config('mail.from.name'),
            ]);

            Mail::raw('This is a test email from your Admin Panel. If you received this, your email settings are working correctly!', function ($message) use ($request, $settings) {
                $message->to($request->test_email)
                    ->subject('Test Email - ' . ($settings['site_name'] ?? 'Admin Panel'));
            });

            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Display system settings.
     */
    public function index(Request $request)
    {
        $group = $request->get('group', 'general');
        $settings = SystemSetting::all();
        $groups = SystemSetting::select('group')->distinct()->pluck('group');
        
        return view('admin.settings.index', compact('settings', 'group', 'groups'));
    }

    /**
     * Show form for editing settings group.
     */
    public function editGroup($group)
    {
        $settings = SystemSetting::where('group', $group)->get();
        
        return view('admin.settings.edit-group', compact('settings', 'group'));
    }

    /**
     * Update settings group.
     */
    public function updateGroup(Request $request, $group)
    {
        $settings = SystemSetting::where('group', $group)->get();
        
        foreach ($settings as $setting) {
            if ($request->has($setting->key)) {
                $value = $request->input($setting->key);
                
                // Validate based on type
                switch ($setting->type) {
                    case 'boolean':
                        $value = (bool) $value;
                        break;
                    case 'number':
                        $value = (int) $value;
                        break;
                    case 'array':
                        $value = json_encode(explode(',', $value));
                        break;
                    case 'json':
                        $value = json_encode(json_decode($value, true));
                        break;
                }
                
                $setting->update(['value' => $value]);
            }
        }
        
        // Clear cache
        Cache::forget('system_settings');
        Cache::forget('system_settings_' . $group);
        
        return redirect()->route('admin.settings.index', ['group' => $group])
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Update individual setting.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|exists:system_settings,key',
            'value' => 'required',
        ]);

        $setting = SystemSetting::where('key', $validated['key'])->first();
        
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found.'
            ], 404);
        }

        $value = $validated['value'];
        
        // Validate and convert based on type
        switch ($setting->type) {
            case 'boolean':
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;
            case 'number':
                $value = (int) $value;
                break;
            case 'array':
                if (is_string($value)) {
                    $value = json_encode(array_map('trim', explode(',', $value)));
                }
                break;
            case 'json':
                $json = json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid JSON format.'
                    ], 422);
                }
                $value = json_encode($json);
                break;
        }

        $setting->update(['value' => $value]);
        
        // Clear cache
        Cache::forget('system_settings');
        Cache::forget('system_settings_' . $setting->group);

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully.'
        ]);
    }

    /**
     * Clear all caches.
     */
    public function clearCache()
    {
        Cache::flush();
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        return redirect()->back()->with('success', 'All caches cleared successfully.');
    }

    /**
     * Backup settings.
     */
    public function backup()
    {
        $settings = SystemSetting::all();
        $backup = [
            'backup_date' => now()->toDateTimeString(),
            'settings' => $settings->toArray()
        ];
        
        $filename = 'settings_backup_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($backup)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Restore settings from backup.
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json'
        ]);

        try {
            $content = file_get_contents($request->file('backup_file')->getRealPath());
            $backup = json_decode($content, true);
            
            if (!isset($backup['settings']) || !is_array($backup['settings'])) {
                return back()->with('error', 'Invalid backup file format.');
            }

            DB::beginTransaction();
            
            foreach ($backup['settings'] as $settingData) {
                SystemSetting::updateOrCreate(
                    ['key' => $settingData['key']],
                    [
                        'value' => $settingData['value'],
                        'type' => $settingData['type'],
                        'group' => $settingData['group'],
                        'description' => $settingData['description'] ?? null,
                    ]
                );
            }
            
            DB::commit();
            
            // Clear cache
            Cache::flush();
            
            return redirect()->route('admin.settings.index')
                ->with('success', 'Settings restored successfully from backup.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to restore settings: ' . $e->getMessage());
        }
    }
}