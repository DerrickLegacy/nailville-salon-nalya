<x-app-layout>
    <div class="px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8 w-full max-w-full mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-4 sm:mb-6 lg:mb-8">

            <div class="mb-3 sm:mb-0">
                <h1 class="text-lg sm:text-xl md:text-2xl text-gray-800 dark:text-gray-100 font-bold">Dashboard</h1>
            </div>

            <div class="flex flex-wrap gap-2 justify-start sm:justify-end">
                <x-dropdown-filter align="right" />
                <button
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white text-xs sm:text-sm px-3 py-2">
                    <svg class="fill-current shrink-0 w-4 h-4 sm:mr-1" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="hidden sm:inline text-xs sm:text-sm">Add View</span>
                </button>
            </div>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <!-- Card 1: Today Invoices -->
            <div class="bg-teal-400 text-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-4 flex items-center space-x-3">
                <div class="p-2 sm:p-3 bg-teal-500 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h3l2 2h4l2-2h3a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs sm:text-sm truncate">Today Invoices</p>
                    <p class="text-base sm:text-lg font-semibold">+{{ $cardData['today_invoices'] }}</p>
                </div>
            </div>

            <!-- Card 3: Today Sales -->
            <div class="bg-orange-400 text-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-4 flex items-center space-x-3">
                <div class="p-2 sm:p-3 bg-orange-500 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13m-11-6v6m4-6v6m-8 0h16" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs sm:text-sm truncate">Today Sales</p>
                    <p class="text-base sm:text-lg font-semibold truncate">↑{{ number_format($cardData['today_sales']) }} Ugx</p>
                </div>
            </div>

            <!-- Card 2: This Month Invoices -->
            <div class="bg-pink-400 text-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-4 flex items-center space-x-3">
                <div class="p-2 sm:p-3 bg-pink-500 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h3l2 2h4l2-2h3a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs sm:text-sm truncate">This Month Invoices</p>
                    <p class="text-base sm:text-lg font-semibold">↑{{ $cardData['month_invoices'] }}</p>
                </div>
            </div>

            <!-- Card 4: This Month Sales -->
            <div class="bg-green-400 text-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-4 flex items-center space-x-3">
                <div class="p-2 sm:p-3 bg-green-500 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V4m0 16v-4" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs sm:text-sm truncate">This Month Sales</p>
                    <p class="text-base sm:text-lg font-semibold truncate">↑{{ number_format($cardData['month_sales']) }} Ugx</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-3 space-y-4 sm:space-y-6"> <!-- left side -->

                <h2 class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-100 flex justify-start">Todays Sales Vs
                    Previous Day</h2>
                <div class="grid grid-cols-12 gap-3 sm:gap-4 lg:gap-6">
                    <x-dashboard.dashboard-card-01 :getTodaysIncomeSales="$getTodaysIncomeSales" />
                    <x-dashboard.dashboard-card-02 :getTodaysExpense="$getTodaysExpense" />
                    <x-dashboard.dashboard-card-03 :getTodaysNetIncome="$getTodaysNetIncome" />
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <h2 class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-100 flex justify-start">Previous 30
                        Days
                        Transactions</h2>
                    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-lg sm:rounded-xl p-3 sm:p-4">
                        <div id="myfirstchart" class="w-full"
                            style="min-height: 200px; height: 300px; max-height: 60vw;">
                        </div>
                    </div>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <h2 class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-100 flex justify-start">
                        <?php echo date('F'); ?> Transaction Goals
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                        @foreach ($monthlyBusinessGoals as $card)
                        <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-sm p-4 sm:p-5 lg:p-6 flex flex-col">
                            <div class="flex justify-between items-start gap-3">
                                <div class="min-w-0 flex-1">
                                    <!-- Percentage -->
                                    <p class="text-{{ $card['color'] }}-500 font-bold text-base sm:text-lg">
                                        {{ $card['percentage'] }}%
                                    </p>

                                    <!-- Title -->
                                    <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm truncate">{{ $card['title'] }}</p>

                                    <!-- Values -->
                                    <p class="text-gray-800 dark:text-gray-200 font-semibold text-xs sm:text-sm mt-1 truncate">
                                        {{ number_format($card['value']) }} Ugx /
                                        {{ number_format($card['target']) }} Ugx
                                    </p>
                                </div>

                                <!-- Icon -->
                                <div class="bg-{{ $card['color'] }}-100 dark:bg-{{ $card['color'] }}-900/20 p-2 sm:p-3 rounded-lg flex-shrink-0">
                                    @if ($card['icon'] === 'money')
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 sm:w-6 sm:h-6 text-{{ $card['color'] }}-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zm0 6v2m0-10V4m0 16h.01" />
                                    </svg>
                                    @elseif ($card['icon'] === 'arrow-up')
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 sm:w-6 sm:h-6 text-{{ $card['color'] }}-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    @elseif ($card['icon'] === 'flag')
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 sm:w-6 sm:h-6 text-{{ $card['color'] }}-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5-5 5M6 7l5 5-5 5" />
                                    </svg>
                                    @elseif ($card['icon'] === 'box')
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 sm:w-6 sm:h-6 text-{{ $card['color'] }}-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V7a2 2 0 00-2-2h-6V3H8v2H4a2 2 0 00-2 2v6h2v7a2 2 0 002 2h12a2 2 0 002-2v-7h2z" />
                                    </svg>
                                    @endif
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 sm:h-2 mt-3 sm:mt-4">
                                <div class="bg-{{ $card['color'] }}-500 h-1.5 sm:h-2 rounded-full transition-all duration-300"
                                    style="width: {{ min($card['percentage'], 100) }}%">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3 sm:space-y-4 animate-on-scroll">
                    <h2 class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-100 flex justify-start">
                        <?php echo date('F'); ?> Transaction Count(Income Vs Expenses)
                    </h2>
                    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-lg sm:rounded-xl p-3 sm:p-4">
                        <div id="record_count" class="w-full"
                            style="min-height: 200px; height: 300px; max-height: 60vw;">
                        </div>
                    </div>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-3 sm:mb-4">
                        <h2 id="recent_transactions"
                            class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-100">
                            Most Recent Income Transactions
                        </h2>
                        <div class="flex gap-2">
                            <button id="btn-income" onclick="showCategory('income')"
                                class="category-btn px-3 py-1.5 text-xs sm:text-sm rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition-colors">
                                Income
                            </button>
                            <button id="btn-expense" onclick="showCategory('expense')"
                                class="category-btn px-3 py-1.5 text-xs sm:text-sm rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                Expense
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto -mx-3 sm:mx-0">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                                <table id="transactions-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date
                                            </th>
                                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service
                                            </th>
                                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Serviced By
                                            </th>
                                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment
                                                Method
                                            </th>
                                            <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="transactions-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-xs rounded-lg sm:rounded-xl p-3 sm:p-4 w-full text-center">
                    <h2 class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-100 mb-2 sm:mb-3">Calendar</h2>
                    <div id="weekRangePicker" class="w-full max-w-full"></div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-xs rounded-lg sm:rounded-xl p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base text-gray-800 dark:text-gray-100 font-semibold mb-2 sm:mb-3 text-center">Employee Performance
                    </h3>
                    <div id="top-employers" class="w-full h-48 sm:h-56 md:h-64 lg:h-80"></div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-xs rounded-lg sm:rounded-xl p-3 sm:p-4">
                    <h3 class="text-sm sm:text-base text-gray-800 dark:text-gray-100 font-semibold text-center">
                        Employee Performance Summary
                    </h3>
                    <p id="month_sumary" class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 text-center mb-3 sm:mb-4"></p>
                    <div id="employers-table" class="overflow-x-auto -mx-3 sm:mx-0"></div>
                </div>

                <div x-data="stockAlert()" class="bg-white dark:bg-gray-800 shadow-xs rounded-lg sm:rounded-xl p-3 sm:p-4 text-center">
                    <h3 class="text-sm sm:text-base text-gray-800 dark:text-gray-100 font-semibold mb-1">
                        Monthly Transactions</h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Expenses Vs Income</p>
                    <small class="text-xs text-gray-500 dark:text-gray-500"><?php echo date("F") ?></small>

                    <div id="transactions-bar-chart-month" class="w-full"
                        style="min-height: 180px; height: 280px; max-height: 50vw;">
                    </div>
                    <div class="flex justify-center gap-4 sm:gap-6 mt-3 sm:mt-4 mb-2 sm:mb-3 items-center">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-blue-500 block"></span>
                            <span class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm">Income</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-red-500 block"></span>
                            <span class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm">Expense</span>
                        </div>
                    </div>
                </div>
                <div x-data="stockAlert()" class="bg-white dark:bg-gray-800 shadow-xs rounded-lg sm:rounded-xl p-3 sm:p-4 text-center">
                    <h3 class="text-sm sm:text-base text-gray-800 dark:text-gray-100 font-semibold mb-1">
                        Year Transactions</h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Expenses Vs Income</p>
                    <small class="text-xs text-gray-500 dark:text-gray-500"><?php echo date("Y") ?></small>
                    <div id="transactions-bar-chart-year" class="w-full"
                        style="min-height: 180px; height: 280px; max-height: 50vw;">
                    </div>
                    <div class="flex justify-center gap-4 sm:gap-6 mt-3 sm:mt-4 mb-2 sm:mb-3 items-center">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-blue-500 block"></span>
                            <span class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm">Income</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-red-500 block"></span>
                            <span class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm">Expense</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
        </div>

    </div>

