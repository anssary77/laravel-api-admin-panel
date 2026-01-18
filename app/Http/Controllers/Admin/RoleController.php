<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        $query = Role::with(['permissions']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by group
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        $roles = $query->paginate(20);
        $groups = Role::select('group')->distinct()->pluck('group');

        return view('admin.roles.index', compact('roles', 'groups'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $guardName = request()->input('guard_name', 'web');
        $permissions = Permission::where('guard_name', $guardName)->get();
        $groups = Permission::select('group')->distinct()->pluck('group');
        
        return view('admin.roles.form', compact('permissions', 'groups'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string|max:500',
            'group' => 'nullable|string|max:50',
            'guard_name' => 'required|in:web,api',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $validator->after(function ($validator) use ($request) {
            $selected = $request->input('permissions', []);
            if (!is_array($selected) || count($selected) === 0) {
                return;
            }

            $guardName = $request->input('guard_name', 'web');
            $allowed = Permission::where('guard_name', $guardName)
                ->whereIn('name', $selected)
                ->pluck('name')
                ->all();

            if (count($allowed) !== count($selected)) {
                $validator->errors()->add('permissions', 'Selected permissions do not match the selected guard.');
            }
        });

        $validated = $validator->validate();

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'group' => $validated['group'] ?? null,
                'guard_name' => $validated['guard_name'],
            ]);

            if (isset($validated['permissions'])) {
                $allowed = Permission::where('guard_name', $validated['guard_name'])
                    ->whereIn('name', $validated['permissions'])
                    ->pluck('name')
                    ->all();
                $role->syncPermissions($allowed);
            }

            DB::commit();
            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        return redirect()->route('admin.roles.edit', $role);
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $guardName = request()->input('guard_name', old('guard_name', $role->guard_name));
        $permissions = Permission::where('guard_name', $guardName)->get();
        $role->load('permissions')->loadCount(['users', 'permissions']);
        
        return view('admin.roles.form', compact('role', 'permissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        $effectiveGuard = $request->input('guard_name', $role->guard_name);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'group' => 'nullable|string|max:50',
            'guard_name' => 'nullable|in:web,api',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $validator->after(function ($validator) use ($request, $effectiveGuard) {
            $selected = $request->input('permissions', []);
            if (!is_array($selected) || count($selected) === 0) {
                return;
            }

            $allowed = Permission::where('guard_name', $effectiveGuard)
                ->whereIn('name', $selected)
                ->pluck('name')
                ->all();

            if (count($allowed) !== count($selected)) {
                $validator->errors()->add('permissions', 'Selected permissions do not match the selected guard.');
            }
        });

        $validated = $validator->validate();

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'group' => $validated['group'] ?? null,
                'guard_name' => $validated['guard_name'] ?? $role->guard_name,
            ]);

            if (isset($validated['permissions'])) {
                $guardName = $validated['guard_name'] ?? $role->guard_name;
                $allowed = Permission::where('guard_name', $guardName)
                    ->whereIn('name', $validated['permissions'])
                    ->pluck('name')
                    ->all();
                $role->syncPermissions($allowed);
            }

            DB::commit();
            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role that has users assigned to it.');
        }

        DB::beginTransaction();
        try {
            $role->delete();
            DB::commit();
            return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:roles,id',
        ]);

        $roles = Role::whereIn('id', $validated['ids'])->withCount('users')->get();

        DB::beginTransaction();
        try {
            $deleted = 0;
            foreach ($roles as $role) {
                if (($role->users_count ?? 0) > 0) {
                    continue;
                }
                $role->delete();
                $deleted++;
            }

            DB::commit();
            return back()->with('success', "Deleted {$deleted} roles successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to bulk delete roles: ' . $e->getMessage());
        }
    }

    /**
     * Update role permissions.
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            $role->syncPermissions($validated['permissions']);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permissions: ' . $e->getMessage()
            ], 500);
        }
    }
}