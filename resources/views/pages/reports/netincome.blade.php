<x-app-layout title="Income Report">
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="mb-2 flex flex-col md:flex-row md:justify-between md:items-center fade-in">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li><a href="#" class="text-gray-500 hover:text-blue-600">Reports</a></li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-2">›</span>
                            <a href="" class="text-gray-500 hover:text-blue-600">
                                <?php echo $report_type; ?>
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-2 fade-in">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl font-bold text-gray-900">
                    <?php echo $report_type; ?> Report
                </h1>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mb-6">
            <h5 class="font-semibold dark:text-white my-2 text-2xl text-center">
                <?php echo $report_type; ?> Performance Report</h5>

            <h5 id="report_period" class="font-semibold dark:text-white my-2 text-1xl text-center">
                <span class="heading">Today</span>'s <?php echo $report_type; ?> Performance Report
            </h5>
            <p class="text-center">
                <small>"Let's Talk About Businness Performance After Deducting Expenses"</small>
            </p>

            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Filters:</h2>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div id="simple_custom_filter" x-data="{ active: 'Today' }" class="flex flex-wrap items-center gap-2">
                    @foreach (['Today', 'This Week', 'This Month', 'This Year'] as $label)
                    <button type="button" @click="active = '{{ $label }}'"
                        :class="active === '{{ $label }}'
                                ?
                                'bg-[#8200DB] text-white border-[#8200DB]' :
                                'border border-[#8200DB] text-[#8200DB] hover:bg-purple-500 hover:text-white'"
                        class="solid-filter-btns px-4 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-150">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <div class="flex items-center gap-2">
                        <select id="month_filter"
                            class="border border-[#8200DB] text-[#8200DB] px-6 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-50 w-full sm:w-auto">
                            <option>--Filter By Month--</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <select id="year_filter"
                            class="border border-[#8200DB] text-[#8200DB] px-6 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-50 w-full sm:w-auto">
                            <option>--Filter By Year--</option>
                            @for ($year = date('Y'); $year >= 2000; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <div class="flex items-center gap-2">
                        <label for="datepicker"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:inline">
                            Custom Range:
                        </label>
                        <x-datepicker />
                    </div>
                    <button type="submit"
                        class="filter-btn btn px-2.5 bg-white dark:bg-purple-800 border-purple-400 hover:border-purple-400 hover:bg-purple-400  dark:border-purple-700/60 dark:hover:border-purple-600 text-purple-400 dark:text-purple-500"
                        aria-haspopup="true" @click.prevent="open = !open">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                            class="h-4 w-4 mr-2 text-black border-black" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0
                         01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-black">Filter</span>
                    </button>
                </div>
            </div>

            <div class="mt-10"> {{-- <hr> --}}
                <div id="todays-income-container" class="flex flex-col lg:flex-row gap-6">
                    <!-- Left Column -->
                    <div class="flex flex-col lg:flex-row lg:space-x-6 w-full">
                        <div class="flex-1 lg:w-2/3">
                            <h5 class="text-xl font-semibold dark:text-white my-6">Tabular Net Income
                                <span class="heading">Today</span>
                            </h5>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-4">
                                <form class="flex w-full sm:w-1/2">
                                    <label for="service-search" class="sr-only">Search</label>
                                    <div class="relative w-full">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="text" id="service-search" name="service-search"
                                            placeholder="Search by Date or Value"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                    </div>
                                </form>
                            </div>

                            <div class="overflow-x-auto rounded-lg shadow-md mb-6">
                                <table id="net_income_table"
                                    class="min-w-full bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th> Date </th>
                                            <th> Income </th>
                                            <th> Expense </th>
                                            <th> Net Income (UGX)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                                        <tr class="border-t border-gray-200 dark:border-gray-700">
                                            <td>
                                                <p>Total(Current Page)</p>
                                                <p>Total(All Pages)</p>
                                            </td>
                                            <td
                                                class=" slot font-semibold text-left text-gray-800 dark:text-gray-200">
                                            </td>
                                            <td
                                                class=" slot font-semibold text-left text-gray-800 dark:text-gray-200">
                                            </td>
                                            <td
                                                class=" slot font-semibold text-left text-gray-800 dark:text-gray-200">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <h5 class="text-xl font-semibold dark:text-white my-6">Income Chart Overview <span
                                    class="heading">Today</span>'s Time Period</h5>

                            <div class="shadow ring-1 ring-black/5 rounded-lg mb-6 p-6">
                                <div id="netIncomeChart-div" class="col-md-8">
                                    <h5>Net Income Linear Chart - <span class="heading">Today</span></h5>
                                    <div id="netIncomeChart" style="height: 300px;"></div>
                                </div>

                            </div>
                            <div class="shadow ring-1 ring-black/5 rounded-lg mb-6 p-6">
                                <div class="col-md-8">
                                    <h5>Net Income Bar Chart - <span class="heading">Today</span></h5>
                                    <div id="netIncomeBarChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="lg:w-1/3 flex flex-col gap-6 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-gray-700 p-4">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700 transition-all">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 text-center mb-6">
                                This Month's <?php echo $report_type; ?> Expectations</h3>
                            <p class="text-center">💰</p>
                            <i class="fa-solid fa-chart-area text-danger"></i>


                            <div class="text-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                (<span class="heading">Today</span>)
                            </div>
                            <div class="space-y-3 text-center">
                                <h1 id="monthlyIncomeTarget" class="max-w-lg text-3xl font-semibold leading-loose  text-[#8200DB] dark:text-white"></h1>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700 transition-all">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 text-center mb-6">
                                Business <?php echo $report_type; ?> Current Achievements</h3>
                            <p class="text-center">💼</p>

                            <div class="text-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                (<span class="heading">Today</span>)
                            </div>
                            <div class="space-y-3">
                                <div
                                    class="flex items-center justify-between bg-purple-50 dark:bg-purple-900/30 rounded-xl px-4 py-3">
                                    <div class="text-gray-700 dark:text-gray-300 font-medium">Projected
                                        <?php echo $report_type; ?>
                                    </div>
                                    <div id="expected_income"
                                        class="text-gray-900 dark:text-white font-semibold text-lg"></div>
                                </div>

                                <div
                                    class="flex items-center justify-between bg-purple-50 dark:bg-purple-900/30 rounded-xl px-4 py-3">
                                    <div class="text-gray-700 dark:text-gray-300 font-medium">Achieved
                                        <?php echo $report_type; ?>
                                    </div>
                                    <div id="achieved_income"
                                        class="font-bold text-green-600 dark:text-green-400 text-lg"></div>
                                </div>

                                <div
                                    class="flex items-center justify-between bg-purple-50 dark:bg-purple-900/30 rounded-xl px-4 py-3">
                                    <div class="text-gray-700 dark:text-gray-300 font-medium">Percentage Achievement
                                    </div>
                                    <div class="font-bold text-purple-600 dark:text-purple-400 text-lg">
                                        <span id="percentage_improvement"></span>%
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 border border-gray-100 dark:border-gray-700 transition-all">

                            <div class="col-md-4">
                                <h5>Income vs Expense Donut Chart <span class="heading">Today</span> </h5>
                                <div id="incomeExpenseDonut" style="height: 300px;"></div>
                            </div>
                            <!-- <div id="todays-income-chart-progress" class="w-full h-64"></div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                let selectedPeriod = 'Today';
                let table;
                var service_search = $('#service-search')
                let searchTerm = '';
                var report_period = document.getElementById('report_period');
                let solid_filter_btns = $('.solid-filter-btns');
                let heading = $('.heading');
                let expected_income = $('#expected_income');
                let achieved_income = $('#achieved_income');
                let month_filter = $('#month_filter');
                let year_filter = $('#year_filter');
                let selectedMonth = '';
                let selectedYear = '';

                month_filter.on('change', function() {
                    selectedMonth = $(this).val();
                    if (selectedMonth) {
                        solid_filter_btns.removeClass(
                            'bg-[#8200DB] text-white border-[#8200DB]').addClass(
                            'border border-[#8200DB] text-[#8200DB] hover:bg-purple-500 hover:text-white');
                        selectedPeriod = 'Month Filter'
                    } else {
                        month_filter.prop('selectedIndex', 0);
                        selectedPeriod = 'Today';
                    }
                    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ];
                    const month = monthNames[selectedMonth - 1];
                    heading.text(month ? month : 'Today');
                    report_period.textContent = `Month of ${month}'s Report`;
                    initTable(selectedPeriod, searchTerm, null, null, selectedMonth, selectedYear);
                });

                year_filter.on('change', function() {
                    selectedYear = $(this).val();
                    if (selectedYear) {
                        solid_filter_btns.removeClass(
                            'bg-[#8200DB] text-white border-[#8200DB]').addClass(
                            'border border-[#8200DB] text-[#8200DB] hover:bg-purple-500 hover:text-white');
                        selectedPeriod = 'Month Filter'
                    } else {
                        month_filter.prop('selectedIndex', 0);
                        selectedPeriod = 'Today';
                    }
                    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ];
                    const month = monthNames[selectedMonth - 1];
                    heading.text(month ? month : 'Today');
                    if (selectedMonth && selectedYear) {
                        report_period.textContent = `Month of ${month}'s ${selectedYear} Report`;
                        initTable(selectedPeriod, searchTerm, null, null, selectedMonth, selectedYear);
                    } else if (selectedYear) {
                        report_period.textContent = `Year of ${selectedYear} -Report`;
                        initTable(selectedPeriod, searchTerm, null, null, null, selectedYear);
                    } else {
                        console.warn('Waiting for both month and year to be selected...');
                    }

                });

                service_search.on('input', function() {
                    searchTerm = $(this).val().toLowerCase();
                    initTable(selectedPeriod, searchTerm);
                })

                solid_filter_btns.on('click', function() {
                    selectedPeriod = $(this).text().trim();
                    month_filter.prop('selectedIndex', 0);
                    heading.text(selectedPeriod ? selectedPeriod : 'Today');
                    report_period.textContent = `${selectedPeriod}'s Report`;
                    initTable(selectedPeriod, searchTerm);
                });

                $('.filter-btn').on('click', function() {
                    let startDate = '';
                    let endDate = '';
                    const range = $(this).text().trim();
                    const employee_id = $('#employee_id').val();
                    const ww = document.getElementById('dateSelect').value.split('-').map(d => d.trim());
                    month_filter.prop('selectedIndex', 0);
                    solid_filter_btns.removeClass(
                        'bg-[#8200DB] text-white border-[#8200DB]').addClass(
                        'border border-[#8200DB] text-[#8200DB] hover:bg-purple-500 hover:text-white');

                    if (ww.length === 1) {
                        startDate = ww[0];
                        endDate = ww[0];
                    } else if (ww.length >= 2) {
                        startDate = ww[0];
                        endDate = ww[1];
                    } else {
                        alert('Please select a valid date or range.');
                        return;
                    }
                    $('.heading').text(`${startDate} to ${endDate}`);
                    report_period.textContent = `From: ${startDate} to ${endDate}`;
                    initTable('Custom Range', searchTerm, startDate, endDate);
                });

                function initTable(period = null, searchTerm = null, startDate = null, endDate = null, month = null, year = null) {
                    if (table) {
                        table.destroy();
                    }
                    console.log('Selected Month-Year:', month, year);

                    table = new DataTable('#net_income_table', {
                        responsive: true,
                        destroy: true,
                        processing: true,
                        serverSide: false,
                        searching: false,
                        ajax: {
                            url: "{{ route('reports.net.income.data') }}",
                            data: {
                                selectedPeriod: period,
                                searchTerm: searchTerm,
                                startDate: startDate,
                                endDate: endDate,
                                month: month,
                                year: year
                            },
                            dataSrc: function(response) {
                                // === Universal numeric cleaner ===
                                function cleanNumbersInObject(obj) {
                                    const cleaned = {};

                                    for (const key in obj) {
                                        if (!obj.hasOwnProperty(key)) continue;

                                        const value = obj[key];

                                        // Handle nested arrays/objects
                                        if (typeof value === 'object' && value !== null) {
                                            cleaned[key] = Array.isArray(value) ?
                                                value.map(v => cleanNumbersInObject(v)) :
                                                cleanNumbersInObject(value);
                                        }
                                        // Convert formatted numeric strings -> numbers
                                        else if (typeof value === 'string' && value.match(/^-?[\d,.\s]+$/)) {
                                            cleaned[key] = parseFloat(value.replace(/[, ]/g, '').trim()) || 0;
                                        } else {
                                            cleaned[key] = value;
                                        }
                                    }

                                    return cleaned;
                                }

                                // 🧼 Clean all numbers in backend response
                                response = cleanNumbersInObject(response);

                                // === Display expected income and animate targets ===
                                expected_income = response.expected_income_target;
                                $('#expected_income').text(Number(response.expected_income_target).toLocaleString());
                                rollSlots(response.monthlyNetIncomeTarget, 'monthlyIncomeTarget', 2000, 100);

                                if (response.data.length !== 0) {
                                    console.log('Data received for charts:', response.data);

                                    // === Prepare chart data ===
                                    const chartData = response.data.map(item => ({
                                        y: item.period,
                                        income: item.income,
                                        expense: item.expense,
                                        net_income: item.net_income
                                    }));

                                    // === Reset chart containers before re-render ===
                                    $('#netIncomeChart').empty();
                                    $('#netIncomeBarChart').empty();
                                    $('#incomeExpenseDonut').empty();

                                    // === Line Chart ===
                                    Morris.Line({
                                        element: 'netIncomeChart',
                                        data: chartData,
                                        xkey: 'y',
                                        ykeys: ['income', 'expense', 'net_income'],
                                        labels: ['Income', 'Expense', 'Net Income'],
                                        lineColors: ['#28a745', '#dc3545', '#007bff'],
                                        hideHover: 'auto',
                                        resize: true,
                                        parseTime: false
                                    });

                                    // === Bar Chart ===
                                    Morris.Bar({
                                        element: 'netIncomeBarChart',
                                        data: chartData,
                                        xkey: 'y',
                                        ykeys: ['income', 'expense', 'net_income'],
                                        labels: ['Income', 'Expense', 'Net Income'],
                                        barColors: ['#28a745', '#dc3545', '#007bff'],
                                        hideHover: 'auto',
                                        resize: true,
                                        gridLineColor: '#eef0f2',
                                        stacked: false
                                    });

                                    // === Donut Chart ===
                                    const totalIncome = chartData.reduce((sum, d) => sum + d.income, 0);
                                    const totalExpense = chartData.reduce((sum, d) => sum + d.expense, 0);
                                    const totalNet = chartData.reduce((sum, d) => sum + d.net_income, 0);

                                    Morris.Donut({
                                        element: 'incomeExpenseDonut',
                                        data: [{
                                                label: 'Total Income',
                                                value: totalIncome
                                            },
                                            {
                                                label: 'Total Expense',
                                                value: totalExpense
                                            },
                                            {
                                                label: 'Net Income',
                                                value: totalNet
                                            }
                                        ],
                                        colors: ['#28a745', '#dc3545', '#007bff'],
                                        resize: true
                                    });
                                } else {
                                    $('#netIncomeChart').empty();
                                    $('#netIncomeBarChart').empty();
                                    $('#incomeExpenseDonut').empty();
                                    Swal.fire({
                                        title: 'No Data Available',
                                        text: 'No data available for the selected time periods.',
                                        icon: 'info',
                                        confirmButtonText: 'Ok'
                                    });
                                }
                                return response.data;
                            },
                        },
                        columns: [{
                                data: 'period'
                            },
                            {
                                data: 'income',
                                render: function(data) {
                                    return Number(data).toLocaleString();
                                },
                            },
                            {
                                data: 'expense',
                                render: function(data) {
                                    return Number(data).toLocaleString();
                                },
                            },
                            {
                                data: 'net_income',
                                render: function(data) {
                                    return Number(data).toLocaleString();
                                },
                            },
                        ],
                        order: [
                            [0, 'desc']
                        ],

                        footerCallback: function(row, data, start, end, display) {
                            const api = this.api();

                            // Helper to parse numbers (strip commas)
                            const intVal = i =>
                                typeof i === 'string' ?
                                parseFloat(i.replace(/[,]/g, '')) || 0 :
                                typeof i === 'number' ?
                                i :
                                0;

                            // ============================
                            // 1️⃣ Grand totals (ALL pages)
                            // ============================
                            const grandIncome = api
                                .column(1)
                                .data()
                                .reduce((a, b) => a + intVal(b), 0);

                            const grandExpense = api
                                .column(2)
                                .data()
                                .reduce((a, b) => a + intVal(b), 0);

                            const grandNet = api
                                .column(3)
                                .data()
                                .reduce((a, b) => a + intVal(b), 0);

                            // ============================
                            // 2️⃣ Page totals (CURRENT page)
                            // ============================
                            const pageIncome = api
                                .column(1, {
                                    page: 'current'
                                })
                                .data()
                                .reduce((a, b) => a + intVal(b), 0);

                            const pageExpense = api
                                .column(2, {
                                    page: 'current'
                                })
                                .data()
                                .reduce((a, b) => a + intVal(b), 0);

                            const pageNet = api
                                .column(3, {
                                    page: 'current'
                                })
                                .data()
                                .reduce((a, b) => a + intVal(b), 0);

                            // ============================
                            // 3️⃣ Optional extra calculations
                            // ============================
                            const percentage = expected_income ?
                                ((grandNet / expected_income) * 100).toFixed(2) :
                                0;

                            $('#percentage_improvement').text(Number(percentage).toLocaleString());
                            achieved_income.text(grandNet.toLocaleString());

                            // ============================
                            // 4️⃣ Update footer cells
                            // ============================
                            $(api.column(1).footer()).html(
                                `${pageIncome.toLocaleString()} <br><small class="text-muted">(${grandIncome.toLocaleString()})</small>`
                            );
                            $(api.column(2).footer()).html(
                                `${pageExpense.toLocaleString()} <br><small class="text-muted">(${grandExpense.toLocaleString()} )</small>`
                            );
                            $(api.column(3).footer()).html(
                                `${pageNet.toLocaleString()} <br><small class="text-muted">(${grandNet.toLocaleString()} )</small>`
                            );
                        }

                    });
                }

                function rollSlots(amount, element, duration = 2000, intervalTime = 50) {
                    const slots = document.querySelectorAll('#' + element);
                    slots.forEach(slot => {
                        const finalValue = Math.floor(amount) || 0;
                        let elapsed = 0;

                        const interval = setInterval(() => {
                            const randomValue = Math.floor(Math.random() * (finalValue + 1));
                            slot.textContent = randomValue.toLocaleString();

                            elapsed += intervalTime;

                            if (elapsed >= duration) {
                                clearInterval(interval);
                                slot.textContent = finalValue
                                    .toLocaleString();
                            }
                        }, intervalTime);
                    });
                }
                initTable(selectedPeriod);
            });
        </script>

</x-app-layout>