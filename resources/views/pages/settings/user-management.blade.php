<x-app-layout class="bg-white">
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-full mx-auto">
        <!-- Page Header -->
        <div class="mb-2 flex flex-col md:flex-row md:justify-between md:items-center fade-in">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li><a href="#" class="text-gray-500 hover:text-blue-600">Settings</a></li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-2">›</span>
                            <a href="{{ route('settings.management') }}" class="text-gray-500 hover:text-blue-600">User
                                Management</a>
                        </li>


                    </ol>
                </nav>
            </div>
        </div>
        <div class="mb-2 flex flex-col md:flex-row md:justify-between md:items-center">
            <h1 class="text-3xl  text-gray-800 dark:text-gray-100 mb-4 md:mb-0">
                User Management
            </h1>

            <div class="flex space-x-3">
                <a href="{{ route('settings.create.employer') }}">
                    <button class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Manage Salary</span>
                    </button>
                </a>
                <a href="{{ route('settings.create.employer') }}">
                    <button class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Add Employee</span>
                    </button>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

    </div>

    <div class="px-4 sm:px-6 lg:px-8 pb-8 w-full max-w-full mx-auto">
        <div class="bg-white shadow-lg rounded-xl">
            <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
                <table id="employersTable" class="min-w-full divide-y divide-gray-200 table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Job Title</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hire Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Salary</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Phone</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th colspan="4" class="px-4 py-2 text-left text-lg font-medium text-gray-500 uppercase tracking-wider">Total Active Employees Salary
                            </th>
                            <th id="totalActiveSalary" class="px-4 py-2 text-left text-lg font-medium text-gray-900 uppercase tracking-wider">
                            </th>
                            <th colspan="4" class="px-4 py-2 text-left text-lg font-medium text-gray-500 uppercase tracking-wider">
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        const table = new DataTable('#employersTable', {
            responsive: true,
            pageLength: 25,
            lengthMenu: [25, 50, 100],
            ajax: {
                url: "{{ route('settings.list') }}",
                dataSrc: 'data',
                error: function(xhr, status, error) {
                    console.error('Error:', status, error);
                }
            },
            columns: [{
                    data: null,
                    title: 'Name',
                    render: function(data, type, row) {
                        return `${row.first_name ?? ''} ${row.last_name ?? ''}`;
                    }
                },
                {
                    data: 'job_title',
                    title: 'Job Title'
                },
                {
                    data: 'department',
                    title: 'Department'
                },
                {
                    data: 'hire_date',
                    title: 'Hire Date'
                },
                {
                    data: 'salary',
                    title: 'Salary',
                    render: function(data, type, row) {
                        if (!data) return '-';
                        const salary = Number(data).toLocaleString();
                        // Only count active employees in salary calculations
                        if (row.work_status === 'Active') {
                            return `<span class="text-green-600 font-semibold">${salary}</span>`;
                        } else {
                            return `<span class="text-gray-400 line-through">${salary}</span>`;
                        }
                    }
                },
                {
                    data: 'work_status',
                    title: 'Status',
                    render: function(data) {
                        if (data === 'Active') {
                            return '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>';
                        } else if (data === 'Terminated') {
                            return '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Terminated</span>';
                        } else if (data === 'On Leave') {
                            return '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">On Leave</span>';
                        } else if (data === 'Resigned') {
                            return '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Resigned</span>';
                        }
                        return data || '-';
                    }
                },
                {
                    data: 'email',
                    title: 'Email',
                    defaultContent: '-'
                },
                {
                    data: 'phone_number',
                    title: 'Phone',
                    defaultContent: '-'
                },
                {
                    data: null,
                    title: 'Actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        const viewUrl = `/settings/user-management/employee-details/${row.employee_id}`;
                        const editUrl = `/settings/user-management/edit-employer/${row.employee_id}`;

                        let statusButton = '';
                        if (row.work_status === 'Active') {
                            statusButton = `
                                <button onclick="confirmStatusToggle(${row.employee_id}, 'deactivate')" 
                                        class="px-2 py-1 bg-orange-500 text-white rounded hover:bg-orange-600 flex items-center space-x-1" 
                                        title="Deactivate Employee">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                    </svg>
                                    <span>Deactivate</span>
                                </button>
                            `;
                        } else {
                            statusButton = `
                                <button onclick="confirmStatusToggle(${row.employee_id}, 'activate')" 
                                        class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 flex items-center space-x-1" 
                                        title="Activate Employee">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Activate</span>
                                </button>
                            `;
                        }

                        return `
                            <div class="flex flex-wrap gap-1">
                                <a href="${viewUrl}" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center space-x-1" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>View</span>
                                </a>
                                <a href="${editUrl}" class="px-2 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600 flex items-center space-x-1" title="Edit Employee">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5.414-7.414a2 2 0 112.828 2.828L11 16H7v-4l6.586-6.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </a>
                                ${statusButton}
                            </div>
                        `;
                    }
                }
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                // Calculate total salary for active employees only
                var totalActiveSalary = 0;
                api.rows({
                    page: 'current'
                }).data().each(function(row) {
                    if (row.work_status === 'Active' && row.salary) {
                        totalActiveSalary += parseFloat(row.salary) || 0;
                    }
                });

                // Update footer
                $(api.column(4).footer()).html(
                    `<span class="text-green-600 font-bold">${totalActiveSalary.toLocaleString()}</span>`
                );
            },
            drawCallback: function(settings) {
                // Update the total active salary in the footer
                var api = this.api();
                var response = settings.json;
                if (response && response.totalActiveSalary !== undefined) {
                    $('#totalActiveSalary').html(
                        `<span class="text-green-600 font-bold">${Number(response.totalActiveSalary).toLocaleString()}</span>`
                    );
                }
            }
        });
    });

    // SweetAlert confirmation for status toggle
    function confirmStatusToggle(employeeId, action) {
        const isActivating = action === 'activate';
        const title = isActivating ? 'Activate Employee?' : 'Deactivate Employee?';
        const text = isActivating ?
            'This employee will be included in salary calculations and can work again.' :
            'This employee will be excluded from salary calculations and cannot work until reactivated.';
        const confirmButtonText = isActivating ? 'Yes, activate!' : 'Yes, deactivate!';
        const confirmButtonColor = isActivating ? '#10b981' : '#f59e0b';
        const icon = isActivating ? 'question' : 'warning';

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we update the employee status.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Redirect to toggle status route
                window.location.href = `/settings/user-management/toggle-status/${employeeId}`;
            }
        });
    }

    // Keep old function for backward compatibility (now just calls toggle)
    function confirmDelete(employeeId) {
        confirmStatusToggle(employeeId, 'deactivate');
    }
</script>