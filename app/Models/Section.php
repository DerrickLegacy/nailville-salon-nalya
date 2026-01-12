<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';
    protected $fillable = ['name', 'service_type', 'description'];

    /**
     * A section can have many services
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Scope to get sections by service type
     */
    public function scopeByServiceType($query, $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }

    /**
     * Scope to get income sections
     */
    public function scopeIncome($query)
    {
        return $query->where('service_type', 'income');
    }

    /**
     * Scope to get expense sections
     */
    public function scopeExpense($query)
    {
        return $query->where('service_type', 'expense');
    }
}
