<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  \Illuminate\Http\RedirectResponse;
use App\Models\Employee;
use App\Models\ApplicationConfigurationSetting;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function index()
    {
        return view('pages.settings.user-management');
    }

    /**
     * Return a list of all employees for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList()
    {
        $employees = Employee::all(); // Fetch all employees

        return response()->json([
            'status' => 'success',
            'data' => $employees
        ]);
    }

    /**
     * Show details of a single employee.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function viewEditEmployerDetails($id, Request $request)
    {
        $employee = Employee::findOrFail($id);
        $pageType = $request->route('pageType');
        if ($pageType && $pageType === 'edit') {
            return view('pages.settings.user-edit', [
                'employee' => $employee
            ]);
        } else {

            return view('pages.settings.user-details', [
                'employee' => $employee
            ]);
        }
    }

    /**
     * Show edit form for a single employee.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEmployee(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        try {
            // Validate the incoming request data
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'gender' => 'required|in:Male,Female,Other',
                'date_of_birth' => 'nullable|date',
                'national_id_number' => 'nullable|string|max:50',
                'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
                'email' => 'nullable|email|max:150',
                'phone_number' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'employee_code' => 'nullable|string|max:50',
                'job_title' => 'nullable|string|max:100',
                'department' => 'nullable|string|max:100',
                'employment_type' => 'nullable|in:Full-Time,Part-Time,Contract,Intern',
                'hire_date' => 'nullable|date',
                'contract_end_date' => 'nullable|date',
                'salary' => 'nullable|numeric',
                'bank_account_number' => 'nullable|string|max:50',
                'bank_name' => 'nullable|string|max:100',
                'tin_number' => 'nullable|string|max:50',
                'nssf_number' => 'nullable|string|max:50',
                'work_status' => 'nullable|in:Active,On Leave,Terminated,Resigned',
                'shift' => 'nullable|string|max:50',
                'work_location' => 'nullable|string|max:150',
                'emergency_contact_name' => 'nullable|string|max:150',
                'emergency_contact_phone' => 'nullable|string|max:20',
                'emergency_contact_relation' => 'nullable|string|max:50',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $validated['profile_photo_path'] = $path;
            }

            // Update employee
            $employee->update($validated);

            return redirect()
                ->route('settings.employee.details', $employee->employee_id)
                ->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating employee: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()
                ->with('error', 'Error updating employee: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Delete a single employee.
     *
     * @param int $id
     */
    public function deleteEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->back()->with('success', 'Employee deleted successfully!');
    }


    public function createNewEmployee()
    {
        return view('pages.settings.user-add');
    }


    /**
     * Store a newly created employee in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */



    public function storeNewEmployee(Request $request)
    {
        try {
            // ✅ Validate the incoming request data
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'gender' => 'required|in:Male,Female,Other',
                'date_of_birth' => 'nullable|date',
                'national_id_number' => 'nullable|string|max:50|unique:employees,national_id_number',
                'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
                'email' => 'nullable|email|max:150|unique:employees,email',
                'phone_number' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'job_title' => 'nullable|string|max:100',
                'department' => 'nullable|string|max:100',
                'employment_type' => 'nullable|in:Full-Time,Part-Time,Contract,Intern',
                'hire_date' => 'nullable|date',
                'contract_end_date' => 'nullable|date',
                'salary' => 'nullable|numeric',
                'bank_account_number' => 'nullable|string|max:50',
                'bank_name' => 'nullable|string|max:100',
                'tin_number' => 'nullable|string|max:50',
                'nssf_number' => 'nullable|string|max:50',
                'work_status' => 'nullable|in:Active,On Leave,Terminated,Resigned',
                'shift' => 'nullable|string|max:50',
                'work_location' => 'nullable|string|max:150',
                'emergency_contact_name' => 'nullable|string|max:150',
                'emergency_contact_phone' => 'nullable|string|max:20',
                'emergency_contact_relation' => 'nullable|string|max:50',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                // ✅ Friendly uniqueness error messages
                'email.unique' => 'This email address is already registered.',
                'national_id_number.unique' => 'This National ID number is already registered.',
            ]);

            // ✅ Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $validated['profile_photo_path'] = $path;
            }

            // ✅ Step 1: Create employee without code
            $employee = Employee::create($validated);

            // ✅ Step 2: Assign employee code based on ID (e.g., NV-18)
            if (empty($employee->employee_code)) {
                $employee->employee_code = $this->generateEmployeeCode($employee->employee_id);
                $employee->save();
            }

            return redirect()
                ->route('settings.employee.details', $employee->employee_id)
                ->with('success', 'Employee created successfully!');
        } catch (QueryException $e) {
            Log::error('Database error while creating employee: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'We could not save the employee because some details already exist in the system.')
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error while creating employee: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while creating the employee. Please try again later.')
                ->withInput();
        }
    }

    /**
     * Generate employee code based on employee_id
     *
     * Example: NV-18 if employee_id = 18
     */
    private function generateEmployeeCode($employeeId)
    {
        return 'NV-' . $employeeId;
    }

    /**
     * configSettings
     */
    public function configSettings()
    {
        return view('pages.settings.app-configurations');
    }

    /**
     * Fetch all application settings (for AJAX).
     */
    public function fetchSettings(Request $request)
    {
        $settings = ApplicationConfigurationSetting::select('id', 'title', 'key', 'value', 'type', 'description', 'created_at', 'updated_at')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }



    /**
     * Store a new app setting
     */
    public function storeConfigurations(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'value'       => 'required|string',
            'type'        => 'nullable|in:string,integer,decimal,boolean,json',
        ]);

        // dd($validated);

        // auto-generate key from title
        $validated['key'] = Str::slug($request->title, '_');

        $setting = ApplicationConfigurationSetting::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Settings Item created successfully.!');
    }

    /**
     * Update an existing app setting
     */
    public function updateConfigurations(Request $request, $id)
    {
        $setting = ApplicationConfigurationSetting::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'value'       => 'required|string',
            'type'        => 'nullable|in:string,integer,decimal,boolean,json',
        ]);

        // auto-generate key again
        $validated['key'] = Str::slug($request->title, '_');

        $setting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Configuration updated successfully.',
            'data'    => $setting,
        ]);
    }

    /**
     * Delete a configuration
     */
    public function destroyConfigurations($id)
    {
        $setting = ApplicationConfigurationSetting::findOrFail($id);
        $setting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Configuration deleted successfully.'
        ]);
    }
}
