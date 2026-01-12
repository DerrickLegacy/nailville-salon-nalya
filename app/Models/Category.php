<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'type', 'description'];

    /**
     * A category can have many services
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Scope to get categories by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get income categories
     */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope to get expense categories
     */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }
}
