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

        <style>
            .rotate-90 {
                transform: rotate(90deg);
            }

            .accordion-icon {
                transition: transform 0.2s ease-in-out;
                display: inline-block;
            }
        </style>


        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-2 fade-in">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100"> <?php echo $report_type; ?> Report</h1>
                @if($report_type=='Income')
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Analyze your salon's income performance and make informed business decisions.</p>
                @else

                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Get to know yourday-to-day expense salon insights.</p>
                @endif
            </div>
        </div>

        @if($report_type=='Income')
        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-[#8200DB] p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-[#8200DB] mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm  text-[#8200DB] dark:text-blue-300">
                        <strong>Note:</strong> This is a report focuses on only business income. For expenditure, go to <span>
                            <a href="{{ route('reports.expense') }}" class="text-[#8200DB] dark:text-blue-300 hover:underline italic font-bold">expense report</a>
                        </span>.
                    </p>
                </div>
            </div>
        </div>
        @else

        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-[#8200DB] p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm text-[#8200DB] dark:text-blue-300">
                        <strong>Note:</strong> This is a report focuses on only business expenses. For income, go to <span>
                            <a href="{{ route('reports.income') }}" class="text-[#8200DB] dark:text-blue-300 hover:underline italic font-bold">income report</a>
                        </span>.
                    </p>
                </div>
            </div>
        </div>
        @endif
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mb-6">
            <h5 class="font-semibold dark:text-white my-2 text-2xl text-center">
                <?php echo $report_type; ?> Performance Report</h5>
            <h5 id="report_period" class="font-semibold dark:text-white my-2 text-1xl text-center">
                <?php echo $report_type; ?> Performance Report</h5>
            <p class="text-center">
                <small>"Let's Talk Only About <?php echo $report_type; ?>"</small>
            </p>

            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Filters:</h2>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div
                    x-data="{ active: 'Today' }"
                    class="flex flex-row flex-nowrap items-center gap-2
               overflow-x-auto sm:overflow-visible py-3">

                    @foreach (['Today', 'This Week', 'This Month', 'This Year', 'All Time'] as $label)
                    <button
                        type="button"
                        @click="active = '{{ $label }}'"
                        :class="active === '{{ $label }}'
                    ? 'bg-[#8200DB] text-white border-[#8200DB]'
                    : 'border border-[#8200DB] text-[#8200DB] hover:bg-[#8200DB] hover:text-white'"
                        class="solid-filter-btns
                       whitespace-nowrap
                       rounded-md
                       border
                       px-3 py-1.5
                       text-xs sm:text-sm lg:text-base
                       font-medium
                       transition
                       focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>

                <!-- RIGHT: Date + Employee + Filter -->
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end w-full">

                    <!-- Datepicker -->
                    <div class="relative w-full sm:w-1/3">
                        <input
                            type="text"
                            class="datepicker form-input pl-10 w-full
                                dark:bg-[#8200DB] text-[#8200DB]
                                border border-purple-400 rounded-lg
                                hover:text-[#8200DB] hover:bg-purple-200
                                dark:text-gray-300 dark:hover:text-[#8200DB]
                                font-medium
                                text-xs sm:text-sm lg:text-base
                                focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                            placeholder="Select date range"
                            id="dateSelect"
                            name="dateSelect"
                            data-class="flatpickr-right" />
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="fill-current text-gray-400 dark:text-gray-500 w-4 h-4" viewBox="0 0 16 16">
                                <path d="M5 4a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2H5Z" />
                                <path d="M4 0a4 4 0 0 0-4 4v8a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4H4ZM2 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4Z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Employee Select -->
                    <select
                        name="employee_id"
                        id="employee_id"
                        class="w-full sm:w-48
                            rounded-lg border border-[#8200DB]
                            bg-white dark:bg-gray-700
                            text-xs sm:text-sm lg:text-base
                            text-[#8200DB] dark:text-[#8200DB]
                            shadow-sm
                            focus:ring-[#8200DB] focus:border-[#8200DB]
                            transition">
                        <option value="">Select Employee</option>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee['id'] }}">{{ $employee['name'] }}</option>
                        @endforeach
                    </select>

                    <!-- Filter Button -->
                    <button
                        type="submit"
                        class="filter-btn inline-flex items-center justify-center gap-2
                            w-full sm:w-auto
                            px-4 py-2 rounded-lg
                            border border-[#8200DB]
                            text-xs sm:text-sm lg:text-base
                            font-medium 
                            text-[#8200DB] bg-white
                            hover:bg-[#8200DB] hover:text-white
                            transition" @click.prevent="open = !open">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filter
                    </button>
                </div>
            </div>

            <input type="hidden" id="report_type" name="report_type" value="<?php echo $report_type; ?>">
        </div>
        <div class="mt-10">
            <div id="todays-income-container" class="flex flex-col lg:flex-row gap-6">
                <!-- Left Column -->
                <div class="flex flex-col lg:flex-row lg:space-x-6 w-full">
                    <div class="flex-1 lg:w-2/3">
                        <div id="employee_table_wrapper" class="mb-6 hidden">
                            <h5 id="employee_table_heading" class="text-xl font-semibold dark:text-white mb-4">
                                Selected Employer <?php echo $report_type; ?> Details
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
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
                                            class="text-xl font-bold text-gray-900 dark:text-white">Shs. 0</p>
                                    </div>
                                </div>

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
                                        </tr>Employee Contr
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
                                    <tfoot class="bg-gray-50 dark:bg-gray-700  text-[#8200DB]">
                                        <tr>
                                            <td class="px-6 py-3 font-semibold">Total</td>
                                            <td class="px-6 py-3 text-right font-semibold  text-[#8200DB]" id="emp_total_income">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mb-6">
                            <h5 class="text-xl font-semibold dark:text-white my-6">Performance By Service
                                <span class="heading">Today</span>
                            </h5>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-4">
                                <form class="flex w-full sm:w-1/2">
                                    <label for="service-search" class="sr-only">Search</label>

                                    <div class="relative w-full">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true"
                                                class="w-5 h-5 text-[#8200DB] dark:text-gray-400"
                                                fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>

                                        <input
                                            type="text"
                                            id="service-search"
                                            name="service-search"
                                            placeholder="Search for a service..."
                                            class="bg-gray-50 border border-[#8200DB] text-[#8200DB]
                   text-sm rounded-lg
                   focus:ring-[#8200DB] focus:border-[#8200DB]
                   block w-full pl-10 p-2
                   dark:bg-gray-700 dark:border-[#8200DB]
                   dark:placeholder-[#8200DB] dark:text-white">
                                    </div>
                                </form>

                            </div>

                            <div class="mt-4 flex items-center mb-3">
                                <label for="categorise_services" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Group by Sections (Barbers Team, Hairdressers and Nailist Teams.)
                                </label>
                                <input
                                    type="checkbox"
                                    id="categorise_services"
                                    name="categorise_services"
                                    class="h-4 w-4 text-[#8200DB] focus:ring-[#8200DB] ring-[#8200DB] border-[#8200DB]  rounded">

                            </div>

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
                                        <td class="px-6 py-3 font-semibold dark:text-gray-200  text-[#8200DB]">Total
                                        </td>
                                        <td
                                            class="px-6 py-3 slot font-semibold text-right  dark:text-gray-200  text-[#8200DB]">
                                        </td>
                                    </tr>
                                </tfoot>`
                            </table>

                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mb-6">

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
                                            <td class="px-6 py-3 font-semibold text-[#8200DB] dark:text-gray-200">Total
                                            </td>
                                            <td
                                                class="slot px-6 py-3 font-semibold text-right text-[#8200DB] dark:text-gray-200">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mb-6">
                            <h5 class="text-xl font-semibold dark:text-white my-6">Employer Income Trend Within
                                <span class="heading">Today</span>'s Time Period
                            </h5>
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
                                            <td class="px-6 py-3 font-semibold text-[#8200DB] dark:text-gray-200">Total
                                            </td>
                                            <td></td>
                                            <td
                                                class="slot px-6 py-3 font-semibold text-right text-[#8200DB] dark:text-gray-200">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="lg:w-1/3 flex flex-col gap-6 px-0  ">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-100 dark:border-gray-700 transition-all">
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
                                    <span id="percentage_improvement"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border border-gray-100 dark:border-gray-700 transition-all">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3 text-center">
                            Service
                            Breakdown As Of
                            <p class="heading">Today</p>
                        </h3>
                        <div id="todays-income-chart-progress" class="w-full h-64"></div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border border-gray-100 dark:border-gray-700 transition-all">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3 text-center">

                            <?php echo $report_type; ?> Summary
                            <p class="heading">Today</p>
                        </h3>
                        <div id="todays-income-performance-progress" class="w-full h-64"></div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border border-gray-100 dark:border-gray-700 transition-all">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3 text-center">Income
                            Trend</h3>
                        <p class="heading text-center">Today</p>
                        <div id="income-chart" class="w-full h-64"></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Global state management
            const ReportManager = {
                state: {
                    mode: 'range', // 'range' | 'custom'
                    range: 'Today',
                    start_date: null,
                    end_date: null,
                    employee_id: null,
                    report_type: $('#report_type').val()
                },

                init() {
                    this.bindEvents();
                    this.loadInitialData();
                },

                bindEvents() {
                    // Range button clicks
                    $('.solid-filter-btns').on('click', (e) => this.handleRangeClick(e));

                    // Filter button click
                    $('.filter-btn').on('click', (e) => this.handleFilterClick(e));

                    // Categorize services checkbox
                    $('#categorise_services').on('change', () => this.handleCategorizeChange());

                    // Service search
                    $('#service-search').on('input', (e) => this.handleServiceSearch(e));

                    // Accordion toggles
                    $(document).on('click', '[data-toggle]', (e) => this.handleAccordionToggle(e));
                },

                loadInitialData() {
                    $('#report_period').text("Today's Report");
                    $('#dateSelect').val('');
                    this.loadData();
                },

                handleRangeClick(e) {
                    const range = $(e.target).text().trim();

                    // Update state
                    this.state.mode = 'range';
                    this.state.range = range;
                    this.state.start_date = null;
                    this.state.end_date = null;
                    this.state.employee_id = $('#employee_id').val();

                    // Update UI
                    this.updateRangeButtonsUI($(e.target));
                    this.clearCustomFilters();
                    this.updateHeadings(range);

                    // Load data
                    this.loadData();
                },

                handleFilterClick(e) {
                    e.preventDefault();

                    const employee_id = $('#employee_id').val();
                    const dateValue = $('#dateSelect').val().trim();

                    if (!dateValue && !employee_id) {
                        this.showAlert('Please select a date range or an employee.');
                        return;
                    }

                    let startDate = null;
                    let endDate = null;

                    if (dateValue) {
                        const parts = dateValue.split('-').map(d => d.trim());
                        startDate = parts[0];
                        endDate = parts[1] ?? parts[0];
                    }

                    // Update state
                    this.state.mode = 'custom';
                    this.state.range = null;
                    this.state.start_date = startDate;
                    this.state.end_date = endDate;
                    this.state.employee_id = employee_id;

                    // Update UI
                    this.deactivateRangeButtons();
                    this.updateCustomFilterHeadings(startDate, endDate, employee_id);

                    // Load data
                    this.loadData();
                },

                handleCategorizeChange() {
                    this.loadData();
                },

                handleServiceSearch(e) {
                    const query = $(e.target).val().toLowerCase();
                    $('#service_table tbody tr').each(function() {
                        const service = $(this).find('td:first').text().toLowerCase();
                        $(this).toggle(service.includes(query));
                    });
                },

                handleAccordionToggle(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const sectionId = $(e.currentTarget).data('toggle');
                    const rows = $(`[data-parent="${sectionId}"]`);
                    const icon = $(e.currentTarget).find('.accordion-icon');

                    rows.toggleClass('hidden');
                    icon.toggleClass('rotate-90');
                },

                updateRangeButtonsUI(activeButton) {
                    $('.solid-filter-btns')
                        .removeClass('bg-[#8200DB] text-white border-[#8200DB]')
                        .addClass('border border-[#8200DB] text-[#8200DB]')
                        .css('opacity', '1');

                    activeButton
                        .removeClass('border border-[#8200DB] text-[#8200DB]')
                        .addClass('bg-[#8200DB] text-white border-[#8200DB]');
                },

                deactivateRangeButtons() {
                    $('.solid-filter-btns')
                        .removeClass('bg-[#8200DB] text-white border-[#8200DB]')
                        .addClass('border border-[#8200DB] text-[#8200DB]')
                        .css('opacity', '0.7');
                },

                clearCustomFilters() {
                    $('#dateSelect').val('');
                },

                updateHeadings(range) {
                    $('.heading').text(range);
                    $('#report_period').text(`${range}'s Report`);
                },

                updateCustomFilterHeadings(startDate, endDate, employee_id) {
                    let headingText = 'Filtered';
                    let reportText = 'Filtered Report';

                    if (startDate && endDate) {
                        headingText = `${startDate} to ${endDate}`;
                        reportText = `From ${startDate} to ${endDate}`;
                    } else if (employee_id) {
                        const employeeName = $('#employee_id option:selected').text();
                        headingText = `Employee: ${employeeName}`;
                        reportText = `Employee Filter: ${employeeName}`;
                    }

                    $('.heading').text(headingText);
                    $('#report_period').text(reportText);
                },

                loadData() {
                    const requestData = this.buildRequestData();

                    // Load main data
                    this.loadIncomeData(requestData);

                    // Load employer contribution
                    this.fetchEmployerContribution(requestData);
                },

                buildRequestData() {
                    const baseData = {
                        report_type: this.state.report_type,
                        categorise_services: $('#categorise_services').is(':checked')
                    };

                    if (this.state.mode === 'range') {
                        baseData.range = this.state.range;
                        if (this.state.employee_id) {
                            baseData.employee_id = this.state.employee_id;
                        }
                    } else if (this.state.mode === 'custom') {
                        baseData.range = 'Filter';
                        if (this.state.start_date) baseData.start_date = this.state.start_date;
                        if (this.state.end_date) baseData.end_date = this.state.end_date;
                        if (this.state.employee_id) baseData.employee_id = this.state.employee_id;
                    }

                    return baseData;
                },

                loadIncomeData(requestData) {
                    $.ajax({
                        url: "{{ route('reports.data') }}",
                        method: "GET",
                        data: requestData,
                        dataType: "json",
                        success: (data) => this.handleIncomeDataSuccess(data),
                        error: (xhr, status, error) => {
                            console.error("Error loading chart data:", error);
                            this.showAlert("Error loading data. Please try again.");
                        }
                    });
                },

                fetchEmployerContribution(requestData) {
                    $.ajax({
                        url: "{{ route('reports.employer.performance') }}",
                        method: 'GET',
                        data: requestData,
                        dataType: 'json',
                        success: (response) => this.handleEmployerContributionSuccess(response),
                        error: (xhr, status, error) => {
                            console.error('Error fetching employer contributions:', error);
                        }
                    });
                },

                handleIncomeDataSuccess(data) {
                    if (!data) return;

                    // Handle employee data
                    this.updateEmployeeData(data.selectedEmpData);

                    // Update income table
                    this.updateIncomeTable(data.grouped_by_period);

                    // Update rolling counters
                    this.updateRollingCounters(data);

                    // Update charts
                    this.updateCharts(data);

                    // Update service table
                    this.updateServiceTable(data.grouped);
                },

                handleEmployerContributionSuccess(response) {
                    const tbody = $('#employer-table tbody');
                    tbody.empty();
                    let totalIncomeSum = 0;

                    response.data.forEach(row => {
                        const income = parseFloat(row.totalIncome.toString().replace(/,/g, ''));
                        totalIncomeSum += income;

                        tbody.append(`
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-2">${row.Employee}</td>
                                <td class="px-4 py-2">${row.Invoices}</td>
                                <td class="px-6 py-2 text-right">${income.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}</td>
                            </tr>
                        `);
                    });

                    const formattedTotal = totalIncomeSum.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 2
                    });
                    $('#employer-table tfoot tr td:last-child').text(formattedTotal);
                },

                updateEmployeeData(employeeData) {
                    if (employeeData && employeeData.employee_id) {
                        $('#employee_table_div, #employee_table_heading, #employee_table_wrapper').removeClass('hidden');

                        $('#emp_name').text(employeeData.name);
                        $('#rank_position').text(employeeData.rank ?? '-');
                        $('#emp_expertise').text(employeeData.expertise);
                        $('#transactions_registered').text(employeeData.performance_positions);
                        $('#emp_total_income').text(Number(employeeData.total_income).toLocaleString());
                    }
                },

                updateIncomeTable(groupedByPeriod) {
                    const tbody = $('#income_table tbody');
                    tbody.empty();
                    let totalIncome = 0;

                    const groupedData = Object.entries(groupedByPeriod).map(([label, value]) => ({
                        label,
                        value: Number(value)
                    }));

                    groupedData.sort((a, b) => new Date(a.label) - new Date(b.label));

                    groupedData.forEach(item => {
                        totalIncome += item.value;
                        tbody.append(`
                            <tr>
                                <td class="px-6 py-3">${item.label}</td>
                                <td class="text-right px-6 py-3">${item.value.toLocaleString()}</td>
                            </tr>
                        `);
                    });

                    $('#income_table tfoot td:last').text(totalIncome.toLocaleString());
                    return {
                        groupedData,
                        totalIncome
                    };
                },

                updateRollingCounters(data) {
                    const {
                        totalIncome
                    } = this.updateIncomeTable(data.grouped_by_period);

                    this.rollSlots(data.expected_income_target, 'expected_income', 800, 100);
                    this.rollSlots(totalIncome, 'achieved_income', 800, 100);

                    if (data.selectedEmpData && data.selectedEmpData.employee_id) {
                        this.rollSlots(totalIncome, 'total_income_card', 800, 100);
                    }

                    // Calculate percentage achievement
                    let percentageAchievement = 0;
                    if (data.expected_income_target > 0 && totalIncome > 0) {
                        percentageAchievement = (totalIncome / data.expected_income_target) * 100;
                    }

                    let percentageAchievementNumeric = Number(percentageAchievement.toFixed(2));
                    if (percentageAchievementNumeric > 0 && percentageAchievementNumeric < 0.1) {
                        percentageAchievementNumeric = 0.1;
                    }

                    this.rollSlots(percentageAchievementNumeric, 'percentage_improvement', 1000, 100);

                    return {
                        totalIncome,
                        percentageAchievementNumeric
                    };
                },

                updateCharts(data) {
                    const {
                        groupedData,
                        totalIncome
                    } = this.updateIncomeTable(data.grouped_by_period);
                    const {
                        percentageAchievementNumeric
                    } = this.updateRollingCounters(data);

                    // Performance donut chart
                    $('#todays-income-performance-progress').empty();
                    const remainingPercentage = Math.max(0, 100 - percentageAchievementNumeric);

                    Morris.Donut({
                        element: 'todays-income-performance-progress',
                        data: [{
                                label: 'Achieved',
                                value: percentageAchievementNumeric
                            },
                            {
                                label: 'Remaining',
                                value: remainingPercentage
                            }
                        ],
                        colors: ['#CC0066', '#E5E5E5'],
                        resize: true,
                        redraw: true,
                        formatter: y => y.toFixed(2) + '%'
                    });

                    // Line chart
                    $('#income-chart').empty();
                    new Morris.Line({
                        element: 'income-chart',
                        data: groupedData.map(d => ({
                            date: d.label,
                            income: d.value
                        })),
                        xkey: 'date',
                        ykeys: ['income'],
                        labels: ['Income'],
                        parseTime: false,
                        resize: true,
                        lineColors: ['#1e88e5']
                    });

                    // Service breakdown chart
                    this.updateServiceChart(data);
                },

                updateServiceChart(data) {
                    let chartData = [];
                    const categoriseServices = $('#categorise_services').is(':checked');

                    if (this.state.report_type.toLowerCase() === 'income') {
                        if (categoriseServices && data.grouped && typeof data.grouped === 'object') {
                            chartData = Object.values(data.grouped)
                                .filter(section => Number(section.total_amount) > 0)
                                .map(section => ({
                                    label: section.section_name || 'Unknown',
                                    value: Number(section.total_amount)
                                }));
                        } else {
                            chartData = Object.values(data.grouped).flatMap(item =>
                                (item.services || [])
                                .filter(s => Number(s.total_amount) > 0)
                                .map(service => ({
                                    label: service.service_name || 'Unknown',
                                    value: Number(service.total_amount)
                                }))
                            );
                        }
                    } else {
                        chartData = Object.entries(data.grouped || {})
                            .filter(([_, item]) => Number(item.total_amount) > 0)
                            .map(([label, item]) => ({
                                label,
                                value: Number(item.total_amount)
                            }));
                    }

                    if (!Array.isArray(chartData) || chartData.length === 0) {
                        $('#todays-income-chart-progress').html('<p class="text-center text-sm text-gray-400">No data available</p>');
                        return;
                    }

                    $('#todays-income-chart-progress').empty();
                    Morris.Donut({
                        element: 'todays-income-chart-progress',
                        data: chartData,
                        colors: ['#8200DB', '#D90082', '#00DB82', '#DB8200', '#0066CC', '#CC0066', '#00CC66', '#CC6600'],
                        resize: true,
                        redraw: true
                    });
                },

                updateServiceTable(grouped) {
                    const tbody = $('#service_table tbody');
                    tbody.empty();
                    let serviceTotal = 0;
                    const categoriseServices = $('#categorise_services').is(':checked');

                    if (categoriseServices && grouped && typeof grouped === 'object') {
                        Object.values(grouped).forEach((section, index) => {
                            serviceTotal += section.total_amount;
                            const sectionId = `section-${index}`;

                            tbody.append(`
                                <tr class="bg-gray-100 dark:bg-gray-700 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                    data-toggle="${sectionId}" title="Click to expand/collapse services">
                                    <td class="px-6 py-3 flex items-center gap-2">
                                        <span class="accordion-icon transition-transform text-[#8200DB] font-bold">▶</span>
                                        <span>${section.section_name}</span>
                                    </td>
                                    <td class="text-right px-6 py-3 text-[#8200DB] font-semibold">
                                        ${section.total_amount.toLocaleString()}
                                    </td>
                                </tr>
                            `);

                            if (section.services && section.services.length > 0) {
                                section.services.forEach(service => {
                                    tbody.append(`
                                        <tr class="hidden text-sm accordion-row" data-parent="${sectionId}">
                                            <td class="px-6 py-2 pl-12 text-gray-600 dark:text-gray-400">
                                                └ ${service.service_name}
                                            </td>
                                            <td class="text-right px-6 py-2 text-gray-600 dark:text-gray-400">
                                                ${service.total_amount.toLocaleString()}
                                            </td>
                                        </tr>
                                    `);
                                });
                            }
                        });
                    } else {
                        Object.values(grouped).forEach(item => {
                            serviceTotal += item.total_amount;
                            tbody.append(`
                                <tr>
                                    <td class="px-6 py-3">${item.services[0].service_name || item.label}</td>
                                    <td class="text-right px-6 py-3">${item.total_amount.toLocaleString()}</td>
                                </tr>
                            `);
                        });
                    }

                    $('#service_table tfoot td:last').text(serviceTotal.toLocaleString());
                },

                rollSlots(amount, element, duration = 2000, intervalTime = 50) {
                    const slots = document.querySelectorAll('#' + element);

                    slots.forEach(slot => {
                        const isPercentage = element.includes('percentage') || element.includes('contri');
                        const decimals = isPercentage ? (amount < 1 ? 2 : 0) : 0;
                        const finalValue = Number(amount) || 0;
                        let elapsed = 0;

                        const interval = setInterval(() => {
                            let randomValue;

                            if (isPercentage) {
                                randomValue = Math.random() * finalValue;
                                slot.textContent = randomValue.toFixed(decimals) + '%';
                            } else {
                                randomValue = Math.floor(Math.random() * (finalValue + 1));
                                slot.textContent = randomValue.toLocaleString();
                            }

                            elapsed += intervalTime;

                            if (elapsed >= duration) {
                                clearInterval(interval);

                                if (isPercentage) {
                                    slot.textContent = finalValue.toFixed(decimals) + '%';
                                } else {
                                    slot.textContent = finalValue.toLocaleString();
                                }
                            }
                        }, intervalTime);
                    });
                },

                showAlert(message) {
                    alert(message);
                }
            };

            // Initialize when document is ready
            $(document).ready(() => {
                ReportManager.init();
            });
        </script>
</x-app-layout>