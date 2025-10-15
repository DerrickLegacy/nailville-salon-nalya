<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $table = 'app_settings';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id',
        'title',
        'description',
        'key',
        'value',
        'type',
        'description',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;
}
