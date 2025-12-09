<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Ensure only admins can access these methods
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            /** @var User|null $user */
            $user = Auth::user();
            if (!$user || !$user->isAdmin()) {
                abort(403, "Unauthorized action. You need admin rights.");
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of system users
     */
    public function index()
    {
        return view('pages.settings.system-users');
    }

    /**
     * Get system users list for DataTables
     */
    public function list()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $users]);
    }

    /**
     * Show the form for creating a new system user
     */
    public function create()
    {
        return view('pages.settings.system-user-add');
    }

    /**
     * Store a newly created system user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'is_admin' => ['required', 'boolean', 'in:0,1']
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $adminValue = $request->input('is_admin', 0);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'admin' => $adminValue,
            'activity' => 'Active',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'System user created successfully!');
    }

    /**
     * Show the form for editing a system user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.settings.system-user-edit', compact('user'));
    }

    /**
     * Update the specified system user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
            'admin' => ['required', 'boolean', 'in:0,1']
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->admin = $request->admin;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'System user updated successfully!');
    }

    /**
     * Toggle user active/inactive status
     */
    public function toggleStatus($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth::user();

        // Prevent deactivating yourself
        if ($currentUser && $targetUser->id === $currentUser->id) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account!');
        }

        $targetUser->activity = $targetUser->activity === 'Active' ? 'Inactive' : 'Active';
        $targetUser->save();

        $status = $targetUser->activity === 'Active' ? 'activated' : 'deactivated';
        return redirect()->route('admin.users.index')
            ->with('success', "User has been {$status} successfully!");
    }

    /**
     * Remove the specified system user
     */
    public function destroy($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth::user();

        // Prevent deleting yourself
        if ($currentUser && $targetUser->id === $currentUser->id) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account!');
        }

        // $targetUser->delete();
        $targetUser->was_deleted = false;
        $targetUser->save();


        return redirect()->route('admin.users.index')
            ->with('success', 'System user deleted successfully!');
    }
}
