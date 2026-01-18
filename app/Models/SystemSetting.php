<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @OA\Schema(
 *     schema="Setting",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="key", type="string"),
 *     @OA\Property(property="value", type="string"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="group", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="is_required", type="boolean"),
 *     @OA\Property(property="is_encrypted", type="boolean"),
 *     @OA\Property(property="metadata", type="object"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class SystemSetting extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'options',
        'is_required',
        'is_encrypted',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'options' => 'array',
        'is_encrypted' => 'boolean',
        'is_required' => 'boolean',
    ];

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['key', 'value', 'group'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('settings');
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->getValue();
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, $value, string $type = 'string', array $metadata = [], bool $encrypted = false): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $encrypted ? encrypt($value) : $value,
                'type' => $type,
                'metadata' => $metadata,
                'is_encrypted' => $encrypted,
            ]
        );
    }

    /**
     * Get the casted value based on the type.
     */
    public function getValue()
    {
        $value = $this->is_encrypted ? decrypt($this->value) : $this->value;

        return match ($this->type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            'array' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * Set the value with proper casting.
     */
    public function setValue($value): void
    {
        if ($this->is_encrypted) {
            $this->value = encrypt($value);
        } else {
            $this->value = match ($this->type) {
                'json', 'array' => json_encode($value),
                'boolean' => (bool) $value ? '1' : '0',
                default => (string) $value,
            };
        }
    }

    /**
     * Check if the setting is encrypted.
     */
    public function isEncrypted(): bool
    {
        return $this->is_encrypted;
    }

    /**
     * Scope to get only encrypted settings.
     */
    public function scopeEncrypted($query)
    {
        return $query->where('is_encrypted', true);
    }

    /**
     * Scope to get only non-encrypted settings.
     */
    public function scopeNotEncrypted($query)
    {
        return $query->where('is_encrypted', false);
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get metadata value by key.
     */
    public function getMetadata(string $key, $default = null)
    {
        return data_get($this->metadata, $key, $default);
    }

    /**
     * Set metadata value by key.
     */
    public function setMetadata(string $key, $value): void
    {
        $metadata = $this->metadata ?? [];
        data_set($metadata, $key, $value);
        $this->metadata = $metadata;
    }
}