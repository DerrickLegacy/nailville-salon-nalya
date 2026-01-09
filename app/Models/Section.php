<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';
    protected $fillable = ['name', 'description'];

    /**
     * A section can have many services
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
