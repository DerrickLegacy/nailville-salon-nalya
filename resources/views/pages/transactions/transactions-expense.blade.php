<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="mb-2 flex flex-col md:flex-row md:justify-between md:items-center fade-in">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li><a href="#" class="text-gray-500 hover:text-blue-600">Transactions</a></li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-2">›</span>
                            <a href="{{ route('transactions.' . strtolower($transactionType)) }}"
                                class="text-gray-500 hover:text-blue-600">
                                {{ $transactionType }} Transactions
                            </a>
                        </li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-2">›</span>
                            <a href="" class="text-gray-500 hover:text-blue-600">List</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-2 fade-in">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl font-bold text-gray-900">{{ $transactionType }} Transactions</h1>
            </div>

            @if ($errors->any())
            <div class="p-2 bg-red-100 text-red-800 rounded mb-2">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Datepicker built with flatpickr -->
                <x-datepicker />

                <x-simple-modal title="{{ $transactionType }} Transactions">
                    @slot('trigger')
                    <button x-on:click="modalIsOpen = true"
                        class="btn bg-blue-700 text-gray-100 hover:bg-blue-800 dark:bg-blue-100 dark:text-blue-800 dark:hover:bg-white">
                        Add Transaction
                    </button>
                    @endslot

                    <form x-ref="transactionForm" action="{{ route('transactions.store') }}" method="POST"
                        class="space-y-2">
                        @csrf
                        <input type="hidden" name="transaction_type" value="{{ $transactionType }}">

                        <!-- Customer Name -->
                        <div>
                            <label for="customer_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" value="Walkin Client"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />

                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                            <!-- Receipt ID -->
                            <div>
                                <label for="receipt_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Receipt
                                    ID</label>
                                <input type="text" name="receipt_id" id="receipt_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                            </div>

                            <!-- Transaction Type (disabled) -->
                            <div>
                                <label for="transaction_type"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction
                                    Type</label>
                                <input name="transaction_type" id="transaction_type" disabled
                                    value="{{ $transactionType }}"
                                    class="mt-1 block w-full bg-gray-100 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                            </div>

                            <!-- Income or Expense select -->
                            @if ($transactionType === 'Income')
                            <div>
                                <label for="service_offered"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service
                                    Offered</label>
                                <select name="service_offered" id="service_offered" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                    <option value="">-- Select Service --</option>
                                    <option value="HairCut">Hair Cut</option>
                                    <option value="BraidalPackage">Braidal Package</option>
                                    <option value="HairStyling">Hair Styling / Braiding</option>
                                    <option value="HairColoring">Hair Coloring / Treatment</option>
                                    <option value="ShampooConditioning">Shampoo & Conditioning</option>
                                    <option value="Nails">Nail Care (Manicure / Pedicure)</option>
                                    <option value="Facial">Facial / Skin Care</option>
                                    <option value="Massage">Massage Therapy</option>
                                    <option value="Waxing">Waxing / Hair Removal</option>
                                    <option value="Makeup">Makeup Services</option>
                                    <option value="Packages">Service Packages (Combo Deals)</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            @else
                            <div>
                                <label for="expense_type"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expense
                                    Category</label>
                                <select name="expense_type" id="expense_type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                    <option value="">-- Select Expense --</option>
                                    <option value="Rent">Rent / Lease</option>
                                    <option value="Salaries">Salaries & Wages</option>
                                    <option value="Allowances">Allowances / Bonuses</option>
                                    <option value="Training">Staff Training / Workshops</option>
                                    <option value="Utilities">Utilities (Electricity, Water, Internet)</option>
                                    <option value="BeautyProducts">Beauty Products</option>
                                    <option value="HairSupplies">Hair Supplies</option>
                                    <option value="NailSupplies">Nail Supplies</option>
                                    <option value="Cleaning">Cleaning Supplies / Laundry</option>
                                    <option value="FurnitureEquipment">Furniture & Equipment Purchase</option>
                                    <option value="Maintenance">Equipment Maintenance & Repairs</option>
                                    <option value="Marketing">Marketing & Advertising</option>
                                    <option value="Transport">Transport / Delivery Costs</option>
                                    <option value="Licenses">Licenses, Permits & Insurance</option>
                                    <option value="Miscellaneous">Miscellaneous / Other</option>
                                </select>
                            </div>
                            @endif

                            <!-- Amount -->
                            <div>
                                <label for="amount"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                <input type="number" name="amount" id="amount" step="0.01" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label for="payment_method"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment
                                    Method</label>
                                <select name="payment_method" id="payment_method"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                    <option value="Cash">Cash</option>
                                    <option value="MobileMoney">MobileMoney</option>
                                    <option value="Card">Card</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            {{-- <!-- Date -->
                            <div>
                                <label for="date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                                <input type="date" name="date" id="date" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                            </div> --}}

                            <div>
                                <label for="date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                                <input type="date" name="date" id="date" required
                                    value="{{ old('date', now()->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                            </div>

                            <!-- Employee -->
                            <div>
                                <label for="employee_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Worked on
                                    By</label>
                                <select name="employee_id" id="employee_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                    <option value="">-- Select Employee --</option>
                                    @foreach ($employees as $employee)
                                    <option value="{{ $employee['id'] }}">{{ $employee['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></textarea>
                            </div>

                            <!-- Recorded by -->
                            <div>
                                <label for="recorded_by_display"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recorded
                                    By</label>
                                <input type="text" id="recorded_by_display" value="{{ Auth::user()->name }}"
                                    disabled
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" />
                                <input type="hidden" name="recorded_by" value="{{ Auth::id() }}">
                            </div>
                        </div>
                    </form>

                    @slot('footer')
                    <button x-on:click="modalIsOpen = false"
                        class="px-4 py-2 bg-red-700 text-white rounded">Cancel</button>

                    <button x-on:click="$refs.transactionForm && $refs.transactionForm.submit()"
                        class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                    @endslot
                </x-simple-modal>
            </div>
        </div>

        <div class="py-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Total Records -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex flex-col items-center">
                    <span class="text-gray-500 text-sm">Total Records</span>
                    <span id="totalRecordsCount" class="text-purple-700 dark:text-white font-bold text-lg"></span>
                </div>

                <!-- Current Page -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex flex-col items-center">
                    <span class="text-gray-500 text-sm">Current Page (Shs)</span>
                    <span id="currnetPage" class="text-purple-700 dark:text-white font-bold text-lg"></span>
                </div>

                <!-- All Pages Total -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex flex-col items-center">
                    <span class="text-gray-500 text-sm">All Pages(Shs) </span>
                    <span id="totalAllPagesAmountRet"
                        class="text-purple-700 dark:text-white font-bold text-lg"></span>
                </div>

                <!-- Total Income/Expense -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex flex-col items-center">
                    <span class="text-gray-500 text-sm">Total {{ $transactionType }} (Shs)</span>
                    <span id="total{{ $transactionType }}"
                        class="text-purple-700 dark:text-white font-bold text-lg"></span>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-12 gap-0 fade-in">
            <div class="col-span-full xl:col-span-12 bg-white dark:bg-gray-800 shadow-xs rounded-xl p-3">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">

                        <div class="flex items-center flex-1 space-x-4">
                            <h5>
                                <span class="text-gray-500">All Records:</span>
                                <span class="dark:text-white text-purple-700 font-bold" id="totalRecordsCount">
                                </span>
                            </h5>
                            <h5>
                                <span class="text-gray-500">Total {{ $transactionType }}:</span>
                                <span class="dark:text-white text-purple-700 font-bold"
                                    id="total{{ $transactionType }}"></span>
                            </h5>
                        </div>
                    </div>
                    <hr>
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                            fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" id="simple-search" name="simple-search"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Search" required="">
                                </div>
                            </form>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <!-- Export Button -->
                            <button type="button" id="export-button"
                                class="flex items-center justify-center flex-shrink-0 px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewbox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                Export PDF
                            </button>

                            <x-dropdown-income-filter align="right" type="income" :filterPageCount="false"
                                :showActions="false" />
                        </div>
                    </div>

                    <div class="relative">
                        <div id="transactions-spinner"
                            class="absolute inset-1 flex items-center justify-center bg-white/70 dark:bg-gray-800/70 z-50 ">
                            <div role="status">
                                <svg aria-hidden="true"
                                    class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-yellow-400"
                                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                        fill="currentColor" />
                                    <path
                                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                        fill="currentFill" />
                                </svg>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div
                            class="overflow-x-auto min-h-[200px] sm:min-h-[300px] lg:min-h-[400px] bg-white dark:bg-gray-800 rounded-md shadow">
                            <div id="transactions-export-wrapper">
                                <table id="transactions-table"
                                    class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead>
                                        <tr>
                                            <th class="px-2 py-3">Date</th>
                                            <th class="px-4 py-3">Service </th>
                                            <th class="px-4 py-3">Receipt ID</th>
                                            <th class="px-4 py-3">Serviced By</th>
                                            <th class="px-4 py-3">Payment Method</th>
                                            <th class="px-4 py-3">Amount</th>
                                            <th class="px-4 py-3 text-center">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="transactions-wrapper">
                                        <!-- DataTables will fill rows here -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="px-2 py-3">Total</th>
                                            <th class="px-4 py-3" colspan="4"></th>
                                            <th class="px-2 py-3" id="totalPageAmount"></th>
                                            <th class="px-2 py-3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="transaction_type" value="{{ $transactionType }}">
    </div>

    <!-- Include html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            var perPage = 10;
            var currentPage = 1;
            let searchTimeout;
            let searchInput = '';
            let cashTypeFilter = '';
            let recordCountTotal = '';
            var transaction_type = $('#transaction_type').val();
            console.log("Transaction Type:", transaction_type);
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

                    ajax: {
                        url: "{{ route('transactions.getRecords') }}",
                        data: {
                            transaction_type: $('#transaction_type').val(),
                            perPage: perPage,
                            searchTerm: searchInput,
                            cashTypeFilter: cashTypeFilter,
                            fromDate: fromDate,
                            toDate: toDate
                        },
                        dataSrc: function(response) {
                            // Update summary info
                            const totalRecords = response.recordsFiltered || 0;
                            $('#totalRecordsCount, #totaLRecordsReturned').text(totalRecords);
                            $('#totalAllPagesAmountRet').text(Number(response.totalAmountAllPages || 0)
                                .toLocaleString())

                            // Update total based on transaction type
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
                            data: "created_at",
                            render: function(data) {
                                if (!data) return 'N/A';

                                const date = new Date(data);

                                // Format date part
                                const datePart = date.toLocaleDateString('en-GB', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                });

                                // Format time part
                                const timePart = date.toLocaleTimeString('en-GB', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                });

                                return `${datePart}, ${timePart}`;
                            }
                        },
                        {
                            data: "service_description",
                            defaultContent: "N/A"
                        },
                        {
                            data: "receipt_id",
                            defaultContent: "N/A"
                        },
                        {
                            data: null,
                            render: function(row) {
                                return (row.employee && row.employee.first_name && row.employee
                                        .last_name) ?
                                    `${row.employee.first_name} ${row.employee.last_name}` :
                                    'N/A';
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
                                const editUrl = "{{ route('transactions.edit', ':id') }}"
                                    .replace(
                                        ':id', row.id);



                                return `
        <div class="flex space-x-2 justify-center">
            <!-- View (Eye icon) -->
            <a href="${detailsUrl}"
               class="p-2 rounded-md bg-blue-500 text-white hover:bg-blue-600"
               title="View">
               <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
               </svg>
            </a>

            <!-- Edit (Pencil icon) -->
           <a href="${editUrl}"
   class="p-2 rounded-md bg-green-500 text-white hover:bg-green-600"
   title="Edit">
   <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-7l7 7m0 0v4m0-4h-4"/>
   </svg>
</a>





            <!-- Delete (Trash icon) -->
            <button type="button"
               class="action-link delete-link p-2 rounded-md bg-red-500 text-white hover:bg-red-600"
               data-action="delete" data-id="${row.id}" title="Delete">
               <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                        const totalCurrent = api.column(6, {
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
                        const totalAll = api.column(6, {
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

                        console.log(totalCurrent, '--', totalAll)

                        // Hide spinner after draw
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
                        confirmButton: "btn btn-success bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 m-1 rounded",
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
                console.log("Raw value:", val);

                if (val.includes(" - ")) {
                    // range mode
                    let parts = val.split(" - ");
                    fromDate = parts[0].trim();
                    toDate = parts[1].trim();
                } else {
                    // single date
                    fromDate = val.trim();
                }

                console.log("From:", fromDate);
                console.log("To:", toDate);
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

            // Export button functionality
            $(document).on("click", "#export-button", function() {
                console.log("Exporting to PDF...");

                // Create a temporary element for PDF generation
                const element = document.createElement('div');

                let now = new Date();
                let currentDate = now.toLocaleString('en-US');
                // console.log(formattedDateTime);


                // Create a styled version of the table for PDF
                // In the export button click handler, update the table structure:
                element.innerHTML = `
                    <div style="font-family: Arial, sans-serif; padding: 20px;">
                        <h1 style="text-align: center; color: #4F46E5; margin-bottom: 5px;">Kenvies Beauty Salon</h1>
                        <h2 style="text-align: center; color: #6B7280; margin-top: 0; margin-bottom: 15px;">${transaction_type} Transactions Report</h2>
                        <p style="text-align: center; color: #6B7280; margin-bottom: 20px;">Generated on: ${currentDate}</p>
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Date</th>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">${transaction_type === 'Income' ? 'Service Description' : 'Expense Category'}</th>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Receipt ID</th>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">${transaction_type === 'Income' ? 'Service Delivered By' : 'Recorded By'}</th>
                                    ${transaction_type === 'Income' ? '<th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Customer Name</th>' : ''}
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Payment Method</th>
                                    <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${$('#transactions-table tbody tr').map(function() {
                                    const cells = $(this).find('td');
                                    if (cells.length > 0) {
                                        let rowHtml = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td style="border: 1px solid #ddd; padding: 8px;">${cells.eq(0).text()}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td style="border: 1px solid #ddd; padding: 8px;">${cells.eq(1).text()}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td style="border: 1px solid #ddd; padding: 8px;">${cells.eq(2).text()}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td style="border: 1px solid #ddd; padding: 8px;">${cells.eq(3).text()}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                `;

                                        // Add customer name column only for Income
                                        if (transaction_type === 'Income') {
                                            rowHtml += `<td style="border: 1px solid #ddd; padding: 8px;">${cells.eq(4).text()}</td>`;
                                        }

                                        // Determine the index for payment method and amount based on transaction type
                                        const paymentMethodIndex = transaction_type === 'Income' ? 5 : 4;
                                        const amountIndex = transaction_type === 'Income' ? 6 : 5;

                                        rowHtml += ` <
                    td style = "border: 1px solid #ddd; padding: 8px;" > $ {
                        cells.eq(paymentMethodIndex).text()
                    } < /td> <
                td style = "border: 1px solid #ddd; padding: 8px; text-align: right;" > $ {
                    cells.eq(amountIndex).text()
                } < /td> < /
                tr >
                    `;

                                        return rowHtml;
                                    }
                                    return '';
                                }).get().join('')}
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f9fafb; font-weight: bold;">
                                    <td style="border: 1px solid #ddd; padding: 8px;" colspan="${transaction_type === 'Income' ? 6 : 5}">Total</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">${$('#totalPageAmount').text()}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <div style="margin-top: 30px; text-align: center; color: #6B7280; font-size: 12px;">
                            <p>Report generated by Kenvies Beauty Salon Management System</p>
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