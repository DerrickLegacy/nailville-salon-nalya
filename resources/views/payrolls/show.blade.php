<x-app-layout>
    <div class="px-2 sm:px-4 lg:px-8 py-4 sm:py-3 lg:py-4 w-full max-w-9xl mx-auto">
        <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:justify-between sm:items-start">
            <div class="flex-1">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-1 sm:space-x-2 text-xs sm:text-sm">
                        <li><a href="{{ route('payrolls.index') }}" class="text-gray-500 hover:text-purple-600 truncate">Payroll</a></li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-1 sm:mx-2">›</span>
                            <span class="text-gray-500 truncate">Details</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 dark:text-gray-100">
                    Payroll Details
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    View payroll run details for {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}.
                </p>
            </div>
        </div>
    </div>

    <div class="px-2 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8 w-full max-w-9xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Payroll Summary Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg sm:rounded-xl p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Employee</label>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Payroll Month</label>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($payroll->payroll_month)->format('F Y') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Payroll Type</label>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ ucfirst($payroll->payroll_type) }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Payment Date</label>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $payroll->payment_date ? \Carbon\Carbon::parse($payroll->payment_date)->format('M d, Y') : 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Status</label>

                            <p class="text-lg font-semibold text-gray-800 dark:text-white {{ $payroll->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $payroll->status === 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $payroll->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $payroll->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $payroll->status === 'cancelled' || $payroll->status === 'reversed' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucwords(str_replace('_', ' ', $payroll->status)) }}

                            </p>

                        </div>
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Total Sales (Collections)</label>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ number_format($payroll->total_sales, 0) }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Commission Rate</label>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ number_format($payroll->commission_rate, 0) }}%</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Gross Salary</label>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ number_format($payroll->gross_salary, 0) }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-400">Total Deductions</label>
                            <p class="text-lg font-semibold text-red-600">{{ number_format($payroll->total_deductions, 0) }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm text-gray-500 dark:text-gray-400">Net Salary</label>
                            <p class="text-2xl font-bold text-purple-600">{{ number_format($payroll->net_salary, 0) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Deductions Section -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg sm:rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Deductions</h3>
                        @if(in_array($payroll->status, ['draft', 'pending_approval']))
                        <button id="addDeductionBtn"
                            class="px-3 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Deduction
                        </button>
                        @endif
                    </div>

                    <!-- Add Deduction Form -->
                    @if(in_array($payroll->status, ['draft', 'pending_approval']))
                    <div id="addDeductionForm" class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hidden">
                        <form action="{{ route('payrolls.deductions.store', $payroll) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deduction Name</label>
                                    <input type="text" name="deduction_name" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount</label>
                                    <input type="number" name="amount" required min="0" step="0.01"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason</label>
                                <input type="text" name="reason"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                                <textarea name="notes" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100"></textarea>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" id="cancelDeductionBtn"
                                    class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    Add Deduction
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                    <!-- Deductions List -->
                    <div class="overflow-x-auto">
                        @if($payroll->deductions->count() > 0)
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Amount</th>
                                    <th class="px-4 py-3">Reason</th>
                                    <th class="px-4 py-3">Entered By</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach($payroll->deductions as $deduction)
                                <tr>
                                    <td class="px-4 py-3">{{ $deduction->deduction_name }}</td>
                                    <td class="px-4 py-3 text-red-600 font-semibold">{{ number_format($deduction->amount, 0) }}</td>
                                    <td class="px-4 py-3">{{ $deduction->reason ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $deduction->enteredBy->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        @if(in_array($payroll->status, ['draft', 'pending_approval']))
                                        <form action="{{ route('payrolls.deductions.destroy', [$payroll, $deduction]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this deduction?')"
                                                class="text-red-600 hover:text-red-800 text-sm">
                                                Delete
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No deductions added yet.</p>
                        @endif
                    </div>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Actions</h3>
                    <div class="flex flex-col sm:flex-row gap-4 items-end justify-end">
                        <!-- Back to List -->
                        <div class="flex-shrink-0">
                            <a href="{{ route('payrolls.index') }}"
                                class="px-5 py-2.5 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-all flex items-center justify-center">
                                Back to List
                            </a>
                        </div>

                        <!-- Recalculate Button -->
                        @if(in_array($payroll->status, ['draft', 'pending_approval']))
                        <div class="flex-shrink-0">
                            <form action="{{ route('payrolls.recalculate', $payroll) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-all flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Recalculate Payroll
                                </button>
                            </form>
                        </div>
                        @endif

                        <!-- Status Update Form -->
                        <form action="{{ route('payrolls.update-status', $payroll) }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                            @csrf
                            @method('PUT')

                            <!-- Payment Date -->
                            <div class="flex-shrink-0 w-full sm:w-40">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Payment Date</label>
                                <input type="date" name="payment_date"
                                    value="{{ old('payment_date', $payroll->payment_date ? \Carbon\Carbon::parse($payroll->payment_date)->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 transition-all">
                            </div>

                            <!-- Status -->
                            <div class="flex-shrink-0 w-full sm:w-40">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status</label>
                                <select name="status" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 transition-all">
                                    <option value="draft" {{ $payroll->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending_approval" {{ $payroll->status === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                                    <option value="approved" {{ $payroll->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="paid" {{ $payroll->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ $payroll->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="reversed" {{ $payroll->status === 'reversed' ? 'selected' : '' }}>Reversed</option>
                                </select>
                            </div>

                            <!-- Save Button -->
                            <div class="flex-shrink-0">
                                <button type="submit"
                                    class="px-6 py-2.5 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column (1/3 width) -->
            <div class="lg:col-span-1">
                <!-- Payroll Timeline Section -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg sm:rounded-xl p-6 sticky top-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Payroll Timeline</h3>

                    @if($payroll->auditLogs->count() > 0)
                    <div class="space-y-4">
                        @foreach($payroll->auditLogs->sortByDesc('created_at') as $log)
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($log->action === 'created')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    @elseif($log->action === 'status_changed')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    @elseif($log->action === 'deduction_added' || $log->action === 'deduction_deleted')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @elseif($log->action === 'recalculated')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @endif
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                                        @if($log->action === 'created')
                                        Payroll created
                                        @elseif($log->action === 'status_changed')
                                        Status changed from {{ ucwords(str_replace('_', ' ', $log->old_value)) }} to {{ ucwords(str_replace('_', ' ', $log->new_value)) }}
                                        @elseif($log->action === 'deduction_added')
                                        Deduction added
                                        @elseif($log->action === 'deduction_deleted')
                                        Deduction removed
                                        @elseif($log->action === 'recalculated')
                                        Payroll recalculated
                                        @else
                                        {{ ucwords(str_replace('_', ' ', $log->action)) }}
                                        @endif
                                    </p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $log->created_at->format('M d, Y H:i') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    By {{ $log->performedBy->name ?? 'Unknown User' }}
                                    @if($log->action === 'recalculated' && $log->old_value && $log->new_value)
                                    @php
                                    $old = json_decode($log->old_value, true);
                                    $new = json_decode($log->new_value, true);
                                    @endphp
                                    <br>
                                    Old: Sales {{ number_format($old['total_sales'] ?? 0) }}, Gross {{ number_format($old['gross_salary'] ?? 0) }}, Net {{ number_format($old['net_salary'] ?? 0) }}<br>
                                    New: Sales {{ number_format($new['total_sales'] ?? 0) }}, Gross {{ number_format($new['gross_salary'] ?? 0) }}, Net {{ number_format($new['net_salary'] ?? 0) }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No timeline entries yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('addDeductionBtn');
            const form = document.getElementById('addDeductionForm');
            const cancelBtn = document.getElementById('cancelDeductionBtn');

            if (addBtn && form) {
                addBtn.addEventListener('click', function() {
                    form.classList.remove('hidden');
                });

                cancelBtn.addEventListener('click', function() {
                    form.classList.add('hidden');
                });
            }
        });
    </script>
</x-app-layout>