<x-app-layout class="bg-gray-50">
    <div class="px-4 py-6 max-w-full mx-auto">


        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 fade-in" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif



        <!-- Page Header -->
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center fade-in ">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li><a href="#" class="text-gray-500 hover:text-blue-600">Settings</a></li>
                        <li class="flex items-center">
                            <span class="text-gray-400 mx-2">›</span>
                            <a href="{{ route('configurations.settings') }}"
                                class="text-gray-500 hover:text-blue-600">Business Goals
                                Management</a>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900">Business Goals Configuration</h1>
                <p class="text-gray-500 text-sm">Manage application targets, currency, themes, and other system
                    settings.
                </p>
            </div>
            <div>

            </div>
        </div>

        <div class="flex justify-end mb-4">
            <x-simple-modal title="Add New Configuration">
                @slot('trigger')
                    <button x-on:click="modalIsOpen = true"
                        class="px-4 py-2 bg-[#8470FF] hover:bg-purple-500 text-white rounded-lg shadow">
                        + New Configuration
                    </button>
                @endslot

                <form id="config-form" action="{{ route('configurations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-[#8470FF] bg-gray-100 focus:border-[#8470FF]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Description</label>
                        <textarea name="description" rows="2"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-[#8470FF] focus:border-[#8470FF]"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Value <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="value" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-[#8470FF] focus:border-[#8470FF]">
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-600">Key <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="key" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-[#8470FF] bg-gray-100 focus:border-[#8470FF]"
                            disabled>
                    </div>



                    <div>
                        <label class="block text-sm font-medium text-gray-600">Type</label>
                        <select name="type"
                            class="w-full px-4 py-2 border rounded-lg focus:[#8470FF] focus:border-[#8470FF]">
                            <option value="string" selected>String</option>
                            <option value="integer">Integer</option>
                            <option value="decimal">Decimal</option>
                            <option value="boolean">Boolean</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>



                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" @click="modalIsOpen = false"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#8470FF] hover:bg-purple-500 text-white rounded-lg">Save</button>
                    </div>
                </form>
            </x-simple-modal>

        </div>
        <div id="transactions-spinner"
            class="absolute inset-1 flex items-center justify-center bg-white/70 dark:bg-gray-800/70 z-50 ">
            <div role="status">
                <svg aria-hidden="true"
                    class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-yellow-400"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-xl p-6 w-full max-w-full mx-auto card-hover">
            <table id="config-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value
                        </th>


                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                </tbody>
            </table>
        </div>



    </div>

</x-app-layout>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        loadTransactions()

        function editConfig(id, url) {
            console.log("Edit config", id, "via", url);
            // open modal or send AJAX PUT
        }

        function deleteConfig(id, url) {
            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url, // ✅ Correct URL
                        type: "DELETE",
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: response.message ||
                                    'Configuration deleted successfully.',
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadTransactions()
                        },
                        error: function(xhr) {
                            console.log(xhr)
                            Swal.fire({
                                title: "Error!",
                                text: xhr.responseJSON?.message ||
                                    "Something went wrong.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }


        function loadTransactions() {
            const table = new DataTable('#config-table', {
                responsive: true,
                destroy: true, // Allow reinitialization
                ordering: true,
                processing: true,
                serverSide: false, // you are sending full JSON, not DataTables server-side format
                searching: true,
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100, 500],

                ajax: {
                    url: "{{ route('configurations.fetch') }}",
                    dataSrc: function(response) {
                        console.log(response);
                        return response.data; // ✅ return your array
                    },
                    complete: function() {
                        $('#transactions-spinner').addClass('hidden');
                    },
                    error: function(xhr, status, error) {
                        console.error('DataTable AJAX error:', status, error);
                    }
                },

                columns: [{
                        data: "title"
                    },
                    {
                        data: "description"
                    },
                    {
                        data: "value"
                    },


                    {
                        data: "id",
                        render: function(data, type, row) {
                            // generate route URLs using Blade with a placeholder
                            var deleteRoute =
                                "{{ route('configurations.destroy.goal', ':id') }}"
                                .replace(':id', data);
                            var editRoute = "{{ route('configurations.update', ':id') }}"
                                .replace(':id', data);

                            return `
                                <div class="flex justify-end items-center space-x-2">
                                    <button class="btn-edit flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded shadow transition duration-150"
                                        title="Edit" data-id="${data}" data-url="${editRoute}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.536-6.536a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button class="btn-delete flex items-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded shadow transition duration-150"
                                        title="Delete" data-id="${data}" data-url="${deleteRoute}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            `;
                        }
                    }

                ]
            });
        }

        // Then, bind events
        $(document).on("click", ".btn-edit", function() {
            let id = $(this).data("id");
            let url = $(this).data("url");
            editConfig(id, url);
        });

        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id");
            let url = $(this).data("url");
            deleteConfig(id, url);
        });
    })

    document.addEventListener("DOMContentLoaded", function() {
        const titleInput = document.querySelector("input[name='title']");
        const keyInput = document.querySelector("input[name='key']");

        titleInput.addEventListener("input", function() {
            let slug = this.value
                .toLowerCase() // lowercase
                .replace(/[^a-z0-9\s]/g, '') // remove special chars
                .trim() // remove spaces before/after
                .replace(/\s+/g, '_'); // replace spaces with underscores

            keyInput.value = slug;
        });
    });
</script>
