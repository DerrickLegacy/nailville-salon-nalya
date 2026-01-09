<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        try {
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
                'name',
                'category_id',
                'section_id',
                'price',
                'status',
                'created_at'
            ];

            $orderColumnIndex = $request->order[0]['column'] ?? 0;
            $orderDir = $request->order[0]['dir'] ?? 'desc';

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDir);
            } else {
                $query->orderBy('name', 'asc');
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
                    'category' => $service->category?->name ?? 'N/A',
                    'section' => $service->section?->name ?? 'N/A',
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
        } catch (\Exception $e) {
            Log::error('Services list error: ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load services data'
            ], 500);
        }
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

            // Generate service code
            $data['service_code'] = $this->generateServiceCode($data['name']);

            // Create the service
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
        try {
            $service = Service::with(['category', 'section'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $service
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }
    }

    /**
     * Update service
     */
    public function update(Request $request, $id)
    {
        try {
            $service = Service::findOrFail($id);




            $data = $request->validate([
                'name' => 'required|max:255',
                'category_id' => 'required|exists:categories,id',
                'section_id' => 'required|exists:sections,id',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable',
                'status' => 'required|in:Active,Inactive'
            ]);

            // Standardize name: trim and capitalize words
            $data['name'] = ucwords(strtolower(trim($data['name'])));

            $service_code = $this->generateServiceCode($data['name']). $id;

            $service->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Service updated successfully',
                'data' => $service->load('category', 'section')
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Service update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update service. Please try again later.'
            ], 500);
        }
    }

    /**
     * Delete service
     */
    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);

            // Check if service is used in transactions
            if ($service->transactions()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete service as it has associated transactions'
                ], 422);
            }

            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'Service deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Service deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete service'
            ], 500);
        }
    }

    /**
     * Get categories and sections for dropdowns
     */
    public function meta()
    {
        try {
            return response()->json([
                'categories' => Category::select('id', 'name')->orderBy('name')->get(),
                'sections' => Section::select('id', 'name')->orderBy('name')->get()
            ]);
        } catch (\Exception $e) {
            Log::error('Meta data fetch failed: ' . $e->getMessage());
            return response()->json([
                'categories' => [],
                'sections' => []
            ], 500);
        }
    }

    /**
     * Section Management Methods
     */
    public function getSections()
    {
        try {
            $sections = Section::withCount('services')->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            Log::error('Sections fetch failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sections'
            ], 500);
        }
    }

    public function storeSection(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|max:255|unique:sections,name',
                'description' => 'nullable|string|max:1000'
            ]);

            $section = Section::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Section created successfully',
                'data' => $section
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Section creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create section'
            ], 500);
        }
    }

    public function updateSection(Request $request, $id)
    {
        try {
            $section = Section::findOrFail($id);

            $data = $request->validate([
                'name' => 'required|max:255|unique:sections,name,' . $id,
                'description' => 'nullable|string|max:1000'
            ]);

            $section->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully',
                'data' => $section
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Section update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update section'
            ], 500);
        }
    }

    public function destroySection($id)
    {
        try {
            $section = Section::findOrFail($id);

            // Check if section is used by services
            if ($section->services()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete section as it has associated services'
                ], 422);
            }

            $section->delete();

            return response()->json([
                'success' => true,
                'message' => 'Section deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Section deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete section'
            ], 500);
        }
    }

    /**
     * Category Management Methods
     */
    public function getCategories()
    {
        try {
            $categories = Category::orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Categories fetch failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories'
            ], 500);
        }
    }

    public function storeCategory(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|max:255|unique:categories,name',
                'description' => 'nullable|string|max:1000'
            ]);

            $category = Category::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Category creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category'
            ], 500);
        }
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $data = $request->validate([
                'name' => 'required|max:255|unique:categories,name,' . $id,
                'description' => 'nullable|string|max:1000'
            ]);

            $category->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Category update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category'
            ], 500);
        }
    }

    public function destroyCategory($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Check if category is used by services
            if ($category->services()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category as it has associated services'
                ], 422);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Category deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category'
            ], 500);
        }
    }

    /**
     * Generate unique service code
     */
    private function generateServiceCode($name)
    {
        $words = explode(' ', $name);
        $parts = [];

        foreach ($words as $word) {
            if (mb_strlen($word) > 0) {
                $parts[] = mb_strtoupper(mb_substr($word, 0, 2));
            }
        }

        $originalCode = implode('-', $parts);
        $serviceCode = $originalCode;

        $counter = 1;
        while (Service::where('service_code', $serviceCode)->exists()) {
            $serviceCode = $originalCode . $counter;
            $counter++;
        }

        return $serviceCode;
    }
}
