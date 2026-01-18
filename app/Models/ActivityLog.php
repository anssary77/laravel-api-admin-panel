<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="ActivityLog",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="log_name", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="subject_id", type="string", format="uuid"),
 *     @OA\Property(property="subject_type", type="string"),
 *     @OA\Property(property="event", type="string"),
 *     @OA\Property(property="causer_id", type="string", format="uuid"),
 *     @OA\Property(property="causer_type", type="string"),
 *     @OA\Property(property="properties", type="object"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'log_name',
        'description',
        'subject_id',
        'subject_type',
        'event',
        'causer_id',
        'causer_type',
        'properties',
        'batch_uuid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get the subject model that the activity belongs to.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the causer model that caused the activity.
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that caused the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    /**
     * Scope to filter by log name.
     */
    public function scopeInLog($query, string $logName)
    {
        return $query->where('log_name', $logName);
    }

    /**
     * Scope to filter by event.
     */
    public function scopeForEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope to filter by subject type.
     */
    public function scopeForSubject($query, string $subjectType)
    {
        return $query->where('subject_type', $subjectType);
    }

    /**
     * Scope to filter by causer.
     */
    public function scopeCausedBy($query, $causer)
    {
        return $query->where('causer_id', $causer->id)
                    ->where('causer_type', get_class($causer));
    }

    /**
     * Scope to get activities for a specific date range.
     */
    public function scopeBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get the changes attribute from properties.
     */
    public function getChangesAttribute()
    {
        return $this->properties['changes'] ?? null;
    }

    /**
     * Get the old values attribute from properties.
     */
    public function getOldAttribute()
    {
        return $this->properties['old'] ?? null;
    }

    /**
     * Get the new values attribute from properties.
     */
    public function getAttributesAttribute()
    {
        return $this->properties['attributes'] ?? null;
    }
}