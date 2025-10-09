<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-full mx-auto">

        <!-- Breadcrumb -->
        <nav class="flex mb-2 text-sm text-gray-500 fade-in" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="" class="hover:text-blue-600">Transactions</a></li>
                <li class="flex items-center"><span class="mx-2">›</span>
                    <a href="" class="hover:text-blue-600">
                        {{ ucfirst($transaction->transaction_type) }}
                    </a>
                </li>
                <li class="flex items-center"><span class="mx-2">›</span>
                    <span class="text-gray-400">Edit</span>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-2 fade-in ">
            <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
                Edit Transaction
            </h1>
            <a href="{{ url()->previous() }}"
                class="px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow hover:opacity-90 transition">
                ← Back to Transactions
            </a>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white shadow-xl rounded-2xl p-6 space-y-6 card-hover mt-5">
            <form x-ref="transactionForm" action="{{ route('transactions.update', $transaction->id) }}" method="POST"
                class="space-y-4">
                @csrf
                @method('PUT')

                <input type="hidden" name="transaction_type" value="{{ $transaction->transaction_type }}">

                <!-- Customer Name -->
                <div>
                    <label for="customer_name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name</label>
                    <input type="text" name="customer_name" id="customer_name"
                        value="{{ $transaction->customer_name ?? '' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                    <!-- Receipt ID -->
                    <div>
                        <label for="receipt_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Receipt ID</label>
                        <input type="text" name="receipt_id" id="receipt_id"
                            value="{{ $transaction->receipt_id ?? '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                    </div>

                    <!-- Transaction Type (disabled) -->
                    <div>
                        <label for="transaction_type_display"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction Type</label>
                        <input name="transaction_type_display" id="transaction_type_display" disabled
                            value="{{ $transaction->transaction_type }}"
                            class="mt-1 block w-full bg-gray-100 rounded-md border-gray-300 shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                    </div>

                    <!-- Service or Expense select -->
                    @if ($transaction->transaction_type === 'Income')
                        <div>
                            <label for="service_offered"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service
                                Offered</label>
                            <select name="service_offered" id="service_offered"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">-- Select Service --</option>
                                @php
                                    $services = [
                                        'HairCut' => 'Hair Cut',
                                        'BraidalPackage' => 'Braidal Package',
                                        'HairStyling' => 'Hair Styling / Braiding',
                                        'HairColoring' => 'Hair Coloring / Treatment',
                                        'ShampooConditioning' => 'Shampoo & Conditioning',
                                        'Nails' => 'Nail Care (Manicure / Pedicure)',
                                        'Facial' => 'Facial / Skin Care',
                                        'Massage' => 'Massage Therapy',
                                        'Waxing' => 'Waxing / Hair Removal',
                                        'Makeup' => 'Makeup Services',
                                        'Packages' => 'Service Packages (Combo Deals)',
                                        'Other' => 'Other',
                                    ];
                                @endphp

                                @foreach ($services as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ $transaction->service_description === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    @else
                        <div>
                            <label for="expense_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expense
                                Category</label>
                            <select name="expense_type" id="expense_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">-- Select Expense --</option>
                                @php
                                    $expenses = [
                                        'Rent' => 'Rent / Lease',
                                        'Salaries' => 'Salaries & Wages',
                                        'Allowances' => 'Allowances / Bonuses',
                                        'Training' => 'Staff Training / Workshops',
                                        'Utilities' => 'Utilities (Electricity, Water, Internet)',
                                        'BeautyProducts' => 'Beauty Products',
                                        'HairSupplies' => 'Hair Supplies',
                                        'NailSupplies' => 'Nail Supplies',
                                        'Cleaning' => 'Cleaning Supplies / Laundry',
                                        'FurnitureEquipment' => 'Furniture & Equipment Purchase',
                                        'Maintenance' => 'Equipment Maintenance & Repairs',
                                        'Marketing' => 'Marketing & Advertising',
                                        'Transport' => 'Transport / Delivery Costs',
                                        'Licenses' => 'Licenses, Permits & Insurance',
                                        'Miscellaneous' => 'Miscellaneous / Other',
                                    ];
                                @endphp

                                @foreach ($expenses as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ $transaction->expense_type === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    @endif

                    <!-- Amount -->
                    <div>
                        <label for="amount"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                        <input type="number" name="amount" id="amount" value="{{ $transaction->amount }}"
                            step="0.01" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                        <select name="payment_method" id="payment_method"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            @foreach (['Cash', 'MobileMoney', 'Card', 'Bank', 'Other'] as $method)
                                <option value="{{ $method }}"
                                    {{ $transaction->payment_method === $method ? 'selected' : '' }}>
                                    {{ $method }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                        <input type="datetime-local" name="date" id="date"
                            value="{{ $transaction->created_at->format('Y-m-d\TH:i') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                    </div>

                    <!-- Employee -->
                    <div>

                        <label for="employee_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Worked on By</label>
                        <select name="employee_id" id="employee_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            <option value="">-- Select Employee --</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->employee_id }}"
                                    {{ $transaction->employee_id == $employee->employee_id ? 'selected' : '' }}>
                                    {{ $employee->first_name . ' ' . $employee->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">{{ $transaction->notes }}</textarea>
                    </div>

                    <!-- Recorded By -->
                    <div>
                        <label for="recorded_by_display"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recorded By</label>

                        <input type="text" id="recorded_by_display"
                            value="{{ $transaction->recordedBy->name ?? 'N/A' }}" disabled
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />

                        <input type="hidden" name="recorded_by" value="{{ $transaction->recordedBy->id ?? '' }}">
                    </div>

                    <!-- To be Edited By -->
                    <div>
                        <label for="recorded_by_display"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">To be Edited By</label>
                        <input type="text" id="recorded_by_display" value="{{ Auth::user()->name }}" disabled
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                        <input type="hidden" name="recorded_by" value="{{ Auth::id() }}">
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
