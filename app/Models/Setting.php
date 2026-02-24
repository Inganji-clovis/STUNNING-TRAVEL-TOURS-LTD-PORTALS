<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'name',
        'locked',
        'payload',
    ];

    protected $casts = [
        'locked' => 'boolean',
        'payload' => 'array',
    ];

    /**
     * Helper to get a setting value quickly.
     */
    public static function getVal($name, $default = null)
    {
        $setting = self::where('name', $name)->first();
        return $setting ? $setting->payload : $default;
    }

    /**
     * Helper to set a setting value quickly.
     */
    public static function setVal($name, $payload, $group = 'general', $locked = false)
    {
        return self::updateOrCreate(
            ['name' => $name],
            ['payload' => $payload, 'group' => $group, 'locked' => $locked]
        );
    }
}
