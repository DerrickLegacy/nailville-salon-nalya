<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <div class="mb-4 sm:mb-0">
                <h1 class="text-xl md:text-2xl text-gray-800 dark:text-gray-100 font-bold">Dashboard</h1>
            </div>

            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <x-dropdown-filter align="right" />
                <button
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Add View</span>
                </button>
            </div>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-0 mb-6 ">
            <!-- Card 1: Today Invoices -->
            <div class="bg-teal-400 text-white rounded-xl shadow p-4 flex items-center space-x-4">
                <div class="p-3 bg-teal-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h3l2 2h4l2-2h3a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm">Today Invoices</p>
                    <p class="text-lg-- font-semibold">+{{ $cardData['today_invoices'] }}</p>
                </div>
            </div>

            <!-- Card 2: This Month Invoices -->
            <div class="bg-pink-400 text-white rounded-xl shadow p-4 flex items-center space-x-4">
                <div class="p-3 bg-pink-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h3l2 2h4l2-2h3a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm">This Month Invoices</p>
                    <p class="text-lg-- font-semibold">↑{{ $cardData['month_invoices'] }}</p>
                </div>
            </div>

            <!-- Card 3: Today Sales -->
            <div class="bg-orange-400 text-white rounded-xl shadow p-4 flex items-center space-x-4">
                <div class="p-3 bg-orange-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13m-11-6v6m4-6v6m-8 0h16" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm">Today Sales</p>
                    <p class="text-lg-- font-semibold">↑{{ number_format($cardData['today_sales']) }} Ugx</p>
                </div>
            </div>

            <!-- Card 4: This Month Sales -->
            <div class="bg-green-400 text-white rounded-xl shadow p-4 flex items-center space-x-4">
                <div class="p-3 bg-green-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V4m0 16v-4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm">This Month Sales</p>
                    <p class="text-lg-- font-semibold">↑{{ number_format($cardData['month_sales']) }} Ugx</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-3 space-y-6"> <!-- left side -->

                <h2 class=" font-semibold text-gray-800 dark:text-gray-100 flex justify-start">Todays Sales Vs
                    Previous Day</h2>
                <div class="grid grid-cols-12 gap-6">
                    <x-dashboard.dashboard-card-01 :getTodaysIncomeSales="$getTodaysIncomeSales" />
                    <x-dashboard.dashboard-card-02 :getTodaysExpense="$getTodaysExpense" />
                    <x-dashboard.dashboard-card-03 :getTodaysNetIncome="$getTodaysNetIncome" />
                </div>

                <div class="space-y-6">
                    <h2 class="text-lg-- font-semibold text-gray-800 dark:text-gray-100 flex justify-start">Previous 30
                        Days
                        Transactions</h2>
                    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-4">
                        <div id="myfirstchart" class="w-full"
                            style="min-height: 250px; height: 400px; max-height: 60vw;">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h2 class="text-lg-- font-semibold text-gray-800 dark:text-gray-100 flex justify-start">
                        <?php echo date('F'); ?> Transaction Goals
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($monthlyBusinessGoals as $card)
                            <div class="bg-white rounded-xl shadow p-6 flex flex-col">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <!-- Percentage -->
                                        <p class="text-{{ $card['color'] }}-500 font-bold text-lg--">
                                            {{ $card['percentage'] }}%
                                        </p>

                                        <!-- Title -->
                                        <p class="text-gray-600">{{ $card['title'] }}</p>

                                        <!-- Values -->
                                        <p class="text-gray-800 font-semibold text-sm mt-1">
                                            {{ number_format($card['value']) }} Ugx /
                                            {{ number_format($card['target']) }} Ugx
                                        </p>
                                    </div>

                                    <!-- Icon -->
                                    <div class="bg-{{ $card['color'] }}-100 p-3 rounded-lg">
                                        @if ($card['icon'] === 'money')
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-6 h-6 text-{{ $card['color'] }}-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zm0 6v2m0-10V4m0 16h.01" />
                                            </svg>
                                        @elseif ($card['icon'] === 'arrow-up')
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-6 h-6 text-{{ $card['color'] }}-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                        @elseif ($card['icon'] === 'flag')
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-6 h-6 text-{{ $card['color'] }}-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7l5 5-5 5M6 7l5 5-5 5" />
                                            </svg>
                                        @elseif ($card['icon'] === 'box')
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-6 h-6 text-{{ $card['color'] }}-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V7a2 2 0 00-2-2h-6V3H8v2H4a2 2 0 00-2 2v6h2v7a2 2 0 002 2h12a2 2 0 002-2v-7h2z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
                                    <div class="bg-{{ $card['color'] }}-500 h-2 rounded-full"
                                        style="width: {{ min($card['percentage'], 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-6 animate-on-scroll">
                    <h2 class="text-lg-- font-semibold text-gray-800 dark:text-gray-100 flex justify-start ">
                        <?php echo date('F'); ?> Transaction Count(Income Vs Expenses)
                    </h2>
                    <div class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-4">
                        <div id="record_count" class="w-full"
                            style="min-height: 250px; height: 400px; max-height: 60vw;">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
                        <h2 id="recent_transactions"
                            class="font-semibold text-gray-800 dark:text-gray-100 mb-2 sm:mb-0">
                            Most Recent Income Transactions
                        </h2>
                        <div class="flex space-x-4">
                            <button id="btn-income" onclick="showCategory('income')"
                                class="category-btn px-3 py-1 rounded bg-purple-600 text-white">
                                Income
                            </button>
                            <button id="btn-expense" onclick="showCategory('expense')"
                                class="category-btn px-3 py-1 rounded bg-gray-200 text-gray-700">
                                Expense
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="transactions-table" class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                        Transaction ID
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                        Serviced By
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Payment
                                        Method
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="transactions-body" class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6 ">
                <div class="dark:bg-gray-800 shadow-xs rounded-xl p-2 sm:p-4 w-full text-center">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Calendar</h2>
                    <div id="weekRangePicker" class="w-full max-w-full"></div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-4">
                    <h3 class="text-gray-800 dark:text-gray-100 font-semibold mb-2 text-center">Employee Performance
                    </h3>
                    <div id="top-employers" class="w-full h-64 sm:h-72 md:h-80 lg:h-96"></div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-4 ">
                    <h3 class="text-gray-800 dark:text-gray-100 font-semibold text-center">
                        Employee Performance Sumary
                    </h3>
                    <p id="month_sumary" class="text-center mb-4"></p>
                    <div id="employers-table"></div>
                </div>

                <div x-data="stockAlert()" class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-4 ">
                    <h3 class="text-gray-800 dark:text-gray-100 font-semibold mb-0">Monthly Expenses Vs Income
                        Transactions</h3>
                    <div id="transactions-bar-chart" class="w-full"
                        style="min-height: 200px; height: 350px; max-height: 50vw;">
                    </div>
                    <div class="flex justify-center space-x-6 mt-4  mb-4 items-center">
                        <div class="flex items-center space-x-2">
                            <span class="w-4 h-4 rounded-full bg-blue-500 block"></span>
                            <span class="text-gray-700 text-sm">Income</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-4 h-4 rounded-full bg-red-500 block"></span>
                            <span class="text-gray-700 text-sm">Expense</span>
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

    function renderTable(data) {
        let rows = '';
        data.forEach(trx => {
            rows += `
            <tr class="hover:bg-[#a698ff]">
                <td class="px-4 py-2">${formatDate(trx.created_at)}</td>
                <td class="px-4 py-2">${trx.transaction_id}</td>
                <td class="px-4 py-2">${trx.service_description ?? '-'}</td>
                <td class="px-4 py-2">${trx.employee.first_name} ${trx.employee.last_name}</td>
                <td class="px-4 py-2">${trx.payment_method}</td>
                <td class="px-4 py-2">${Number(trx.amount).toLocaleString()}</td>
            </tr>
        `;
        });
        document.getElementById('transactions-body').innerHTML = rows;
    }


    function showCategory(type) {
        // Render the right table data
        if (type === 'income') {
            renderTable(incomeTransactions);
            $('#recent_transactions').text('Most Recent Income Transactions')

        } else {
            renderTable(expenseTransactions);
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
        url: "{{ route('chart.income.expenses') }}",
        method: "GET",
        dataType: "json",
        success: function(data) {
            new Morris.Bar({
                element: 'transactions-bar-chart',
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
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employer</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    `;

            data.forEach(function(item) {
                tableHtml += `
                    <tr class=" hover:bg-[#9c8dfc]">
                        <td class="px-4 py-2">${item.label}</td>
                        <td class="px-4 py-2">${Number(item.value).toLocaleString()}</td>
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
