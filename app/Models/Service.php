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
        'trans_type',
        'section_id',
        'price',
        'description',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2'
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
     * Scope to get only inactive services.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    /**
     * Scope to get income services
     */
    public function scopeIncome($query)
    {
        return $query->where('trans_type', 'income');
    }

    /**
     * Scope to get expense services
     */
    public function scopeExpense($query)
    {
        return $query->where('trans_type', 'expense');
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to filter by section
     */
    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    /**
     * Accessor: return a formatted price with commas
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    /**
     * Accessor: return capitalized trans_type
     */
    public function getTransTypeFormattedAttribute()
    {
        return ucfirst($this->trans_type);
    }

    /**
     * Accessor: return status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $class = $this->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$class}\">{$this->status}</span>";
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
