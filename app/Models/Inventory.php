<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Inventory extends Model
{
    // Specify the table if it doesn't follow Laravel's naming convention
    protected $table = 'inventory_products';
    protected $primaryKey = 'product_id';

    // Mass-assignable fields
    protected $fillable = [
        'product_name',
        'sku',
        'category',
        'brand',
        'unit',
        'quantity_in_stock',
        'reorder_level',
        'purchase_price',
        'selling_price',
        'supplier',
    ];

    // Optional: cast types automatically
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    // Accessor for total value (quantity * price)
    protected function totalValue(): Attribute
    {
        return Attribute::get(fn() => $this->quantity * $this->price);
    }

    // Scope to filter in-stock items
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    // Scope to filter out-of-stock items
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }
}
