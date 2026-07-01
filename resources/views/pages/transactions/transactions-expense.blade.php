<x-app-layout>
    <div class="px-2 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8 w-full max-w-9xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-2 sm:gap-4">
            <div class="mb-4 sm:mb-6 fade-in">
                <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:justify-between sm:items-start">
                    <div class="flex-1">
                        <nav class="flex mb-2" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-1 sm:space-x-2 text-xs sm:text-sm">
                                <li><a href="#"
                                        class="text-gray-500 hover:text-purple-600 truncate">Transactions</a></li>
                                <li class="flex items-center">
                                    <span class="text-gray-400 mx-1 sm:mx-2">›</span>
                                    <a href="{{ route('transactions.' . strtolower($transactionType)) }}"
                                        class="text-gray-500 hover:text-purple-600 truncate">
                                        {{ $transactionType }}
                                    </a>
                                </li>
                                <li class="flex items-center">
                                    <span class="text-gray-400 mx-1 sm:mx-2">›</span>
                                    <span class="text-gray-500 truncate">List</span>
                                </li>
                            </ol>
                        </nav>
                        <h1
                            class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-gray-800 dark:text-gray-100">
                            {{ $transactionType }} Transactions
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Manage your salon's {{ strtolower($transactionType) }} transactions.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-2 lg:px-8 w-full max-w-9xl flex justify-end">
        <button id="addTransactionBtn"
            class="w-auto px-4 py-2 bg-purple-700 text-white text-sm font-medium rounded-lg hover:bg-purple-800 dark:bg-purple-600 dark:hover:bg-purple-700 transition-colors duration-200 flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Add Transaction</span>
        </button>
    </div>

    <!-- Section Modal -->
    <div id="sectionModal" class="fixed inset-0 hidden z-50 fill-white drop-shadow-xl/50"
        style="z-index: 9999; backdrop-filter: blur(4px);">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="error" id="returned-error"></div>
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-0">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Add New
                            {{ $transactionType }} Transaction
                        </h3>
                        <button id="closeModal" type="button"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Add Section Form -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">

                        <form id="transactionForm" action="{{ route('transactions.store') }}" method="POST"
                            class="space-y-4 sm:space-y-6">
                            @csrf
                            <input type="hidden" name="transaction_type" value="{{ $transactionType }}">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">

                                <!-- Customer Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Customer Name
                                    </label>
                                    <input type="text" name="customer_name" value="Walkin Client"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm sm:text-base"
                                        required>
                                </div>
                                <!-- Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Date
                                    </label>
                                    <input type="date" name="date"
                                        value="{{ old('date', now()->format('Y-m-d')) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm sm:text-base"
                                        required>
                                </div>
                            </div>

                            <!-- Two Column Grid for smaller fields -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <!-- Receipt ID -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Receipt ID <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="receipt_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm sm:text-base"
                                        required>
                                </div>

                                <!-- Transaction Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Transaction Type <span class="text-red-500">*</span>
                                    </label>
                                    <input disabled value="{{ $transactionType }}"
                                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm sm:text-base">
                                </div>
                            </div>

                            <!-- Service/Expense and Amount Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <!-- Service Offered / Expense Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ $transactionType }} Service <span class="text-red-500">*</span>
                                    </label>

                                    <select id="expense_type" name="expense_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm sm:text-base"
                                        required>
                                        <option value="">-- Select Expense --</option>
                                        @foreach ($services as $service)
                                        <option value="{{ $service->id }}" data-name="{{ $service->name }}"
                                            data-price="{{ $service->price }}"
                                            data-service-id="{{ $service->id }}">
                                            {{ $service->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Payment Method
                                    </label>
                                    <select name="payment_method"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm sm:text-base"
                                        required>
                                        <option value="Cash">Cash</option>
                                        <option value="MobileMoney">Mobile Money</option>
                                        <option value="Card">Card</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Payment Method, Date, Employee Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <div class="sm:col-span-2 lg:col-span-1">
                                    <label
                                        class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Initiated By (On Behalf of) <span class="text-red-500">*</span>
                                    </label>
                                    <select name="employee_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                                            dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm sm:text-base"
                                        required>
                                        <option value="">-- Select Employee --</option>
                                        @foreach ($employees as $employee)
                                        <option value="{{ $employee['id'] }}" data-salary="{{ $employee['salary'] }}">{{ $employee['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Amount -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Amount
                                    </label>
                                    <input type="text" id="amount_display"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm sm:text-base"
                                        required>
                                    <input type="hidden" id="amount" name="amount">
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Notes
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm sm:text-base resize-none"></textarea>
                            </div>

                            <!-- Recorded By -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Recorded By
                                </label>
                                <input type="text" value="{{ Auth::user()->name }}" disabled
                                    class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 text-sm sm:text-base">
                                <input type="hidden" name="recorded_by" value="{{ Auth::id() }}">
                            </div>

                            <!-- Modal footer -->
                            <div
                                class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" @click="modalIsOpen = false"
                                    class="w-full sm:w-auto px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200 text-sm sm:text-base">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200 text-sm sm:text-base">
                                    Save Transaction
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="px-2 sm:px-4 lg:px-8 py-4 sm:py-3 lg:py-4 w-full max-w-9xl mx-auto">
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
            <!-- Total Records -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm sm:shadow p-3 sm:p-4 flex flex-col items-center min-h-[80px] sm:min-h-[100px]">
                <span class="text-gray-500 text-xs sm:text-sm text-center leading-tight">Total Records</span>
                <span id="totalRecordsCount"
                    class="text-purple-700 dark:text-white font-bold text-sm sm:text-lg lg:text-xl mt-1"></span>
            </div>

            <!-- Current Page -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm sm:shadow p-3 sm:p-4 flex flex-col items-center min-h-[80px] sm:min-h-[100px]">
                <span class="text-gray-500 text-xs sm:text-sm text-center leading-tight">Current Page</span>
                <span id="currnetPage"
                    class="text-purple-700 dark:text-white font-bold text-sm sm:text-lg lg:text-xl mt-1"></span>
            </div>

            <!-- All Pages Total -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm sm:shadow p-3 sm:p-4 flex flex-col items-center min-h-[80px] sm:min-h-[100px]">
                <span class="text-gray-500 text-xs sm:text-sm text-center leading-tight">All Pages Total</span>
                <span id="totalAllPagesAmountRet"
                    class="text-purple-700 dark:text-white font-bold text-sm sm:text-lg lg:text-xl mt-1"></span>
            </div>

            <!-- Total Income/Expense -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm sm:shadow p-3 sm:p-4 flex flex-col items-center min-h-[80px] sm:min-h-[100px]">
                <span class="text-gray-500 text-xs sm:text-sm text-center leading-tight">Total
                    {{ $transactionType }}</span>
                <span id="total{{ $transactionType }}"
                    class="text-purple-700 dark:text-white font-bold text-sm sm:text-lg lg:text-xl mt-1"></span>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="px-2 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8 w-full max-w-9xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg sm:rounded-xl overflow-hidden">
            <!-- Search and Filter Section -->
            <div class="p-3 sm:p-4 lg:p-6 border-b border-gray-200 dark:border-gray-700">
                <div
                    class="flex flex-col space-y-3 sm:space-y-4 lg:flex-row lg:space-y-0 lg:items-center lg:justify-between">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        <!-- Search -->
                        <div class="flex-1 lg:max-w-md">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" name="simple-search"
                                    class="w-full pl-8 sm:pl-10 pr-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Search transactions...">
                            </div>
                        </div>
                        <!-- Expense Type Filter -->
                        <div class="flex-1 lg:max-w-md">
                            <label for="expense-type-filter" class="sr-only">Expense Type</label>
                            <div class="relative">
                                <select id="expense-type-filter" name="expense-type-filter"
                                    class="w-full pl-3 pr-3 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">All Expense Types</option>
                                    @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="w-full sm:w-auto sm:flex-1 sm:max-w-xs">
                            <x-datepicker class="w-full" />
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <!-- Export Button -->
                        <button type="button" id="export-button"
                            class="flex items-center justify-center px-3 sm:px-4 py-2 sm:py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="hidden sm:inline">Export PDF</span>
                            <span class="sm:hidden">Export</span>
                        </button>

                        <!-- Filter Dropdown -->
                        <x-dropdown-income-filter align="right" type="expense" :filterPageCount="false" :showActions="false" />
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="transactions-spinner"
                class="absolute inset-0 flex items-center justify-center bg-white/70 dark:bg-gray-800/70 z-50 hidden">
                <div class="flex items-center space-x-2">
                    <svg class="animate-spin w-6 h-6 sm:w-8 sm:h-8 text-purple-600" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Loading...</span>
                </div>
            </div>
            <!-- Table Container -->
            <div class="relative">
                <div class="overflow-x-auto">
                    <div id="transactions-export-wrapper">
                        <table id="transactions-table"
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-2 sm:px-4 py-3 min-w-[100px]">Date</th>
                                    <th class="px-2 sm:px-4 py-3 min-w-[120px]">Expense</th>
                                    <th class="px-2 sm:px-4 py-3 min-w-[100px] hidden sm:table-cell">Receipt ID</th>
                                    <th class="px-2 sm:px-4 py-3 min-w-[120px] hidden md:table-cell">Recorded By</th>
                                    <th class="px-2 sm:px-4 py-3 min-w-[100px] hidden lg:table-cell">Payment</th>
                                    <th class="px-2 sm:px-4 py-3 min-w-[100px] text-right">Amount</th>
                                    <th class="px-2 sm:px-4 py-3 min-w-[100px] text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="transactions-wrapper"
                                class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                <!-- DataTables will populate this -->
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr class="font-semibold text-gray-900 dark:text-white">
                                    <th class="px-2 sm:px-4 py-3 text-left">Total</th>
                                    <th class="px-2 sm:px-4 py-3 hidden sm:table-cell" colspan="3"></th>
                                    <th class="px-2 sm:px-4 py-3 hidden lg:table-cell"></th>
                                    <th class="px-2 sm:px-4 py-3 text-right" id="totalPageAmount">0</th>
                                    <th class="px-2 sm:px-4 py-3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->

        <input type="hidden" id="transaction_type" value="{{ $transactionType }}">
    </div>

    <!-- Include html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- jQuery for amount formatting -->
    <script>
        $(document).ready(function() {
            $('#amount').val(parseFloat($('#amount_display').val().replace(/,/g, '')));

            $('#amount_display').on('input', function() {
                let inputVal = $(this).val().replace(/,/g, '');
                if (!isNaN(inputVal) && inputVal.trim() !== '') {
                    $('#amount').val(parseFloat(inputVal));
                } else {
                    $('#amount').val('');
                }
            });
        });
    </script>
    <!-- Main DataTable Script -->
    <script>
        $(document).ready(function() {
            var perPage = 10;
            var currentPage = 1;
            let searchTimeout;
            let searchInput = '';
            let cashTypeFilter = '';
            let expenseTypeFilter = '';
            let recordCountTotal = '';
            var transaction_type = $('#transaction_type').val();
            var defaultText = ''
            if (transaction_type !== "Income") {
                defaultText = "-";
            } else {
                defaultText = "Walkin Client";
            }

            let fromDate = null;
            let toDate = null;
            const baseUrl = "{{ url('/') }}";

            loadTransactions();


            function openSectionModal() {
                const modal = document.getElementById('sectionModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
                resetSectionForm();
            }

            function resetSectionForm() {
                // console.log('Resetting section form',$('#sectionForm').reset());
                $('#transactionForm')[0].reset();

                $('#sectionId').val('');
                $('#submitSectionBtn').text('Add Section');
                $('.error-message').text('');
                isSectionEditMode = false;
                currentSectionId = null;
                isSalaryExpense = false;
            }

            function closeModal() {
                const modal = document.getElementById('sectionModal');
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';

                resetSectionForm();
            }
            $('#amount').val(parseFloat($('#amount_display').val().replace(/,/g, '')));


            // Flag to track if salary-related expense type is selected
            let isSalaryExpense = false;

            $('#expense_type').on('change', function() {
                let selectedOption = $(this).find('option:selected');
                let rawPrice = selectedOption.data('price');
                let service_id = selectedOption.data('service-id'); // use data-service-id
                let serviceName = selectedOption.data('name');

                // Check if it's a salary-related expense type
                isSalaryExpense = serviceName && (serviceName.toLowerCase().includes('staff salary') || serviceName.toLowerCase().includes('salary') || serviceName.toLowerCase().includes('Staff Salaries'));

                if (isSalaryExpense) {
                    let employeeSelect = $('select[name="employee_id"]');
                    let selectedEmployee = employeeSelect.find('option:selected');
                    let employeeSalary = selectedEmployee.data('salary');
                    if (employeeSalary) {
                        let formattedSalary = Number(employeeSalary).toLocaleString('en-US');
                        $('#amount_display').val(formattedSalary);
                        $('#amount').val(employeeSalary);
                    }
                } else if (rawPrice) {
                    let formatted = Number(rawPrice).toLocaleString('en-US');
                    $('#amount_display').val(formatted);
                    $('#amount').val(rawPrice);
                    $('#service_id').val(service_id);
                    notes_message = 'Service payments  for ' + selectedOption.data('name') +
                        'have been received.';
                    $('#notes').val(notes_message);
                } else {
                    $('#amount_display').val('');
                    $('#service_id').val('');
                    $('#amount').val('');
                }
            });

            // Auto-fill salary when employee is selected and salary expense flag is set
            $('select[name="employee_id"]').on('change', function() {
                let selectedEmployee = $(this).find('option:selected');
                let employeeSalary = selectedEmployee.data('salary');

                if (isSalaryExpense && employeeSalary) {
                    let formattedSalary = Number(employeeSalary).toLocaleString('en-US');
                    $('#amount_display').val(formattedSalary);
                    $('#amount').val(employeeSalary);
                }
            });

            // Close section modal handlers
            $('#closeModal, #cancelBtn').on('click', function(e) {
                e.preventDefault();
                closeModal();
            });

            $('#sectionModal').on('click', function(e) {
                if (e.target.id === 'sectionModal') {
                    closeModal();
                }
            });

            // Open Section Management Modal
            $('#addTransactionBtn').on('click', function() {
                openSectionModal();
            });

            $('#closeModal, #cancelBtn').on('click', function(e) {
                e.preventDefault();
                closeModal();
            });


            function openSectionModal() {
                const modal = document.getElementById('sectionModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
                resetSectionForm();
            }

            function closeSectionModal() {
                const modal = document.getElementById('sectionModal');
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';

                resetSectionForm();
            }


            function loadTransactions() {
                const table = new DataTable('#transactions-table', {
                    responsive: true,
                    destroy: true, // Allow reinitialization
                    ordering: true,
                    serverSide: true,
                    processing: true,
                    pageLength: perPage,
                    searching: false,
                    lengthChange: true,
                    lengthMenu: [5, 10, 25, 50, 100, 500, 1000, 5000, 10000],
                    order: [
                        [0, 'desc']
                    ],

                    ajax: {
                        url: "{{ route('transactions.getRecords') }}",
                        data: {
                            transaction_type: $('#transaction_type').val(),
                            perPage: perPage,
                            searchTerm: searchInput,
                            cashTypeFilter: cashTypeFilter,
                            expenseTypeFilter: expenseTypeFilter,
                            fromDate: fromDate,
                            toDate: toDate
                        },
                        dataSrc: function(response) {
                            // Update summary info
                            const totalRecords = response.recordsFiltered || 0;
                            $('#totalRecordsCount, #totaLRecordsReturned').text(totalRecords);
                            $('#totalAllPagesAmountRet').text(Number(response.totalAmountAllPages || 0)
                                .toLocaleString())
                            let totalValue = 0;
                            if (transaction_type === 'Expense') {
                                totalValue = Number(response.totalExpense || 0);
                            } else {
                                totalValue = Number(response.totalIncome || 0);
                            }
                            $(`#total${transaction_type}`).text(
                                totalValue.toLocaleString('en-US', {
                                    maximumFractionDigits: 0
                                })
                            );

                            return response.data;
                        },
                        complete: function() {
                            $('#transactions-spinner').addClass('hidden');
                        },
                        error: function(xhr, status, error) {
                            console.error('DataTable AJAX error:', status, error);
                        }
                    },
                    // Update the columns configuration to dynamically handle both Income and Expense
                    columns: [{
                            data: "date",
                            render: function(data) {
                                if (!data) return 'N/A';

                                const date = new Date(data);

                                const datePart = date.toLocaleDateString('en-GB', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                });

                                return `${datePart}`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                if (row.service && row.service.service_name) {
                                    return row.service.service_name;
                                } else if (row.service_description) {
                                    return row.service_description;
                                }
                                return "N/A";
                            }
                        },
                        {
                            data: "receipt_id",
                            defaultContent: "N/A"
                        },
                        {
                            data: null,
                            defaultContent: "-",
                            render: function(data) {
                                return data.employee && data.employee.first_name && data.employee
                                    .last_name ?
                                    data.employee.first_name + " " + data.employee.last_name :
                                    defaultText;
                            }
                        },
                        {
                            data: "payment_method",
                            defaultContent: "N/A"
                        },
                        {
                            data: "amount",
                            render: function(data) {
                                return Number(data || 0).toLocaleString();
                            }
                        },
                        {
                            data: null,
                            className: "text-center",
                            render: function(row) {
                                const detailsUrl = "{{ route('transactions.details', ':id') }}"
                                    .replace(':id', row.id);
                                const editUrl = "{{ route('transactions.edit', ':id') }}".replace(
                                    ':id', row.id);

                                return `
                                        <div class="flex space-x-1 sm:space-x-2 justify-center">
                                            <!-- View (Eye icon) -->
                                            <a href="${detailsUrl}"
                                            class="p-1 sm:p-2 rounded-md bg-purple-500 text-white hover:bg-purple-600 transition-colors"
                                            title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            </a>

                                            <!-- Edit (Pencil icon) -->
                                        <a href="${editUrl}"
                                class="p-1 sm:p-2 rounded-md bg-green-500 text-white hover:bg-green-600 transition-colors"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-7l7 7m0 0v4m0-4h-4"/>
                                </svg>
                                </a>
                                            <!-- Delete (Trash icon) -->
                                            <button type="button"
                                            class="action-link delete-link p-1 sm:p-2 rounded-md bg-red-500 text-white hover:bg-red-600 transition-colors"
                                            data-action="delete" data-id="${row.id}" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a2 2 0 00-2 2v0h8v0a2 2 0 00-2-2m-4 0V5a2 2 0 014 0v0" />
                                            </svg>
                                            </button>

                                        </div>
                                    `;
                            }

                        }
                    ],
                    drawCallback: function(settings) {
                        const api = this.api();

                        // Sum amounts for current page
                        const totalCurrent = api.column(5, {
                                page: 'current'
                            })
                            .data()
                            .reduce((sum, val) => {
                                let num = 0;
                                if (typeof val === 'number') {
                                    num = val;
                                } else if (typeof val === 'string') {
                                    num = parseFloat(val.replace(/[^0-9.-]+/g, '')) || 0;
                                } else if (val && val.amount !== undefined) {
                                    num = parseFloat(val.amount) || 0;
                                }
                                return sum + num;
                            }, 0);

                        // Sum amounts for all pages
                        const totalAll = api.column(5, {
                                page: 'all'
                            })
                            .data()
                            .reduce((sum, val) => {
                                let num = 0;
                                if (typeof val === 'number') {
                                    num = val;
                                } else if (typeof val === 'string') {
                                    num = parseFloat(val.replace(/[^0-9.-]+/g, '')) || 0;
                                } else if (val && val.amount !== undefined) {
                                    num = parseFloat(val.amount) || 0;
                                }
                                return sum + num;
                            }, 0);

                        // Show current page total
                        $("#totalPageAmount").text(totalCurrent.toLocaleString('en-US', {
                            style: 'currency',
                            currency: 'UGX',
                            maximumFractionDigits: 0
                        }));

                        // Show all pages total (add a new element in your HTML if needed)
                        $("#currnetPage").text(totalAll.toLocaleString());
                        $('#transactions-spinner').addClass('hidden');
                    },

                    initComplete: function() {
                        $('#transactions-spinner').addClass('hidden');
                    }
                });
            }

            $(document).on("click", ".delete-link", function(e) {
                e.preventDefault();
                let id = $(this).data("id");
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 m-1 rounded",
                        cancelButton: "btn btn-danger bg-red-600 text-white hover:bg-red-700 px-4 py-2 m-1 rounded"
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const detailsBaseUrl = `${baseUrl}/transactions/delete-record`;
                        window.location.href = `${detailsBaseUrl}/${id}`;

                        swalWithBootstrapButtons.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelled",
                            text: "Your file is safe :)",
                            icon: "error"
                        });
                    }
                });
            });
            // Cash type filter
            $(document).on('change', '.cash-type', function() {
                $('.cash-type').not(this).prop('checked', false);
                cashTypeFilter = $(this).val();
                loadTransactions();
                $(this).closest('[x-data]').find('[x-show]').removeClass('block').addClass('hidden');
            });

            $("#dateSelect").on("change", function() {
                let val = $(this).val();
                if (val.includes(" - ")) {
                    // range mode
                    let parts = val.split(" - ");
                    fromDate = parts[0].trim();
                    toDate = parts[1].trim();
                } else {
                    // single date
                    fromDate = val.trim();
                }

                loadTransactions();
            });

            // Page size change
            $(document).on('change', '.page-size-option', function() {
                $('.page-size-option').not(this).prop('checked', false);
                perPage = $(this).val() === "All" ? recordCountTotal : parseInt($(this).val());
                loadTransactions();
            });

            // Search input
            $(document).on('input', '#simple-search', function() {
                clearTimeout(searchTimeout);
                searchInput = $(this).val();
                searchTimeout = setTimeout(() => {
                    loadTransactions(1); // restart at first page
                }, 300);
            });

            // Expense type filter
            $(document).on('change', '#expense-type-filter', function() {
                expenseTypeFilter = $(this).val();
                loadTransactions();
            });

            // Export button functionality
            $(document).on("click", "#export-button", function() {
                // Create a temporary element for PDF generation
                const element = document.createElement('div');

                let now = new Date();
                let currentDate = now.toLocaleString('en-US');
                // Create a styled version of the table for PDF
                element.innerHTML = `
                    <div style="font-family: Arial, sans-serif; padding: 20px;">
                        <h1 style="text-align: center; color: #4F46E5; margin-bottom: 5px;">Nailville Beauty Salon</h1>
                        <h2 style="text-align: center; color: #6B7280; margin-top: 0; margin-bottom: 15px;">${transaction_type} Transactions Report</h2>
                        <p style="text-align: center; color: #6B7280; margin-bottom: 20px;">Generated on: ${currentDate}</p>
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Date</th>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">${transaction_type === 'Income' ? 'Service Description' : 'Expense Category'}</th>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Receipt ID</th>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">${transaction_type === 'Income' ? 'Service Delivered By' : 'Recorded By'}</th>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Payment Method</th>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${$('#transactions-table tbody tr').map(function() {
                                    const cells = $(this).find('td');
                                    if (cells.length > 0) {
                                        return `
                                                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                                                        <td style="border: 1px solid #ddd; padding: 8px;">${cells.eq(0).text()}</td>
                                                                                                                                                                                                                                                                                                        <td style="border: 1px solid #ddd; padding: 8px;">${cells.eq(1).text()}</td>
                                                                                                                                                                                                                                                                                                        <td style="border: 1px solid #ddd; padding: 8px;">${cells.eq(2).text()}</td>
                                                                                                                                                                                                                                                                                                        <td style="border: 1px solid #ddd; padding: 8px;">${cells.eq(3).text()}</td>
                                                                                                                                                                                                                                                                                                        <td style="border: 1px solid #ddd; padding: 8px;">${cells.eq(4).text()}</td>
                                                                                                                                                                                                                                                                                                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">${cells.eq(5).text()}</td>
                                                                                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                                                                                `;
                                    }
                                    return '';
                                }).get().join('')}
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f9fafb; font-weight: bold;">
                                    <td style="border: 1px solid #ddd; padding: 8px;" colspan="5">Total</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">${$('#totalPageAmount').text()}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <div style="margin-top: 30px; text-align: center; color: #6B7280; font-size: 12px;">
                            <p>Report generated by Nailville Beauty Salon Management System</p>
                        </div>
                    </div>
                `;
                // PDF options
                const options = {
                    margin: 10,
                    filename: `${transaction_type}_Transactions_${currentDate.replace(/\//g, '-')}.pdf`,
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'landscape'
                    }
                };

                // Generate PDF
                html2pdf().set(options).from(element).save();
            });
        });
    </script>
</x-app-layout>