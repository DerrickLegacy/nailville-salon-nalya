<x-app-layout class="bg-white">
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-full mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Settings & Mgt</a></li>
                <li class="flex items-center">
                    <span class="text-gray-400 dark:text-gray-500 mx-2">›</span>
                    <a href="{{ route('admin.users.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">System Services</a>
                </li>
                <li class="flex items-center">
                    <span class="text-gray-400 dark:text-gray-500 mx-2">›</span>
                    <span class="text-gray-700 dark:text-[#8200DB] ">list</span>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Services Offered</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your salon services and pricing</p>
        </div>

        <!-- Info Alert -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        <strong>Note:</strong> This is a list of services the business is offerring to the customers.
                    </p>
                </div>
            </div>
        </div>

        <div class="mb-2 flex flex-col md:flex-row md:justify-between md:items-center">
            <h1 class="text-3xl text-gray-800 dark:text-gray-100 mb-4 md:mb-0">
            </h1>

            <div class="flex space-x-3">
                <button id="manageSectionsBtn" class="btn bg-blue-600 hover:bg-blue-700 text-white shadow-lg px-2 py-2 sm:px-2 sm:py-1 rounded-lg flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Manage Sections</span>
                </button>
                <button id="addServiceBtn" class="btn bg-violet-600 hover:bg-violet-700 text-white shadow-lg px-2 py-2 rounded-lg flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Service</span>
                </button>
            </div>
        </div>

        <!-- Services Table -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-[#8200DB]  mb-2">Category</label>
                    <select id="filterCategory" class="form-select w-full rounded-lg border-[#8200DB]  dark:border-[#8200DB] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-[#8200DB] ">
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-[#8200DB]  mb-2">Section/Team</label>
                    <select id="filterSection" class="form-select w-full rounded-lg border-[#8200DB]  dark:border-[#8200DB] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-[#8200DB] ">
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-[#8200DB]  mb-2">Status</label>
                    <select id="filterStatus" class="form-select w-full rounded-lg border-[#8200DB]  dark:border-[#8200DB] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-[#8200DB] ">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button id="resetFilters" class="btn bg-gray-200 dark:bg-gray-700 hover:bg-[#8200DB]  dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 w-full">
                        Reset Filters
                    </button>
                </div>
            </div>
            <hr>
            <div class="p-3 mt-2">
                <div class="overflow-x-auto">
                    <table id="servicesTable" class="table-auto w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Service Name</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap hidden md:table-cell">
                                    <div class="font-semibold text-left">Category</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap hidden md:table-cell">
                                    <div class="font-semibold text-left">Section</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-right">Price</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap hidden lg:table-cell">
                                    <div class="font-semibold text-center">Status</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-center">Actions</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <!-- DataTable will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add/Edit Service Modal -->
        <div id="serviceModal" class="fixed inset-0 hidden z-50 fill-white drop-shadow-xl/50" style="z-index: 9999; backdrop-filter: blur(4px);">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center mb-6">
                            <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 dark:text-gray-100">Add Service</h2>
                            <button id="closeModal" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Form -->
                        <form id="serviceForm">
                            <input type="hidden" id="serviceId" name="service_id">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-[#8200DB]  mb-2">Service Name <span class="text-red-500">*</span></label>
                                <input type="text" id="serviceName" name="name" required
                                    class="form-input w-full rounded-lg border-[#8200DB]  dark:border-[#8200DB] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-[#8200DB] "
                                    placeholder="e.g., Hair Cut">
                                <span class="text-red-500 text-xs error-message" id="error-name"></span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-[#8200DB]  mb-2">Category</label>
                                    <select id="serviceCategory" name="category_id"
                                        class="form-select w-full rounded-lg border-[#8200DB]  dark:border-[#8200DB] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-[#8200DB] ">
                                    </select>

                                    <span class="text-red-500 text-xs error-message" id="error-category"></span>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-[#8200DB]  mb-2">Section</label>

                                    <select id="serviceSection" name="section_id"
                                        class="form-select w-full rounded-lg border-[#8200DB]  dark:border-[#8200DB] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-[#8200DB] ">
                                    </select>
                                    <span class="text-red-500 text-xs error-message" id="error-category"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-[#8200DB]  mb-2">Price <span class="text-red-500">*</span></label>
                                    <input type="number" id="servicePrice" name="price" required step="0.01" min="0"
                                        class="form-input w-full rounded-lg border-[#8200DB]  dark:border-[#8200DB] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-[#8200DB] "
                                        placeholder="0.00">
                                    <span class="text-red-500 text-xs error-message" id="error-price"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-[#8200DB]  mb-2">Status <span class="text-red-500">*</span></label>
                                    <select id="serviceStatus" name="status" required
                                        class="form-select w-full rounded-lg border-[#8200DB]  dark:border-[#8200DB] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-[#8200DB] ">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                    <span class="text-red-500 text-xs error-message" id="error-status"></span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-[#8200DB]  mb-2">Description</label>
                                <textarea id="serviceDescription" name="description" rows="3"
                                    class="form-textarea w-full rounded-lg border-[#8200DB]  dark:border-[#8200DB] focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-[#8200DB] "
                                    placeholder="Service description..."></textarea>
                                <span class="text-red-500 text-xs error-message" id="error-description"></span>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-[#8200DB]  dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg">
                                    Cancel
                                </button>
                                <button type="submit" id="submitBtn" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-lg">
                                    Save Service
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Management Modal -->
        <div id="sectionModal" class="fixed inset-0 hidden z-50 fill-white drop-shadow-xl/50" style="z-index: 9999; backdrop-filter: blur(4px);">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="error" id="returned-error"></div>
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manage Sections</h2>
                            <button id="closeSectionModal" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Add Section Form -->
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Add New Section</h3>
                            <form id="sectionForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <input type="hidden" id="sectionId" name="section_id">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="sectionName" name="name" required
                                        class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-gray-600 dark:text-gray-100"
                                        placeholder="e.g., Men Hair Team">
                                    <span class="text-red-500 text-xs error-message" id="error-section-name"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                    <input type="text" id="sectionDescription" name="description"
                                        class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-gray-600 dark:text-gray-100"
                                        placeholder="Section description">
                                    <span class="text-red-500 text-xs error-message" id="error-section-description"></span>
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" id="submitSectionBtn" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                        Add Section
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Sections List -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Existing Sections</h3>
                                <div class="overflow-x-auto">
                                    <table id="sectionsTable" class="table-auto w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20">
                                            <tr>
                                                <th class="px-4 py-3 text-left">Section Name</th>
                                                <th class="px-4 py-3 text-left">Description</th>
                                                <th class="px-4 py-3 text-center">Services Count</th>
                                                <th class="px-4 py-3 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sectionsTableBody" class="text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                            <!-- Sections will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    </div>

    <input type="hidden" id="authUserId" value="{{ auth()->user()->id }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let table;
            let isEditMode = false;
            let currentServiceId = null;
            table = new DataTable('#servicesTable', {
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.services.list") }}',
                    data: function(d) {
                        d.category_id = $('#filterCategory').val();
                        d.section_id = $('#filterSection').val();
                        d.status = $('#filterStatus').val();
                    },
                    error: function() {
                        window.alert('Error loading data');
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category',
                        name: 'category',
                        className: 'hidden md:table-cell',
                        render: function(data) {
                            return data || '<span class="text-gray-400">N/A</span>';
                        }
                    },
                    {
                        data: 'section',
                        name: 'section',
                        className: 'hidden md:table-cell',
                        render: function(data) {
                            return data || '<span class="text-gray-400">N/A</span>';
                        }
                    },
                    {
                        data: 'price',
                        name: 'price',
                        className: 'text-right',
                        render: function(data) {
                            return data;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center hidden lg:table-cell',
                        render: function(data) {
                            if (data === 'Active') {
                                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Active</span>';
                            } else {
                                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Inactive</span>';
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <div class="flex space-x-2 justify-center">
                                <!-- Edit Button -->
                                <button class="btn-edit px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center space-x-1"
                                        data-id="${row.id}" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span>Edit</span>
                                </button>

                                <!-- Delete Button -->
                                <button class="btn-delete px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 flex items-center space-x-1"
                                        data-id="${row.id}" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Delete</span>
                                </button>
                            </div>
                            `;
                        }
                    }

                ],
                order: [
                    [0, 'desc']
                ],
                responsive: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    emptyTable: "No services found",
                    zeroRecords: "No matching services found"
                }
            });

            // Filter handlers
            $('#filterCategory, #filterStatus, #filterSection').on('change', function() {
                table.ajax.reload();
            });

            $('#resetFilters').on('click', function() {
                $('#filterCategory, #filterStatus, #filterSection').val('');
                table.ajax.reload();
            });

            $('#bt')

            // Open Add Service Modal
            $('#addServiceBtn').on('click', function() {
                openModal(false);
            });

            // Open Section Management Modal
            $('#manageSectionsBtn').on('click', function() {
                openSectionModal();
            });

            // Edit Service
            $(document).on('click', '.btn-edit', function() {
                const serviceId = $(this).data('id');
                loadServiceData(serviceId);
            });

            // Delete Service
            $(document).on('click', '.btn-delete', function() {
                const serviceId = $(this).data('id');
                closeSectionModal();

                Swal.fire({
                    title: 'Delete Service?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/services/${serviceId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Deleted!', response.message, 'success');
                                    table.ajax.reload();
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', 'Failed to delete service', 'error');
                            }
                        });
                    }
                });
            });

            $('#serviceForm').on('submit', function(e) {
                e.preventDefault();
                clearErrors();

                const formData = {
                    service_code: $('#serviceCode').val(),
                    name: $('#serviceName').val(),
                    category_id: $('#serviceCategory').val(), // <-- fix
                    section_id: $('#serviceSection').val(), // <-- fix
                    price: $('#servicePrice').val(),
                    description: $('#serviceDescription').val(),
                    status: $('#serviceStatus').val()
                };

                const url = isEditMode ? `/admin/services/${currentServiceId}` : '{{ route("admin.services.store") }}';
                const method = isEditMode ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            closeModal();
                            showNotification('success', response.message);
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            displayErrors(errors);
                        } else {
                            showNotification('error', 'An error occurred. Please try again.');
                        }
                    }
                });
            });

            $('#closeModal, #cancelBtn').on('click', function(e) {
                e.preventDefault();
                closeModal();
            });

            $('#serviceModal').on('click', function(e) {
                if (e.target.id === 'serviceModal') {
                    closeModal();
                }
            });

            function openModal(editMode = false, callback) {
                isEditMode = editMode;
                $('#modalTitle').text(editMode ? 'Edit Service' : 'Add Service');
                $('#submitBtn').text(editMode ? 'Update Service' : 'Save Service');

                const modal = document.getElementById('serviceModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';

                if (!editMode) {
                    $('#serviceForm')[0].reset();
                    currentServiceId = null;
                }
                loadServiceMeta(callback);
            }

            function closeModal() {
                const modal = document.getElementById('serviceModal');
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';

                $('#serviceForm')[0].reset();
                clearErrors();
                isEditMode = false;
                currentServiceId = null;
            }

            function loadServiceData(serviceId) {
                $.ajax({
                    url: `/admin/services/${serviceId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const service = response.data;
                            currentServiceId = service.id;

                            $('#serviceCode').val(service.service_code);
                            $('#serviceName').val(service.name);
                            $('#servicePrice').val(service.price);
                            $('#serviceDescription').val(service.description);
                            $('#serviceStatus').val(service.status);

                            openModal(true, function() {
                                $('#serviceCategory').val(service.category.id);
                                $('#serviceSection').val(service.section.id);
                            });
                        }
                    },
                    error: function() {
                        showNotification('error', 'Failed to load service data');
                    }
                });
            }


            function displayErrors(errors) {
                $.each(errors, function(field, messages) {
                    $(`#error-${field}`).text(messages[0]);
                });
            }

            function clearErrors() {
                $('.error-message').text('');
            }

            function showNotification(type, message) {
                const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
                const notification = $(`
                    <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 notification">
                        ${message}
                    </div>
                `);
                $('body').append(notification);

                setTimeout(function() {
                    notification.fadeOut(function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });

        let metaCache = null;

        function showNotification(type, message) {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const notification = $(`
                    <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 notification">
                        ${message}
                    </div>
                `);
            $('body').append(notification);
            $('#returned-error').append(notification);

            setTimeout(function() {
                notification.fadeOut(function() {
                    $(this).remove();
                });
            }, 3000);
        }

        /**
         * Load categories & sections once
         */
        function loadServiceMeta(callback, filter = false) {
            if (metaCache) {
                populateMeta(metaCache);
                if (callback) callback();
                return;
            }

            fetch('{{ route("admin.services.categories.services.meta") }}')
                .then(res => res.json())
                .then(data => {
                    metaCache = data;
                    populateMeta(data);
                    if (callback) callback();
                });
        }

        loadServiceMeta(function() {
            populateMeta(metaCache, true); // update filters too
        });

        function populateMeta(data, filter = false) {
            // --- Populate form selects ---
            const categorySelect = document.getElementById('serviceCategory');
            const sectionSelect = document.getElementById('serviceSection');

            categorySelect.innerHTML = '<option value="">Select Category</option>';
            sectionSelect.innerHTML = '<option value="">Select Section</option>';

            data.categories.forEach(cat => {
                // Form select
                categorySelect.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
            });

            data.sections.forEach(sec => {
                sectionSelect.innerHTML += `<option value="${sec.id}">${sec.name}</option>`;
            });

            // --- Populate filter select if requested ---
            if (filter) {
                const filterCategory = document.getElementById('filterCategory');
                filterCategory.innerHTML = '<option value="">All Categories</option>'; // for "All"
                data.categories.forEach(cat => {
                    filterCategory.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
                });

                const filterSection = document.getElementById('filterSection');
                filterSection.innerHTML = '<option value="">All Sections</option>'; // for "All"
                data.sections.forEach(sec => {
                    filterSection.innerHTML += `<option value="${sec.id}">${sec.name}</option>`;
                });
            }
        }

        // Section Management Functions
        let isSectionEditMode = false;
        let currentSectionId = null;

        function openSectionModal() {
            const modal = document.getElementById('sectionModal');
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';

            loadSections();
            resetSectionForm();
        }

        function closeSectionModal() {
            const modal = document.getElementById('sectionModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';

            resetSectionForm();
        }

        function resetSectionForm() {
            $('#sectionForm')[0].reset();
            $('#sectionId').val('');
            $('#submitSectionBtn').text('Add Section');
            $('.error-message').text('');
            isSectionEditMode = false;
            currentSectionId = null;
        }

        function loadSections() {
            $.ajax({
                url: '{{ route("admin.services.sections.list") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        populateSectionsTable(response.data);
                    }
                },
                error: function() {
                    showNotification('error', 'Failed to load sections');
                }
            });
        }

        function populateSectionsTable(sections) {
            const tbody = $('#sectionsTableBody');
            tbody.empty();

            if (sections.length === 0) {
                tbody.append(`
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            No sections found. Add your first section above.
                        </td>
                    </tr>
                `);
                return;
            }

            sections.forEach(section => {
                const servicesCount = section.services_count || 0;
                tbody.append(`
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">${section.name}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">${section.description || 'N/A'}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                ${servicesCount} services
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex space-x-2 justify-center">
                                <button class="btn-edit-section px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs"
                                        data-id="${section.id}" data-name="${section.name}" data-description="${section.description || ''}" title="Edit">
                                    Edit
                                </button>
                                <button class="btn-delete-section px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs ${servicesCount > 0 ? 'opacity-50 cursor-not-allowed' : ''}"
                                        data-id="${section.id}" data-services-count="${servicesCount}" title="${servicesCount > 0 ? 'Cannot delete - has services' : 'Delete'}">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `);
            });
        }

        // Section form submission
        $('#sectionForm').on('submit', function(e) {
            e.preventDefault();
            $('.error-message').text('');

            const formData = {
                name: $('#sectionName').val(),
                description: $('#sectionDescription').val()
            };

            const url = isSectionEditMode ?
                `/admin/services/sections/${currentSectionId}` :
                '{{ route("admin.services.sections.store") }}';
            const method = isSectionEditMode ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('success', response.message);
                        loadSections();
                        resetSectionForm();
                        // Refresh the main services table and filters
                        loadServiceMeta(function() {
                            populateMeta(metaCache, true);
                            table.ajax.reload();
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            $(`#error-section-${field}`).text(messages[0]);
                        });
                    } else {
                        showNotification('error', 'An error occurred. Please try again.');
                    }
                }
            });
        });

        // Edit section
        $(document).on('click', '.btn-edit-section', function() {
            const sectionId = $(this).data('id');
            const sectionName = $(this).data('name');
            const sectionDescription = $(this).data('description');

            $('#sectionId').val(sectionId);
            $('#sectionName').val(sectionName);
            $('#sectionDescription').val(sectionDescription);
            $('#submitSectionBtn').text('Update Section');

            isSectionEditMode = true;
            currentSectionId = sectionId;
        });

        // Delete section
        $(document).on('click', '.btn-delete-section', function() {
            const sectionId = $(this).data('id');
            const servicesCount = $(this).data('services-count');
            closeSectionModal();

            if (servicesCount > 0) {
                Swal.fire({
                    title: 'Cannot Delete Section',
                    text: `This section has ${servicesCount} associated services. Please reassign or delete those services first.`,
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Delete Section?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/services/sections/${sectionId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                loadSections();
                                // Refresh the main services table and filters
                                loadServiceMeta(function() {
                                    populateMeta(metaCache, true);
                                    table.ajax.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Failed to delete section', 'error');
                        }
                    });
                }
            });
        });

        // Close section modal handlers
        $('#closeSectionModal').on('click', function(e) {
            e.preventDefault();
            closeSectionModal();
        });

        $('#sectionModal').on('click', function(e) {
            if (e.target.id === 'sectionModal') {
                closeSectionModal();
            }
        });
    </script>

</x-app-layout>