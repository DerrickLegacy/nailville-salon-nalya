<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full  mx-auto">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center"><span class="mx-2">›</span>
                    <a href="{{ route('settings.management') }}" class="hover:text-blue-600">User Management</a>
                </li>
                <li class="flex items-center"><span class="mx-2">›</span>
                    <span class="text-gray-400">Add System User</span>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add System User</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Create a new user account with access to the system
                </p>
            </div>
        </div>

        <!-- Info Alert -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        <strong>Note:</strong> System users can log in and access the application. Only create accounts for authorized personnel.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                <div class="space-y-6">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                                      dark:bg-gray-700 dark:text-white"
                               placeholder="e.g., John Doe">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}" 
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                                      dark:bg-gray-700 dark:text-white"
                               placeholder="e.g., john@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                                      dark:bg-gray-700 dark:text-white"
                               placeholder="Minimum 8 characters">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Password must be at least 8 characters long
                        </p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                      focus:ring-2 focus:ring-violet-500 focus:border-violet-500
                                      dark:bg-gray-700 dark:text-white"
                               placeholder="Re-enter password">
                    </div>

                    <!-- Is an administrator? -->
<div>
    <label for="is_admin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Administrator <span class="text-red-500">*</span>
    </label>
    <select 
        name="is_admin" 
        id="is_admin" 
        required
        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
               focus:ring-2 focus:ring-violet-500 focus:border-violet-500
               dark:bg-gray-700 dark:text-white">
        <option value="">Select...</option>
        <option value="0">No</option>
        <option value="1">Yes</option>
    </select>
</div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('settings.management') }}"
                       class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 
                              text-gray-700 dark:text-gray-300 font-medium rounded-lg 
                              transition-colors text-center">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-lg 
                                   shadow-sm transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Create System User</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">What happens next?</h3>
            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    The user will be created with the provided credentials
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    They can log in immediately using their email and password
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    You can manage their account from the user management page
                </li>
            </ul>
        </div>
    </div>
</x-app-layout>
