<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $fillable = [
        'service_code',
        'name',
        'category_id',
        'section_id',
        'price',
        'description',
        'status'
    ];

    /**
     * A service can appear in many transactions (incomes, etc.)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope to get only active services.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Accessor: return a formatted price with commas
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
