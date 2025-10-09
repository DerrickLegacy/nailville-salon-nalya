<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockAlert;


class StockAlertController extends Controller
{
    /**
     * Return all stock alerts as JSON
     */
    public function index(Request $request)
    {
        $query = $request->input('query', '');

        $alerts = StockAlert::when($query, function ($q) use ($query) {
            $q->where('service_name', 'like', "%$query%")
                ->orWhere('service_type', 'like', "%$query%");
        })->orderBy('stock', 'asc')
            ->get();

        return response()->json($alerts);
    }

    public function getItemdetails() {}
}
