<x-app-layout class="bg-white">
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-full mx-auto">
        <!-- Page Header -->
        <div class="mb-2 flex flex-col md:flex-row md:justify-between md:items-center fade-in">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Settings</a></li>
                        <li class="flex items-center">
                            <span class="text-gray-400 dark:text-gray-500 mx-2">›</span>
                            <a href="{{ route('admin.users.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">System Users</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="mb-2 flex flex-col md:flex-row md:justify-between md:items-center">
            <h1 class="text-3xl text-gray-800 dark:text-gray-100 mb-4 md:mb-0">
                System Users
            </h1>

            <div class="flex space-x-3">
                <a href="{{ route('admin.users.create') }}">
                    <button class="px-5 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>Add System User</span>
                    </button>
                </a>
            </div>
        </div>

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

        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl">
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded-lg p-4">
                <table id="systemUsersTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const table = new DataTable('#systemUsersTable', {
                responsive: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                ajax: {
                    url: "{{ route('admin.users.list') }}",
                    dataSrc: 'data',
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error);
                    }
                },
                columns: [{
                        data: 'name',
                        title: 'Name',
                        render: function(data) {
                            return '<span class="text-gray-900 dark:text-gray-100">' + data + '</span>';
                        }
                    },
                    {
                        data: 'email',
                        title: 'Email',
                        render: function(data) {
                            return '<span class="text-gray-900 dark:text-gray-100">' + data + '</span>';
                        }
                    },
                    {
                        data: 'admin',
                        title: 'Role',
                        render: function(data) {
                            return data ?
                                '<span class="px-2 py-1 bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-400 rounded-full text-xs font-semibold">Admin</span>' :
                                '<span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-400 rounded-full text-xs font-semibold">User</span>';
                        }
                    },
                    {
                        data: 'is_active',
                        title: 'Status',
                        render: function(data) {
                            return data ?
                                '<span class="px-2 py-1 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 rounded-full text-xs font-semibold">Active</span>' :
                                '<span class="px-2 py-1 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-400 rounded-full text-xs font-semibold">Inactive</span>';
                        }
                    },
                    {
                        data: 'created_at',
                        title: 'Created',
                        render: function(data) {
                            return data ? '<span class="text-gray-900 dark:text-gray-100">' + new Date(data).toLocaleDateString() + '</span>' : '-';
                        }
                    },
                    {
                        data: null,
                        title: 'Actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const editUrl = `/admin/users/${row.id}/edit`;
                            const toggleUrl = `/admin/users/${row.id}/toggle-status`;
                            const deleteUrl = `/admin/users/${row.id}`;

                            let actions = `<div class="flex space-x-2">`;

                            // Edit button
                            actions += `
                                <a href="${editUrl}" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5.414-7.414a2 2 0 112.828 2.828L11 16H7v-4l6.586-6.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </a>
                            `;

                            // Toggle status button
                            const statusText = row.is_active ? 'Deactivate' : 'Activate';
                            const statusColor = row.is_active ? 'orange' : 'green';
                            actions += `
                                <button onclick="toggleStatus(${row.id}, '${statusText}')" class="px-2 py-1 bg-${statusColor}-500 text-white rounded hover:bg-${statusColor}-600 flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    <span>${statusText}</span>
                                </button>
                            `;

                            // Delete button (only if not current user)
                            if (row.id !== {
                                    {
                                        auth() - > id()
                                    }
                                }) {
                                actions += `<button onclick="confirmDelete(${row.id})" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                `;
                            }

                            actions += `</div>`;
                            return actions;
                        }
                    }
                ]
            });
        });

        function toggleStatus(userId, action) {
            Swal.fire({
                title: `${action} User?`,
                text: `Are you sure you want to ${action.toLowerCase()} this user?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${action.toLowerCase()}!`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/admin/users/${userId}/toggle-status`;
                }
            });
        }

        function confirmDelete(userId) {
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
                    // Create a form and submit it for DELETE request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/users/${userId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>