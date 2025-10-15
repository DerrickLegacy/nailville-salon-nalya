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
                    <?php echo $report_type; ?> Report</h1>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mb-6">
            <h5 class="font-semibold dark:text-white my-2 text-2xl text-center">
                <?php echo $report_type; ?> Performance Report</h5>
            <h5 id="report_period" class="font-semibold dark:text-white my-2 text-1xl text-center">
                <?php echo $report_type; ?> Performance Report</h5>

            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Filters:</h2>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div x-data="{ active: 'Today' }" class="flex flex-wrap items-center gap-2">
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
                <div class="flex items-center gap-2">
                    <label for="datepicker"
                        class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:inline">Date
                        Range:</label>
                    <x-datepicker />
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <select name="employee_id" id="employee_id"
                        class="block w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <option value="">-- Select Employee --</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee['id'] }}">{{ $employee['name'] }}</option>
                        @endforeach
                    </select>

                    {{-- Filter Submit Button --}}
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
                        <!-- Main Content Column -->
                        <div class="flex-1 lg:w-2/3">

                            <!-- Selected Employer Section -->
                            <div id="employee_table_wrapper" class="mb-6 hidden">
                                <h5 id="employee_table_heading" class="text-xl font-semibold dark:text-white mb-4">
                                    Selected Employer
                                </h5>

                                <!-- Cards Row -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                                    <!-- Total Income Card -->
                                    <div
                                        class="bg-white dark:bg-[#8200DB] border border-[#c180ed] dark:border-[#9b4dff] rounded-lg shadow-md p-4 flex items-center space-x-4 transition-transform transform hover:scale-105">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('images/profit_7107544.png') }}" alt="Profit Icon"
                                                class="w-12 h-12 md:w-16 md:h-16 object-contain">
                                        </div>
                                        <div class="flex-1 text-center sm:text-left">
                                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                                                Total {{ $report_type }}</h3>
                                            <p id="total_income_card"
                                                class="text-xl font-bold text-gray-900 dark:text-white"></p>
                                        </div>
                                    </div>

                                    <!-- Employee Contribution Card -->
                                    <div
                                        class="bg-white dark:bg-[#8200DB] border border-[#c180ed] dark:border-[#9b4dff] rounded-lg shadow-md p-4 flex items-center space-x-4 transition-transform transform hover:scale-105">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('images/earning_16136294.png') }}"
                                                alt="Contribution Icon"
                                                class="w-12 h-12 md:w-16 md:h-16 object-contain">
                                        </div>
                                        <div class="flex-1 text-center sm:text-left">
                                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">
                                                Employee Contribution</h3>
                                            <p class="text-xl font-bold text-gray-900 dark:text-white"><span
                                                    id="total_emp_contribution_card"></span>%</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employee Info Table -->
                                <div class="overflow-x-auto rounded-lg shadow ring-1 ring-black/5">
                                    <table
                                        class="min-w-full bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Attribute</th>
                                                <th
                                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Value</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            <tr>
                                                <td class="px-6 py-3">Name</td>
                                                <td class="px-6 py-3 text-right" id="emp_name"></td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-3">Expertise Section</td>
                                                <td class="px-6 py-3 text-right" id="emp_expertise"></td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-3">Total Transactions</td>
                                                <td class="px-6 py-3 text-right" id="transactions_registered"></td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-3">Rank</td>
                                                <td class="px-6 py-3 text-right" id="rank_position"></td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-3">Contribution Per Total</td>
                                                <td class="px-6 py-3 text-right" id="contri_per_total"></td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <td class="px-6 py-3 font-semibold">Total</td>
                                                <td class="px-6 py-3 text-right font-semibold" id="emp_total_income">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- Performance by Service Section -->
                            <h5 class="text-xl font-semibold dark:text-white my-6">Performance By Service
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
                                            placeholder="Search service"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                    </div>
                                </form>
                            </div>
                            <div class="overflow-x-auto rounded-lg shadow-md mb-6">
                                <table id="service_table"
                                    class="min-w-full bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Service</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Total Income (UGX)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <td class="px-6 py-3 font-semibold text-gray-800 dark:text-gray-200">Total
                                            </td>
                                            <td
                                                class="px-6 py-3 slot font-semibold text-right text-gray-800 dark:text-gray-200">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <h5 class="text-xl font-semibold dark:text-white my-6">Performance Within <span
                                    class="heading">Today</span>'s Time Period</h5>
                            <div class="max-h-96 overflow-y-auto shadow ring-1 ring-black/5 rounded-lg mb-6">
                                <table id="income_table"
                                    class="min-w-full bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Date/Month</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                <?php echo $report_type; ?> (UGX)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <td class="px-6 py-3 font-semibold text-gray-800 dark:text-gray-200">Total
                                            </td>
                                            <td
                                                class="slot px-6 py-3 font-semibold text-right text-gray-800 dark:text-gray-200">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <h5 class="text-xl font-semibold dark:text-white my-6">Employer Income Trend Within <span
                                    class="heading">Today</span>'s Time Period</h5>
                            <div class="max-h-96 overflow-y-auto shadow ring-1 ring-black/5 rounded-lg mb-6">
                                <table id="employer-table"
                                    class="min-w-full text-sm text-left rtl:text-right bg-white dark:bg-gray-800 rounded-lg shadow-md">
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2">Employee</th>
                                            <th class="px- py-2">Invoices</th>
                                            <th class="px-6 py-2 text-right ">Total <?php echo $report_type; ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="px-6 py-3 font-semibold text-gray-800 dark:text-gray-200">Total
                                            </td>
                                            <td></td>
                                            <td
                                                class="slot px-6 py-3 font-semibold text-right text-gray-800 dark:text-gray-200">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div
                        class="lg:w-1/3 flex flex-col gap-6 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-gray-700 p-4">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700 transition-all">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 text-center mb-6">💼
                                Business <?php echo $report_type; ?> Goals</h3>

                            <div class="text-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                <span class="heading">Today</span>

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
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3 text-center">
                                Service
                                Breakdown As Of
                                <p class="heading">Today</p>
                            </h3>
                            <div id="todays-income-chart-progress" class="w-full h-64"></div>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 border border-gray-100 dark:border-gray-700 transition-all">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3 text-center">

                                <?php echo $report_type; ?> Summary
                                <p class="heading">Today</p>
                            </h3>
                            <div id="todays-income-performance-progress" class="w-full h-64"></div>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 border border-gray-100 dark:border-gray-700 transition-all">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3 text-center">Income
                                Trend</h3>
                            <p class="heading text-center">Today</p>
                            <div id="income-chart" class="w-full h-64"></div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden"id="report_type" name="report_type" value="<?php echo $report_type; ?>">
        </div>
        <script>
            $(document).ready(function() {
                var report_type = $('#report_type').val();
                var total_Income_recorded = 0;
                var employer_contribution = 0;
                report_period = document.getElementById('report_period')
                report_period.textContent = `Today's Report`;
                loadIncomeData('Today');
                fetchEmployerContribution();

                $('#simple-search').on('input', function() {
                    const query = $(this).val().toLowerCase();
                    $('table tbody tr').each(function() {
                        const service = $(this).find('td:first').text().toLowerCase();
                        if (service.includes(query)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });

                $('.solid-filter-btns').on('click', function() {
                    const range = $(this).text().trim();
                    $('.heading').text(range ? range : 'Today');
                    report_period.textContent = `${range}'s Report`;
                    loadIncomeData(range);
                    fetchEmployerContribution(range);
                });

                $('.filter-btn').on('click', function() {
                    let startDate = '';
                    let endDate = '';
                    const range = $(this).text().trim();
                    const employee_id = $('#employee_id').val();
                    const ww = document.getElementById('dateSelect').value.split('-').map(d => d.trim());

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
                    loadIncomeData(range, startDate, endDate, employee_id);
                    fetchEmployerContribution('Filter', employee_id, startDate, endDate);
                });

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

                function fetchEmployerContribution(range = 'Today', employee_id = null, start_date = null, end_date =
                    null) {
                    $.ajax({
                        url: "{{ route('reports.employer.performance') }}",
                        method: 'GET',
                        data: {
                            range: range,
                            employee_id: employee_id,
                            start_date: start_date,
                            end_date: end_date,
                            report_type: report_type
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Update range label
                            $('#range-label').text(response.range_label);

                            // Clear previous table rows
                            const tbody = $('#employer-table tbody');
                            tbody.empty();
                            let totalIncomeSum = 0; // Initialize total

                            response.data.forEach(function(row) {
                                // Convert string with commas to a number
                                const income = parseFloat(row.totalIncome.toString().replace(/,/g,
                                    ''));

                                // Add to running total
                                totalIncomeSum += income;

                                // Append row
                                const tr = `<tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="px-4 py-2">${row.Employee}</td>
                                    <td class="px-4 py-2">${row.Invoices}</td>
                                    <td class="px-6 py-2 text-right">${income.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}</td>
                                </tr>`;

                                tbody.append(tr);
                            });

                            // Append total to table footer
                            const formattedTotal = totalIncomeSum.toLocaleString('en-US', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 2
                            });
                            $('#employer-table tfoot tr td:last-child').text(formattedTotal);


                        },
                        error: function(xhr, status, error) {
                            alert('Error fetching employer contributions:', error);
                            return
                        }
                    });
                }

                function loadIncomeData(range, start_date = null, end_date = null, employee_id = null) {
                    $.ajax({
                        url: "{{ route('reports.data') }}",
                        method: "GET",
                        data: {
                            range: range,
                            start_date: start_date,
                            end_date: end_date,
                            employee_id: employee_id,
                            report_type: report_type
                        },

                        dataType: "json",
                        success: function(data) {
                            employeData = data.selectedEmpData;
                            if (employeData && employeData.employee_id) {
                                $('#employee_table_div').removeClass('hidden');
                                $('#employee_table_heading').removeClass('hidden');
                                document.getElementById('emp_name').textContent = employeData.name;
                                document.getElementById('rank_position').textContent = employeData.rank;
                                document.getElementById('emp_expertise').textContent = employeData
                                    .expertise;
                                document.getElementById('transactions_registered').textContent = employeData
                                    .performance_positions;
                                document.getElementById('emp_total_income').textContent = employeData
                                    .total_income
                                    .toLocaleString();
                                employer_contribution = employeData.total_income;
                            }
                            const income_tbody = $('#income_table tbody');
                            income_tbody.empty();
                            let total_income = 0;
                            const groupedData = Object.entries(data.grouped_by_period).map(([label,
                                value
                            ]) => ({
                                label,
                                value
                            }));
                            incomeChartData = groupedData;
                            groupedData.sort((a, b) => new Date(a.label) - new Date(b.label));
                            groupedData.forEach(item => {
                                total_income += item.value;
                                income_tbody.append(`
                                <tr>
                                    <td class="px-6 py-3">${item.label}</td>
                                    <td class="text-right px-6 py-3">${item.value.toLocaleString()}</td>
                                </tr>
                            `);
                            });
                            $('#income_table tfoot td:last').text(total_income.toLocaleString());
                            rollSlots(total_income, 'total_income_card', 1500, 100);
                            rollSlots(data
                                .expected_income_target, 'expected_income', 2000, 100);
                            rollSlots(total_income, 'achieved_income', 2000, 100);
                            const percentageAchievement = data.expected_income_target === 0 ?
                                0 :
                                Math.floor((total_income / data.expected_income_target) *
                                    100);

                            rollSlots(percentageAchievement, 'percentage_improvement', 2000, 100);


                            total_Income_recorded = total_income;
                            const chartData = Object.entries(data.grouped).map(([label, value]) => ({
                                label: label,
                                value: value
                            }));

                            $('#todays-income-chart-progress').empty();
                            $('#todays-income-performance-progress').empty();
                            $('#todays-income-chart-progress').empty();
                            $('#income-chart').empty();
                            Morris.Donut({
                                element: 'todays-income-performance-progress',
                                data: [{
                                        label: 'Achieved',
                                        value: percentageAchievement
                                    },
                                    {
                                        label: 'Remaining',
                                        value: 100 - percentageAchievement < 0 ? 0 : 100 -
                                            percentageAchievement
                                    }
                                ],
                                colors: ['#CC0066', '#E5E5E5'],
                                resize: true,
                                redraw: true,
                                formatter: function(y, data) {
                                    return y.toFixed(2) + '%';
                                }
                            });

                            // Morris.js line chart
                            new Morris.Line({
                                element: 'income-chart',
                                data: groupedData.map(d => ({
                                    date: d.label,
                                    income: d.value
                                })),
                                xkey: 'date',
                                ykeys: ['income'],
                                labels: ['Income'],
                                lineColors: ['#1e88e5'],
                                parseTime: false,
                                resize: true
                            });

                            Morris.Donut({
                                element: 'todays-income-chart-progress',
                                data: chartData,
                                colors: ['#8200DB', '#D90082', '#00DB82', '#DB8200', '#0066CC',
                                    '#CC0066', '#00CC66', '#CC6600', '#6600CC', '#CC0066',
                                    '#00CCCC', '#CCCC00'
                                ],
                                resize: true,
                                redraw: true
                            });

                            const tbody = $('#service_table tbody');
                            tbody.empty();
                            let total = 0;
                            chartData.forEach(item => {
                                total += item.value;
                                tbody.append(`
                                    <tr>
                                        <td class="px-6 py-3">${item.label}</td>
                                        <td class="text-right px-6 py-3">${item.value.toLocaleString()}</td>
                                    </tr>
                                `);
                            });
                            $('#service_table tfoot td:last').text(total.toLocaleString());
                            employer_contribution_percentage = employer_contribution /
                                total_Income_recorded * 100
                            rollSlots(employer_contribution / total_Income_recorded * 100,
                                'total_emp_contribution_card', 1500, 100);
                            document.getElementById('contri_per_total').textContent = Number(
                                employer_contribution_percentage).toFixed(2);
                        },
                        error: function(xhr, status, error) {
                            alert("Error loading chart data:", error);
                            return;
                        }
                    });
                }
            });
        </script>
</x-app-layout>
