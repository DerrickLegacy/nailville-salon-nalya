<x-app-layout class="bg-gray-50">
    <div class="px-4 py-6 max-w-full mx-auto">

        <!-- Page Header -->
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center fade-in">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li><a href="#" class="text-gray-500 hover:text-blue-600">Settings</a></li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-2">›</span>
                            <a href="{{ route('settings.management') }}" class="text-gray-500 hover:text-blue-600">User
                                Management</a>
                        </li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-2">›</span>
                            <span class="text-gray-700 font-medium">Profile</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</h1>
            </div>
            <div class="flex space-x-3 mt-4 md:mt-0 fade-in">
                <a href="{{ route('settings.edit.employer', $employee->employee_id) }}"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm flex items-center space-x-2 transition-all duration-300 hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5.414-7.414a2 2 0 112.828 2.828L11 16H7v-4l6.586-6.586z" />
                    </svg>
                    <span>Edit Profile</span>
                </a>
                <button onclick="deleteEmployee({{ $employee->employee_id }})"
                    class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg shadow-sm flex items-center space-x-2 transition-all duration-300 hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Delete</span>
                </button>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="bg-white shadow-xl rounded-xl p-6 w-full max-w-full mx-auto card-hover">

            <div class="flex flex-col md:flex-row md:space-x-8">

                <!-- Profile Picture & Basic Info -->
                <div class="md:w-1/3 mb-6 md:mb-0">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-4">
                            <img src="{{ $employee->profile_photo_path ? asset('storage/' . $employee->profile_photo_path) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}"
                                alt="{{ $employee->first_name }}"
                                class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                        </div>

                        <div class="text-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-800">{{ $employee->first_name }}
                                {{ $employee->last_name }}</h2>
                            <p class="text-blue-600 font-medium">{{ $employee->job_title ?? 'No job title' }}</p>
                            <p class="text-gray-500 text-sm mt-1">{{ $employee->department ?? 'No department' }}</p>
                        </div>

                        <!-- Status Badges -->
                        <div class="flex flex-wrap justify-center gap-2 w-full mb-6">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                {{ $employee->employment_type }}
                            </span>
                            <span
                                class="px-3 py-1 {{ $employee->work_status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} text-xs font-medium rounded-full">
                                {{ $employee->work_status }}
                            </span>
                        </div>

                        <!-- Quick Stats -->
                        <div class="bg-gray-50 rounded-lg p-4 w-full">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div>
                                    <p class="text-xs text-gray-500">Employee ID</p>
                                    <p class="text-sm font-semibold">{{ $employee->employee_id }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Hire Date</p>
                                    <p class="text-sm font-semibold">
                                        {{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('M d, Y') : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="md:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Basic Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Basic Info
                        </h3>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Full Name</label>
                            <p class="text-sm text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}
                            </p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Gender</label>
                            <p class="text-sm text-gray-900">{{ $employee->gender }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Date of Birth</label>
                            <p class="text-sm text-gray-900">
                                {{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('M d, Y') : '-' }}
                            </p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Marital Status</label>
                            <p class="text-sm text-gray-900">{{ $employee->marital_status }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">National ID Number</label>
                            <p class="text-sm text-gray-900">{{ $employee->national_id_number ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Work Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Work Info
                        </h3>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Job Title</label>
                            <p class="text-sm text-gray-900">{{ $employee->job_title ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Department</label>
                            <p class="text-sm text-gray-900">{{ $employee->department ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Employment Type</label>
                            <p class="text-sm text-gray-900">{{ $employee->employment_type }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Employee Code</label>
                            <p class="text-sm text-gray-900">{{ $employee->employee_code ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Hire Date</label>
                            <p class="text-sm text-gray-900">
                                {{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('M d, Y') : '-' }}
                            </p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Contract End Date</label>
                            <p class="text-sm text-gray-900">
                                {{ $employee->contract_end_date ? \Carbon\Carbon::parse($employee->contract_end_date)->format('M d, Y') : '-' }}
                            </p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Salary</label>
                            <p class="text-sm text-gray-900">
                                {{ $employee->salary ? 'Shs.' . number_format($employee->salary) : '-' }}</p>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Contact Info
                        </h3>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Email</label>
                            <p class="text-sm text-gray-900">{{ $employee->email ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Phone</label>
                            <p class="text-sm text-gray-900">{{ $employee->phone_number ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Address</label>
                            <p class="text-sm text-gray-900">{{ $employee->address ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">City</label>
                            <p class="text-sm text-gray-900">{{ $employee->city ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Country</label>
                            <p class="text-sm text-gray-900">{{ $employee->country ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Bank Account Number</label>
                            <p class="text-sm text-gray-900">{{ $employee->bank_account_number ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Bank Name</label>
                            <p class="text-sm text-gray-900">{{ $employee->bank_name ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Other Info -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Other Info
                        </h3>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Work Status</label>
                            <p class="text-sm text-gray-900">{{ $employee->work_status }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Shift</label>
                            <p class="text-sm text-gray-900">{{ $employee->shift ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Work Location</label>
                            <p class="text-sm text-gray-900">{{ $employee->work_location ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">NSSF Number</label>
                            <p class="text-sm text-gray-900">{{ $employee->nssf_number ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">TIN Number</label>
                            <p class="text-sm text-gray-900">{{ $employee->tin_number ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Emergency Contact Name</label>
                            <p class="text-sm text-gray-900">{{ $employee->emergency_contact_name ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Emergency Contact Phone</label>
                            <p class="text-sm text-gray-900">{{ $employee->emergency_contact_phone ?? '-' }}</p>
                        </div>
                        <div class="info-group p-3 rounded-lg">
                            <label class="block text-xs text-gray-500 font-medium">Emergency Contact Relation</label>
                            <p class="text-sm text-gray-900">{{ $employee->emergency_contact_relation ?? '-' }}</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Timestamps -->
            <div
                class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between text-xs text-gray-500">
                <p>Created at: {{ \Carbon\Carbon::parse($employee->created_at)->format('M d, Y H:i') }}</p>
                <p class="mt-2 sm:mt-0">Updated at:
                    {{ \Carbon\Carbon::parse($employee->updated_at)->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
