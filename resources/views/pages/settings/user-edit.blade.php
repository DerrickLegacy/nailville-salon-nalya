<x-app-layout class="bg-gray-50">
    <div class="px-4 py-6 max-w-full mx-auto">


        @if (session('error'))
            Service Offered
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 fade-in" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                            <a href="" class="text-gray-500 hover:text-blue-600">Profile</a>
                        </li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-2">›</span>
                            <span class="text-gray-700 font-medium">Edit</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900">Edit {{ $employee->first_name }} {{ $employee->last_name }}
                </h1>
            </div>

        </div>

        <!-- Edit Form -->
        <form id="employeeForm" action="{{ route('settings.update.employer', $employee->employee_id) }}" method="POST"
            enctype="multipart/form-data"
            class="bg-white shadow-xl rounded-xl p-6 w-full max-w-full mx-auto card-hover">
            @csrf
            @method('PUT')

            <div class="flex flex-col md:flex-row md:space-x-8">
                <!-- Profile Picture & Basic Info -->
                <div class="md:w-1/3 mb-6 md:mb-0">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-4">
                            <img id="profileImage"
                                src="{{ $employee->profile_photo_path ? asset('storage/' . $employee->profile_photo_path) : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}"
                                alt="{{ $employee->first_name }}"
                                class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                            <label for="profile_photo"
                                class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full shadow-md cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" id="profile_photo" name="profile_photo" class="hidden"
                                    accept="image/*">
                            </label>
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

                <!-- User Info Form -->
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
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">First Name</label>
                            <input type="text" name="first_name"
                                value="{{ old('first_name', $employee->first_name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Gender</label>
                            <select name="gender"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="Male"
                                    {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female"
                                    {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female
                                </option>
                                <option value="Other"
                                    {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth"
                                value="{{ old('date_of_birth', $employee->date_of_birth) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Marital Status</label>
                            <select name="marital_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="Single"
                                    {{ old('marital_status', $employee->marital_status) == 'Single' ? 'selected' : '' }}>
                                    Single</option>
                                <option value="Married"
                                    {{ old('marital_status', $employee->marital_status) == 'Married' ? 'selected' : '' }}>
                                    Married</option>
                                <option value="Divorced"
                                    {{ old('marital_status', $employee->marital_status) == 'Divorced' ? 'selected' : '' }}>
                                    Divorced</option>
                                <option value="Widowed"
                                    {{ old('marital_status', $employee->marital_status) == 'Widowed' ? 'selected' : '' }}>
                                    Widowed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">National ID Number</label>
                            <input type="text" name="national_id_number"
                                value="{{ old('national_id_number', $employee->national_id_number) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
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
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Job Title</label>
                            <input type="text" name="job_title"
                                value="{{ old('job_title', $employee->job_title) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Department</label>
                            <input type="text" name="department"
                                value="{{ old('department', $employee->department) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Employment Type</label>
                            <select name="employment_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="Full-Time"
                                    {{ old('employment_type', $employee->employment_type) == 'Full-Time' ? 'selected' : '' }}>
                                    Full-Time</option>
                                <option value="Part-Time"
                                    {{ old('employment_type', $employee->employment_type) == 'Part-Time' ? 'selected' : '' }}>
                                    Part-Time</option>
                                <option value="Contract"
                                    {{ old('employment_type', $employee->employment_type) == 'Contract' ? 'selected' : '' }}>
                                    Contract</option>
                                <option value="Intern"
                                    {{ old('employment_type', $employee->employment_type) == 'Intern' ? 'selected' : '' }}>
                                    Intern</option>

                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Hire Date</label>
                            <input type="date" name="hire_date"
                                value="{{ old('hire_date', $employee->hire_date) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Salary</label>
                            <input type="number" name="salary" value="{{ old('salary', $employee->salary) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Employee Code</label>
                            <input type="text" name="employee_code"
                                value="{{ old('employee_code', $employee->employee_code) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Contract End Date</label>
                            <input type="date" name="contract_end_date"
                                value="{{ old('contract_end_date', $employee->contract_end_date) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
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
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $employee->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Phone</label>
                            <input type="tel" name="phone_number"
                                value="{{ old('phone_number', $employee->phone_number) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Address</label>
                            <input type="text" name="address" value="{{ old('address', $employee->address) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">City</label>
                            <input type="text" name="city" value="{{ old('city', $employee->city) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Country</label>
                            <input type="text" name="country" value="{{ old('country', $employee->country) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Bank Account Number</label>
                            <input type="text" name="bank_account_number"
                                value="{{ old('bank_account_number', $employee->bank_account_number) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Bank Name</label>
                            <input type="text" name="bank_name"
                                value="{{ old('bank_name', $employee->bank_name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
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
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Work Status</label>
                            <select name="work_status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="Active"
                                    {{ old('work_status', $employee->work_status) == 'Active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="On Leave"
                                    {{ old('work_status', $employee->work_status) == 'On Leave' ? 'selected' : '' }}>On
                                    Leave</option>
                                <option value="Terminated"
                                    {{ old('work_status', $employee->work_status) == 'Terminated' ? 'selected' : '' }}>
                                    Terminated</option>
                                <option value="Retired"
                                    {{ old('work_status', $employee->work_status) == 'Retired' ? 'selected' : '' }}>
                                    Retired</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Shift</label>
                            <input type="text" name="shift" value="{{ old('shift', $employee->shift) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Work Location</label>
                            <input type="text" name="work_location"
                                value="{{ old('work_location', $employee->work_location) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">NSSF Number</label>
                            <input type="text" name="nssf_number"
                                value="{{ old('nssf_number', $employee->nssf_number) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">TIN Number</label>
                            <input type="text" name="tin_number"
                                value="{{ old('tin_number', $employee->tin_number) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Emergency Contact Name</label>
                            <input type="text" name="emergency_contact_name"
                                value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Emergency Contact Phone</label>
                            <input type="text" name="emergency_contact_phone"
                                value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-medium mb-1">Emergency Contact
                                Relation</label>
                            <input type="text" name="emergency_contact_relation"
                                value="{{ old('emergency_contact_relation', $employee->emergency_contact_relation) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href=""
                    class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg shadow-sm flex items-center space-x-2 transition-all duration-300 hover:shadow-md">
                    <span>Cancel</span>
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm flex items-center space-x-2 transition-all duration-300 hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        // Preview uploaded profile image
        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profileImage').src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Add fade-in animation to form elements
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('input, select');
            formElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.05}s`;
                element.classList.add('fade-in');
            });
        });
    </script>

    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        input:focus,
        select:focus {
            outline: none;
            ring: 2px;
            ring-color: rgba(59, 130, 246, 0.5);
            border-color: #3b82f6;
        }
    </style>
</x-app-layout>
