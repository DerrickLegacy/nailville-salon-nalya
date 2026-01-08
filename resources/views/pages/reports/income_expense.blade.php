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
            $(document).ready(function() {
                var report_type = $('#report_type').val();
                var total_Income_recorded = 0;
                var employer_contribution = 0;
                report_period = document.getElementById('report_period')
                report_period.textContent = `Today's Report`;
                loadIncomeData('Today');
                fetchEmployerContribution();

                $(document).on('click', '[data-toggle]', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const sectionId = $(this).data('toggle');

                    const rows = $(`[data-parent="${sectionId}"]`);
                    const icon = $(this).find('.accordion-icon');

                    rows.toggleClass('hidden');
                    // Rotate arrow
                    icon.toggleClass('rotate-90');
                });

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

                // Add event listener for categorise services checkbox
                $('#categorise_services').on('change', function() {
                    const activeRange = $('.solid-filter-btns.bg-\\[\\#8200DB\\]').text().trim() || 'Today';
                    const employee_id = $('#employee_id').val();

                    // Reload data with current settings
                    loadIncomeData(activeRange, null, null, employee_id);
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

                        // Detect percentage values
                        const isPercentage = element.includes('percentage') || element.includes('contri');

                        // Determine decimals
                        const decimals = isPercentage ?
                            (amount < 1 ? 2 : 0) :
                            0;

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

                                const tr = `<tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="px-4 py-2">${row.Employee}</td>
                                        <td class="px-4 py-2">${row.Invoices}</td>
                                        <td class="px-6 py-2 text-right">${income.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}</td>
                                    </tr>`;

                                tbody.append(tr);
                            });

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
                    const categoriseServices = $('#categorise_services').is(':checked');

                    $.ajax({
                        url: "{{ route('reports.data') }}",
                        method: "GET",
                        data: {
                            range: range,
                            start_date: start_date,
                            end_date: end_date,
                            employee_id: employee_id,
                            report_type: report_type,
                            categorise_services: categoriseServices
                        },

                        dataType: "json",
                        success: function(data) {
                            //   1. EMPLOYEE DATA

                            if (data) {
                                let employeData = data.selectedEmpData;
                                let employer_contribution = 0;

                                if (employeData && employeData.employee_id) {
                                    $('#employee_table_div, #employee_table_heading, #employee_table_wrapper')
                                        .removeClass('hidden');

                                    document.getElementById('emp_name').textContent = employeData.name;
                                    document.getElementById('rank_position').textContent = employeData.rank ?? '-';
                                    document.getElementById('emp_expertise').textContent = employeData.expertise;
                                    document.getElementById('transactions_registered').textContent =
                                        employeData.performance_positions;

                                    document.getElementById('emp_total_income').textContent =
                                        Number(employeData.total_income).toLocaleString();

                                    employer_contribution = Number(employeData.total_income);
                                }

                                /* ===============================
                                 * 2. INCOME TABLE & TOTAL
                                 =============================== */
                                const income_tbody = $('#income_table tbody');
                                income_tbody.empty();

                                let total_income = 0;

                                const groupedData = Object.entries(data.grouped_by_period).map(
                                    ([label, value]) => ({
                                        label,
                                        value: Number(value)
                                    })
                                );

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

                                /* ===============================
                                 * 3. ROLLING COUNTERS
                                 =============================== */
                                rollSlots(data.expected_income_target, 'expected_income', 800, 100);
                                rollSlots(total_income, 'achieved_income', 800, 100);

                                if (employeData && employeData.employee_id) {
                                    rollSlots(total_income, 'total_income_card', 800, 100);
                                }

                                /* ===============================
                                 * 4. PERCENTAGE ACHIEVEMENT (FIXED)
                                 =============================== */
                                let percentageAchievement = 0;

                                if (data.expected_income_target > 0 && total_income > 0) {
                                    percentageAchievement =
                                        (total_income / data.expected_income_target) * 100;
                                }

                                // Display logic
                                let percentageAchievementNumeric =
                                    percentageAchievement < 1 ?
                                    Number(percentageAchievement.toFixed(2)) :
                                    Number(percentageAchievement.toFixed(2));

                                // Optional UX minimum visibility
                                if (percentageAchievementNumeric > 0 && percentageAchievementNumeric < 0.1) {
                                    percentageAchievementNumeric = 0.1;
                                }

                                rollSlots(
                                    percentageAchievementNumeric,
                                    'percentage_improvement',
                                    1000,
                                    100
                                );

                                //  * 5. PERFORMANCE DONUT
                                $('#todays-income-performance-progress').empty();

                                const remainingPercentage = Math.max(
                                    0,
                                    100 - percentageAchievementNumeric
                                );

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

                                /* ===============================
                                 * 6. LINE CHART
                                 =============================== */
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

                                /* ===============================
                                 * 7. SERVICE DONUT
                                 =============================== */
                                let chartData = [];

                                if (report_type.toLowerCase() === 'income') {

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
                                    $('#todays-income-chart-progress')
                                        .html('<p class="text-center text-sm text-gray-400">No data available</p>');
                                    return;
                                } else {


                                    $('#todays-income-chart-progress').empty();
                                    Morris.Donut({
                                        element: 'todays-income-chart-progress',
                                        data: chartData,
                                        colors: [
                                            '#8200DB', '#D90082', '#00DB82', '#DB8200',
                                            '#0066CC', '#CC0066', '#00CC66', '#CC6600'
                                        ],
                                        resize: true,
                                        redraw: true
                                    });
                                }

                                /* ===============================
                                 * 8. SERVICE TABLE
                                 =============================== */
                                const tbody = $('#service_table tbody');
                                tbody.empty();
                                let serviceTotal = 0;

                                if (categoriseServices && data.grouped && typeof data.grouped === 'object') {

                                    Object.values(data.grouped).forEach((section, index) => {
                                        serviceTotal += section.total_amount;

                                        const sectionId = `section-${index}`;

                                        tbody.append(`
                                            <tr 
                                                class="bg-gray-100 dark:bg-gray-700 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                                data-toggle="${sectionId}"
                                                title="Click to expand/collapse services"
                                            >
                                                <td class="px-6 py-3 flex items-center gap-2">
                                                    <span class="accordion-icon transition-transform text-[#8200DB] font-bold">▶</span>
                                                    <span class="">${section.section_name}</span>
                                                </td>
                                                <td class="text-right px-6 py-3 text-[#8200DB] font-semibold">
                                                    ${section.total_amount.toLocaleString()}
                                                </td>
                                            </tr>
                                        `);

                                        // SERVICE ROWS (HIDDEN BY DEFAULT)
                                        if (section.services && section.services.length > 0) {
                                            section.services.forEach(service => {
                                                tbody.append(`
                                                    <tr 
                                                        class="hidden text-sm accordion-row"
                                                        data-parent="${sectionId}"
                                                    >
                                                        <td class="px-6 py-2 pl-12 text-gray-600 dark:text-gray-400">
                                                            └ ${service.service_name}
                                                        </td>
                                                        <td class="text-right px-6 py-2 text-gray-600 dark:text-gray-400 ">
                                                            ${service.total_amount.toLocaleString()}
                                                        </td>
                                                    </tr>
                                                `);
                                            });
                                        }
                                    });
                                } else {

                                    chartData.forEach(item => {
                                        serviceTotal += item.value;
                                        tbody.append(`
                                        <tr>
                                            <td class="px-6 py-3">${item.label}</td>
                                            <td class="text-right px-6 py-3">${item.value.toLocaleString()}</td>
                                        </tr>
                                    `);
                                    });
                                }

                                $('#service_table tfoot td:last').text(serviceTotal.toLocaleString());

                                /* ===============================
                                 * 9. EMPLOYEE CONTRIBUTION
                                 =============================== */
                                if (total_income > 0 && employer_contribution > 0) {
                                    const employerContributionPercentage =
                                        (employer_contribution / total_income) * 100;

                                    rollSlots(
                                        employerContributionPercentage,
                                        'total_emp_contribution_card',
                                        1500,
                                        100
                                    );

                                    document.getElementById('contri_per_total').textContent =
                                        employerContributionPercentage.toFixed(2);
                                }
                            }
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