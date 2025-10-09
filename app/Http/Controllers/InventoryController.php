<?php

namespace App\Http\Controllers;

use App\Models\Inventory;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class InventoryController extends Controller
{
    public function manageInventory()
    {
        return view('pages.inventory.manage');
    }
    /**
     * Return inventory data for DataTables
     */
    public function list(Request $request)
    {
        $items = Inventory::select('*')
            ->orderBy('product_id', 'desc')
            ->get();

        $totalInStockValue = Inventory::sum(DB::raw('quantity_in_stock * selling_price'));
        $totalStock = Inventory::sum('quantity_in_stock');
        $outOfStockCount = Inventory::where('quantity_in_stock', '<=', 0)->count();
        $inStockCount = Inventory::where('quantity_in_stock', '>', 0)->count();


        // Compute summary values
        $totalItems = $items->count(); // Total products
        $inStock = $items->where('quantity_in_stock', '>', 0)->count();
        $outOfStock = $items->where('quantity_in_stock', '<=', 0)->count();
        $overallCost = $items->sum(function ($item) {
            return $item->quantity_in_stock * $item->purchase_price;
        });
        return response()->json([
            'data' => $items,
            'summary' => [
                'totalItems' => $totalItems,
                'inStock' => $inStock,
                'outOfStock' => $outOfStock,
                'overallCost' => number_format($overallCost, 2)
            ],
            'totalInStockValue' => $totalInStockValue,
            'totalStock' => $totalStock,
            'outOfStockCount' => $outOfStockCount,
            'inStockCount' => $inStockCount,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'brand' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:20',
            'quantity_in_stock' => 'nullable|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:100',
        ], [
            'product_name.required' => 'Product name is required',
            'category.required' => 'Category is required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('inventory.manage')
                ->withErrors($validator)
                ->withInput();
        }

        $productNameParts = explode(' ', $request->product_name);
        $initials = '';
        foreach ($productNameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        $categoryAbbr = strtoupper(substr($request->category, 0, 2)); // first 2 letters of category
        $sku = $initials . '-' . $categoryAbbr;
        Inventory::create([
            'product_name' => $request->product_name,
            'sku' => $sku,
            'category' => $request->category,
            'brand' => $request->brand,
            'unit' => $request->unit,
            'quantity_in_stock' => $request->quantity_in_stock ?? 0,
            'reorder_level' => $request->reorder_level ?? 5,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'supplier' => $request->supplier,
        ]);
        return redirect()->route('inventory.manage')->with('success', 'Inventory product created successfully.');
    }


    public function update(Request $request, $id)
    {
        $product = Inventory::find($id);

        if (!$product) {
            return redirect()->route('inventory.manage')
                ->with('error', 'Product not found.');
        }

        // Validate input
        $request->validate([
            'product_name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'brand' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:20',
            'quantity_in_stock' => 'nullable|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:100',
        ]);

        // Regenerate SKU if product name or category changed
        $productNameParts = explode(' ', $request->product_name);
        $initials = '';
        foreach ($productNameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        $categoryAbbr = strtoupper(substr($request->category, 0, 2));
        $sku = $initials . '-' . $categoryAbbr;

        // Update product
        $product->update([
            'product_name' => $request->product_name,
            'sku' => $sku,
            'category' => $request->category,
            'brand' => $request->brand,
            'unit' => $request->unit,
            'quantity_in_stock' => $request->quantity_in_stock ?? 0,
            'reorder_level' => $request->reorder_level ?? 5,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'supplier' => $request->supplier,
        ]);

        return redirect()->route('inventory.manage')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Inventory::find($id);

        if (!$product) {
            return redirect()->route('inventory.manage')
                ->with('error', 'Product not found.');
        }

        $product->delete();

        return redirect()->route('inventory.manage')
            ->with('success', 'Product deleted successfully.');
    }

    public function inventory()
    {
        return view('inventory.price_changes');
    }
}
