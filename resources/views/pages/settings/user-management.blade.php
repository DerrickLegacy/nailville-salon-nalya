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

            <a href="{{ route('settings.create.employer') }}">
                <button class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Employee</button>
            </a>

        </div>



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
                                Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Phone</th>
                        </tr>

                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">

                    </tbody>
                </table>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                $(document).ready(function() {
                    const table = new DataTable('#employersTable', {
                        responsive: true,
                        pageLength: 10,
                        lengthMenu: [10, 25, 50, 100],
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
                                render: function(data) {
                                    return data ? `$${data}` : '-';
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
                                    const viewUrl =
                                        `/settings/user-management/employee-details/${row.employee_id}`;
                                    const editUrl =
                                        `/settings/user-management/edit-employer/${row.employee_id}`;

                                    return `
                                        <div class="flex space-x-2">
                                            <a href="${viewUrl}" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center space-x-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m0 0l3 3m-3-3l3-3m-6 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                                                </svg>
                                                <span>View</span>
                                            </a>
                                            <a href="${editUrl}" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 flex items-center space-x-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5.414-7.414a2 2 0 112.828 2.828L11 16H7v-4l6.586-6.586z" />
                                                </svg>
                                                <span>Edit</span>
                                            </a>
                                            <button onclick="confirmDelete(${row.employee_id})" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 flex items-center space-x-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                        `;
                                }
                            }
                        ]
                    });
                });

                // SweetAlert confirmation
                function confirmDelete(employeeId) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to delete route or make an AJAX request
                            window.location.href = `/settings/user-management/delete-employer/${employeeId}`;
                        }
                    });
                }
            </script>
        </div>
    </div>

</x-app-layout>
