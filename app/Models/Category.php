<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'description'];

    /**
     * A category can have many services
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
