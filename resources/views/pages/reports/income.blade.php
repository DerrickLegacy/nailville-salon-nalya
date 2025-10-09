<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="mb-2 flex flex-col md:flex-row md:justify-between md:items-center fade-in">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li><a href="#" class="text-gray-500 hover:text-blue-600">Reports</a></li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-2">›</span>
                            <a href="" class="text-gray-500 hover:text-blue-600">
                                Income
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
                <h1 class="text-2xl font-bold text-gray-900">Income Report</h1>
            </div>

        </div>
        {{-- <hr> --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mb-6">
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
            <div class="mt-10">
                <div id="todays-income-container" class="flex flex-col lg:flex-row gap-6">
                    <!-- Left Column -->
                    <div class="flex-1 lg:w-2/3">
                        <!-- Row 1: Performance by Service -->
                        <h5 class="text-xl font-semibold dark:text-white my-6">Performance By Service</h5>
                        <div class="w-full md:w-1/2 mb-4">
                            <form class="flex items-right">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" id="simple-search" name="simple-search"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="Search" required>
                                </div>
                            </form>
                        </div>

                        <div class="overflow-x-auto rounded-lg shadow-md">
                            <table id="service_table"
                                class="min-w-full bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Service
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Total Income (UGX)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <td class="px-6 py-3 font-semibold text-gray-800 dark:text-gray-200">Total</td>
                                        <td class="px-6 py-3 font-semibold text-right text-gray-800 dark:text-gray-200">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <h5 class="text-xl font-semibold dark:text-white my-6">Performance Within Time Period</h5>
                        <div class="overflow-x-auto rounded-lg shadow-md mb-6">
                            <table id="income_table"
                                class="min-w-full bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date/Month
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Income (UGX)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <td class="px-6 py-3 font-semibold text-gray-800 dark:text-gray-200">Total</td>
                                        <td class="px-6 py-3 font-semibold text-right text-gray-800 dark:text-gray-200">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div
                        class="lg:w-1/3 flex flex-col gap-4 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-gray-700 p-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2 text-center">
                                Service Breakdown
                            </h3>
                            <div id="todays-income-chart-progress" class="w-full"
                                style="min-height: 200px; height: 250px;"></div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2 text-center">
                                Daily Income Goal Achievement
                            </h3>
                            <div id="todays-income-performance-progress" class="w-full"
                                style="min-height: 200px; height: 250px;"></div>
                        </div>


                        <div id="myfirstchart" style="height: 250px;"></div>

                    </div>
                </div>
            </div>


        </div>
        <script>
            $(document).ready(function() {
                const picker = flatpickr("#dateSelect", {
                    mode: "range"
                });
                picker.clear();


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

                loadIncomeData('Today');

                // On click of any filter button
                $('.solid-filter-btns').on('click', function() {
                    const range = $(this).text().trim();
                    loadIncomeData(range);

                });

                $('.filter-btn').on('click', function() {
                    const range = $(this).text().trim();
                    const ww = document.getElementById('dateSelect').value.split(' - ');
                    const employee_id = $('#employee_id').val();
                    const startDate = ww[0];
                    const endDate = ww[1];
                    loadIncomeData(range, startDate, endDate, employee_id);
                });

                function loadIncomeData(range, start_date = null, end_date = null, employee_id = null) {

                    $.ajax({
                        url: "{{ route('reports.data') }}",
                        method: "GET",
                        data: {
                            range: range,
                            start_date: start_date,
                            end_date: end_date,
                            employee_id: employee_id

                        },
                        dataType: "json",
                        success: function(data) {
                            const income_tbody = $('#income_table tbody');
                            income_tbody.empty();
                            let total_income = 0;
                            const groupedData = Object.entries(data.grouped_by_period).map(([label,
                                value
                            ]) => ({
                                label,
                                value
                            }));

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

                            const chartData = Object.entries(data.grouped).map(([label, value]) => ({
                                label: label,
                                value: value
                            }));

                            $('#todays-income-chart-progress').empty();
                            $('#todays-income-performance-progress').empty();
                            Morris.Donut({
                                element: 'todays-income-performance-progress',
                                data: [{
                                        label: 'Achieved',
                                        value: data.daily_percentage
                                    },
                                    {
                                        label: 'Remaining',
                                        value: 100 - data.daily_percentage
                                    }
                                ],
                                colors: ['#CC0066', '#E5E5E5'],
                                resize: true,
                                redraw: true,
                                formatter: function(y, data) {
                                    return y.toFixed(2) + '%';
                                }
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
                            $('table tfoot td:last').text(total.toLocaleString());
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading chart data:", error);
                        }
                    });
                }
            });
        </script>
</x-app-layout>