</x-app-layout>
<style>
    #weekRangePicker .flatpickr-calendar.inline {
        width: 100% !important;
        max-width: 100% !important;
    }

    #weekRangePicker .flatpickr-days {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr) !important;
    }



    /* Optional: shrink day cells for small screens */
    @media (max-width: 640px) {

        /* sm breakpoint */
        #weekRangePicker .flatpickr-day {
            padding: 0.25rem 0.15rem !important;
            font-size: 0.7rem !important;
        }
    }
</style>

<script>
    const incomeTransactions = @json($topIncomeTransactions);
    const expenseTransactions = @json($topExpenseTransactions);

    function formatDate(dateString) {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // 01-12
        const day = String(date.getDate()).padStart(2, '0'); // 01-31
        const hours = String(date.getHours()).padStart(2, '0'); // 00-23
        const minutes = String(date.getMinutes()).padStart(2, '0'); // 00-59
        return `${year}-${month}-${day} ${hours}:${minutes}`;
    }

 function renderTable(data, type = "income") {
    let rows = '';

    data.forEach(trx => {
        console.log(trx);
        var service_or_desc = '';
        
        // These specific employeeFirstName/LastName variables aren't used in the final template anymore, you can remove them if you like:
        // const employeeFirstName = trx.employee?.first_name ?? '';
        // const employeeLastName = trx.employee?.last_name ?? '-'; 

        if (type === 'income') {
            // Corrected: Wrapped the template literal in backticks
            service_or_desc = `${trx.service?.name ?? '-'}`;
        } else {
            service_or_desc = trx.service_description;
        }

        rows += `
            <tr class="hover:bg-purple-50 dark:hover:bg-gray-700 transition-colors">
                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900 dark:text-gray-100 whitespace-nowrap">${formatDate(trx.created_at)}</td>
                
                <!-- Use the service_or_desc variable we set above -->
                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900 dark:text-gray-100">${service_or_desc}</td>
                
                <!-- Employee name display (using optional chaining safely) -->
                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900 dark:text-gray-100">
                    ${trx.employee ? `${trx.employee?.first_name} ${trx.employee?.last_name}` : 'N/A'}
                </td>                
                
                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-900 dark:text-gray-100">${trx.payment_method}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100">${Number(trx.amount).toLocaleString()}</td>
            </tr>
        `;
    });
    document.getElementById('transactions-body').innerHTML = rows;
}



    function showCategory(type) {
        // Render the right table data
        if (type === 'income') {
            renderTable(incomeTransactions,type);
            $('#recent_transactions').text('Most Recent Income Transactions')

        } else {
            renderTable(expenseTransactions,type);
            $('#recent_transactions').text('Most Recent Expense Transactions')
        }

        // Reset all buttons
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('bg-purple-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        });

        // Highlight the active button
        const activeBtn = document.getElementById('btn-' + type);
        activeBtn.classList.remove('bg-gray-200', 'text-gray-700');
        activeBtn.classList.add('bg-[#8470FF]', 'text-white');
    }


    // default: load income first
    showCategory('income');
    $.ajax({
        url: "{{ route('chart.record.count') }}",
        method: "GET",
        dataType: "json",
        success: function(data) {
            console.log("Chart:--", data)
            new Morris.Line({
                element: 'record_count',
                data: data,
                xkey: 'day',
                ykeys: ['IncomeCount', 'ExpenseCount'],
                labels: ['Income Count', 'Expense Count'],
                parseTime: false,
                lineColors: ['#0b62a4', '#7a92a3'],
                pointSize: 3,
                hoverCallback: function(index, options, content, row) {
                    return content + '<br>Income: ' + Number(row.Income).toLocaleString() +
                        '<br>Expense: ' + Number(row.Expense).toLocaleString();
                }
            });

        },
        error: function(xhr, status, error) {
            console.error("Error loading chart data:", error);
        }
    });


    function stockAlert() {
        return {
            query: '',
            items: [],
            filteredItems() {
                return this.items.filter(i =>
                    i.service_name.toLowerCase().includes(this.query.toLowerCase()) ||
                    i.service_type.toLowerCase().includes(this.query.toLowerCase())
                );
            },
            // Fetch stock alerts from server
            fetchItems() {
                fetch('/stock-alerts')
                    .then(resp => resp.json())
                    .then(result => {
                        this.items = result; // set items from API
                    })
                    .catch(err => console.error(err));
            },
            // init() runs automatically when Alpine component is initialized
            init() {
                this.fetchItems(); // fetch data on load
            }
        }
    }
    $.ajax({
        url: "{{ route('chart.data') }}",
        method: "GET",
        dataType: "json",
        success: function(data) {
            var hideYAxis = window.innerWidth < 640;
            new Morris.Area({
                element: 'myfirstchart',
                data: data,
                xkey: 'y',
                ykeys: ['value'],
                labels: ['Income'],
                pointStrokeColors: ['#00B5B8', '#FA8E57', '#F25E75'],
                smooth: true,
                gridLineColor: '#E4E7ED',
                fillOpacity: 0.9,
                behaveLikeLine: true,
                lineColors: ['#8470FF', '#7965C1'],
                parseTime: true,
                resize: true,
                axes: !hideYAxis
            });

            if (hideYAxis) {
                $('#myfirstchart .morris-axis-label').css('display', 'none');
            }
        },
        error: function(xhr, status, error) {}
    });

    $.ajax({
        url: "{{ route('chart.income.expenses.month') }}",
        method: "GET",
        dataType: "json",
        success: function(data) {
            console.log("this MONTH's Data:", data);

            new Morris.Bar({
                element: 'transactions-bar-chart-month',
                data: data,
                xkey: 'month',
                ykeys: ['Income', 'Expense'],
                labels: ['Income', 'Expense'],
                barColors: ['#3490dc', '#e3342f'],
                hideHover: 'auto',
                resize: true
            });
        },
        error: function(xhr, status, error) {
            console.error("Error loading chart data:", error);
        }
    });



    $.ajax({
        url: "{{ route('chart.income.expenses.year') }}",
        method: "GET",
        dataType: "json",
        success: function(data) {
            console.log("YEAR : ", data);
            new Morris.Bar({
                element: 'transactions-bar-chart-year',
                data: data,
                xkey: 'month',
                ykeys: ['Income', 'Expense'],
                labels: ['Income', 'Expense'],
                barColors: ['#3490dc', '#e3342f'],
                hideHover: 'auto',
                resize: true
            });
        },
        error: function(xhr, status, error) {
            console.error("Error loading chart data:", error);
        }
    });


    $.ajax({
        url: "{{ route('employees.status.chart') }}",
        method: "GET",
        dataType: "json",
        success: function(data) {
            new Morris.Bar({
                element: 'employee-status-chart',
                data: data,
                xkey: 'status',
                ykeys: ['count'],
                labels: ['Employees'],
                barColors: function(row, series, type) {
                    return row.label === 'Active' ? '#10b981' : '#ef4444';
                },
                hideHover: 'auto',
                resize: true,
                horizontal: true,
                gridTextColor: '#6b7280',
                gridLineColor: '#e5e7eb',
                barGap: 6,
                barSizeRatio: 0.6,
                fillOpacity: 0.8,
                hoverCallback: function(index, options, content, row) {
                    return `<strong>${row.status}</strong>: ${row.count} employees`;
                }
            });
        },
        error: function(xhr, status, error) {
            console.error("Error loading chart data:", error);
        }
    });

    $.ajax({
        url: "{{ route('chart.top.employers') }}", // Laravel route returning JSON
        method: "GET",
        dataType: "json",
        success: function(data) {
            var month = data.month
            var data = data.data;
            new Morris.Donut({
                element: 'top-employers',
                data: data
            });

            let tableHtml = `
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employer</th>
                                <th class="px-2 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    `;

            data.forEach(function(item) {
                tableHtml += `
                    <tr class="hover:bg-purple-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-2 sm:px-4 py-2 text-xs sm:text-sm text-gray-900 dark:text-gray-100">${item.label}</td>
                        <td class="px-2 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100">${Number(item.value).toLocaleString()}</td>
                    </tr>
                `;
            });

            tableHtml += `
                </tbody>
                    </table>
             `;

            $("#employers-table").html(tableHtml);
            $("#month_sumary").text(month);
        },
        error: function(xhr, status, error) {
            console.error("Error loading chart data:", error);
        }
    });
</script>