<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * Display services management page
     */
    public function index()
    {
        return view('pages.services.services');
    }

    /**
     * Get services list for DataTable
     */
    public function list(Request $request)
    {
        $query = Service::with(['category:id,name', 'section:id,name']);

        /** 🔍 Search */
        if (!empty($request->search['value'])) {
            $search = $request->search['value'];

            $query->where(function ($q) use ($search) {
                $q->where('services.name', 'like', "%{$search}%")
                    ->orWhere('service_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas(
                        'category',
                        fn($c) =>
                        $c->where('name', 'like', "%{$search}%")
                    )
                    ->orWhereHas(
                        'section',
                        fn($s) =>
                        $s->where('name', 'like', "%{$search}%")
                    );
            });
        }

        /** 🎯 Filters */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        /** 📊 Counts */
        $totalRecords = Service::count();
        $filteredRecords = $query->count();

        /** ↕ Sorting */
        $columns = [
            'id',
            'service_code',
            'name',
            'price',
            'status',
            'created_at'
        ];

        $orderColumnIndex = $request->order[0]['column'] ?? 0;
        $orderDir = $request->order[0]['dir'] ?? 'desc';

        if (isset($columns[$orderColumnIndex])) {
            $query->orderBy($columns[$orderColumnIndex], $orderDir);
        }

        /** 📄 Pagination */
        $services = $query
            ->skip($request->start ?? 0)
            ->take($request->length ?? 10)
            ->get()
            ->map(fn($service) => [
                'id' => $service->id,
                'service_code' => $service->service_code,
                'name' => $service->name,
                'category' => $service->category?->name,
                'section' => $service->section?->name,
                'price' => number_format($service->price, 0),
                'status' => $service->status,
                'created_at' => $service->created_at->format('Y-m-d'),
            ]);

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $services
        ]);
    }

    /**
     * Store service
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|max:255',
                'category_id' => 'required|exists:categories,id',
                'section_id' => 'required|exists:sections,id',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:1000',
                'status' => 'required|in:Active,Inactive'
            ]);

            // Standardize name: trim and capitalize words
            $data['name'] = ucwords(strtolower(trim($data['name'])));

            // 1. Generate the initial service code (e.g., Alice Blackwell -> AL-BL)
            $words = explode(' ', $data['name']);
            $parts = [];

            foreach ($words as $word) {
                if (mb_strlen($word) > 0) {
                    // Take first 2 letters of each word and uppercase them
                    $parts[] = mb_strtoupper(mb_substr($word, 0, 2));
                }
            }

            // Join parts with a hyphen
            $originalCode = implode('-', $parts);
            $serviceCode = $originalCode;

            // 2. Handle uniqueness by appending a counter if the code exists
            $counter = 1;
            while (Service::where('service_code', $serviceCode)->exists()) {
                // If AL-BL exists, it becomes AL-BL1, then AL-BL2, etc.
                $serviceCode = $originalCode . $counter;
                $counter++;
            }

            $data['service_code'] = $serviceCode;

            // 3. Create the service
            $service = Service::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Service created successfully',
                'data' => $service->load('category', 'section')
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log exception for debugging
            Log::error('Service creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create service. Please try again later.'
            ], 500);
        }
    }


    /**
     * Show service
     */
    public function show($id)
    {
        $service = Service::with(['category', 'section'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }

    /**
     * Update service
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $data = $request->validate([
            'service_code' => 'required|max:50|unique:services,service_code,' . $id,
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'section_id' => 'required|exists:sections,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable',
            'status' => 'required|in:Active,Inactive'
        ]);

        $service->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
            'data' => $service->load('category', 'section')
        ]);
    }

    /**
     * Delete service
     */
    public function destroy($id)
    {
        Service::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully'
        ]);
    }

    /**
     * Dropdown helpers
     */
    public function meta()
    {
        return response()->json([
            'categories' => Category::select('id', 'name')->orderBy('name')->get(),
            'sections' => Section::select('id', 'name')->orderBy('name')->get()
        ]);
    }
}
