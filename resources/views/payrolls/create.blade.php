<x-app-layout>
    <div class="px-2 sm:px-4 lg:px-8 py-4 sm:py-3 lg:py-4 w-full max-w-9xl mx-auto">
        <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:justify-between sm:items-start">
            <div class="flex-1">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-1 sm:space-x-2 text-xs sm:text-sm">
                        <li><a href="{{ route('payrolls.index') }}" class="text-gray-500 hover:text-purple-600 truncate">Payroll</a></li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-1 sm:mx-2">›</span>
                            <span class="text-gray-500 truncate">Create</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 dark:text-gray-100">
                    Create New Payroll
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Generate payroll for an employee.
                </p>
            </div>
        </div>
    </div>

    <div class="px-2 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8 w-full max-w-3xl mx-auto">
        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg sm:rounded-xl p-6">
            <form action="{{ route('payrolls.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee</label>
                    <select name="employee_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <option value="">Select Employee</option>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->employee_id }}" {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payroll Month</label>
                    <input type="month" name="payroll_month" required
                        value="{{ old('payroll_month', now()->format('Y-m')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('payrolls.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Create Payroll
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>