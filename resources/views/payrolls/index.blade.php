<x-app-layout>
    <div class="px-2 sm:px-4 lg:px-8 py-4 sm:py-3 lg:py-4 w-full max-w-9xl mx-auto">
        <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:justify-between sm:items-start">
            <div class="flex-1">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-1 sm:space-x-2 text-xs sm:text-sm">
                        <li><a href="#" class="text-gray-500 hover:text-purple-600 truncate">Payroll</a></li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-1 sm:mx-2">›</span>
                            <span class="text-gray-500 truncate">List</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 dark:text-gray-100">
                    Payroll Runs
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Manage your salon's employee payroll.
                </p>
            </div>
        </div>
    </div>

    <div class="px-2 lg:px-8 w-full max-w-9xl flex justify-end">
        <a href="{{ route('payrolls.create') }}"
            class="w-auto px-4 py-2 bg-purple-700 text-white text-sm font-medium rounded-lg hover:bg-purple-800 dark:bg-purple-600 dark:hover:bg-purple-700 transition-colors duration-200 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span>Create Payroll</span>
        </a>
    </div>

    <div class="px-2 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8 w-full max-w-9xl mx-auto">
        <!-- Filter Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg sm:rounded-xl p-4 mb-6">
            <form action="{{ route('payrolls.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee</label>
                    <select name="employee_id" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <option value="">All Employees</option>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->employee_id }}" {{ request('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payroll Month</label>
                    <input type="month" name="payroll_month" value="{{ request('payroll_month') }}" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="reversed" {{ request('status') == 'reversed' ? 'selected' : '' }}>Reversed</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-all">Filter</button>
                    <a href="{{ route('payrolls.index') }}" class="px-4 py-2 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-all">Clear</a>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg sm:rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Employee</th>
                            <th class="px-4 py-3">Payroll Month</th>
                            <th class="px-4 py-3">Total Sales</th>
                            <th class="px-4 py-3">Gross Salary</th>
                            <th class="px-4 py-3">Net Salary</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @foreach ($payrolls as $payroll)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('settings.employee.details', $payroll->employee->employee_id) }}" class="text-purple-600 hover:text-purple-900">
                                    {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($payroll->payroll_month)->format('F Y') }}</td>
                            <td class="px-4 py-3">{{ number_format($payroll->total_sales, 0) }}</td>
                            <td class="px-4 py-3">{{ number_format($payroll->gross_salary, 0) }}</td>
                            <td class="px-4 py-3">{{ number_format($payroll->net_salary, 0) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $payroll->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $payroll->status === 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $payroll->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $payroll->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $payroll->status === 'cancelled' || $payroll->status === 'reversed' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucwords(str_replace('_', ' ', $payroll->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('payrolls.show', $payroll) }}" class="text-purple-600 hover:text-purple-900 mr-3">View</a>
                                <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this payroll? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700">
                {{ $payrolls->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>