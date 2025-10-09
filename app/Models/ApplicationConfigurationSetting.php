<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationConfigurationSetting extends Model
{
    protected $table = 'app_settings';
    protected $fillable = ['title', 'key', 'value', 'type', 'description'];
    public $timestamps = true;

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
