<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @OA\Schema(
 *     schema="Permission",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="guard_name", type="string"),
 *     @OA\Property(property="group", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Permission extends SpatiePermission
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'guard_name',
        'group',
        'description',
    ];

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'group', 'description'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('permission');
    }

    /**
     * Scope to filter by group.
     */
    public function scopeInGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope to get permissions with groups.
     */
    public function scopeWithGroup($query)
    {
        return $query->whereNotNull('group');
    }

    /**
     * Get permissions grouped by group name.
     */
    public static function getGroupedPermissions()
    {
        return static::whereNotNull('group')
                    ->orderBy('group')
                    ->orderBy('name')
                    ->get()
                    ->groupBy('group');
    }
}