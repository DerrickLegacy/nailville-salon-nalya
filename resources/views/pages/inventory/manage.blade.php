<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Breadcrumb -->
        <div class="mb-4 flex flex-col md:flex-row md:justify-between md:items-center fade-in">
            <nav class="flex mb-2 text-sm" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-gray-500 dark:text-gray-400">
                    <li><a href="#" class="hover:text-blue-600">Inventory</a></li>
                    <li class="flex items-center">
                        <span class="mx-2">›</span>
                        <a href="#" class="hover:text-blue-600">Inventory</a>
                    </li>
                    <li class="flex items-center">
                        <span class="mx-2">›</span>
                        <a href="#" class="hover:text-blue-600">Items</a>
                    </li>
                </ol>
            </nav>
        </div>



        <!-- Header + Datepicker -->
        <div class="sm:flex sm:justify-between sm:items-center mb-4 fade-in">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Inventory</h1>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <x-datepicker />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-lg transition p-4 flex flex-col items-center">
                <span class="text-gray-500 text-sm">Total Items</span>
                <span id="totalRecordsCount" class="text-purple-700 dark:text-white font-bold text-lg">0</span>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-lg transition p-4 flex flex-col items-center">
                <span class="text-gray-500 text-sm">In Stock</span>
                <span id="inStockCount" class="text-purple-700 dark:text-white font-bold text-lg">0</span>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-lg transition p-4 flex flex-col items-center">
                <span class="text-gray-500 text-sm">Out Of Stock</span>
                <span id="outOfStockCount" class="text-purple-700 dark:text-white font-bold text-lg">0</span>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-lg transition p-4 flex flex-col items-center">
                <span class="text-gray-500 text-sm">Overall Cost (Shs)</span>
                <span id="overallCost" class="text-purple-700 dark:text-white font-bold text-lg">0</span>
            </div>
        </div>


        <!-- Inventory Table -->
        <div class="bg-white dark:bg-gray-800 shadow-xs rounded-xl p-3 fade-in">
            <div class="relative shadow-md sm:rounded-lg overflow-hidden">

                <!-- Search + Actions -->
                <div class="flex flex-col md:flex-row items-end justify-end space-y-3 md:space-y-0 md:space-x-4 p-4">

                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <button type="button" id="exportPdfBtn"
                            class="flex items-center justify-center px-3 py-2 text-sm font-medium text-white  border border-gray-200 rounded-lg hover:bg-purple-800  bg-purple-500  hover:text-primary-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 transition">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            Export PDF
                        </button>
                    </div>


                    <x-simple-modal title="Inventory Product">
                        @slot('trigger')
                            <button x-on:click="modalIsOpen = true"
                                class="btn bg-purple-500 text-gray-100 hover:bg-purple-800 dark:bg-blue-100 dark:text-blue-800 dark:hover:bg-white">
                                Add Product
                            </button>
                        @endslot

                        <form x-ref="transactionForm" action="{{ route('inventory.product.store') }}" method="POST"
                            class="space-y-2">
                            @csrf
                            <!-- Product Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2" for="product_name">Product
                                        Name</label>
                                    <input type="text" id="product_name" name="product_name"
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                        required>
                                </div>

                                <div>
                                    <label class="block text-gray-700 font-medium mb-2" for="category">Category</label>
                                    <input type="text" id="category" name="category"
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2" for="brand">Brand</label>
                                    <input type="text" id="brand" name="brand"
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2" for="unit">Unit</label>
                                    <input type="text" id="unit" name="unit"
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2" for="supplier">Supplier</label>
                                    <input type="text" id="supplier" name="supplier"
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                </div>
                            </div>

                            <!-- Stock & Pricing -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2" for="quantity_in_stock">Quantity
                                        in Stock</label>
                                    <input type="number" id="quantity_in_stock" name="quantity_in_stock" min="0"
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2" for="reorder_level">Reorder
                                        Level</label>
                                    <input type="number" id="reorder_level" name="reorder_level" min="0"
                                        value="5"
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2" for="purchase_price">Purchase
                                        Price</label>
                                    <input type="number" step="0.01" id="purchase_price" name="purchase_price"
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2" for="selling_price">Selling
                                        Price</label>
                                    <input type="number" step="0.01" id="selling_price" name="selling_price"
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                </div>
                            </div>
                        </form>

                        @slot('footer')
                            <button x-on:click="modalIsOpen = false"
                                class="px-4 py-2 bg-red-700 text-white rounded">Cancel</button>

                            <button x-on:click="$refs.transactionForm && $refs.transactionForm.submit()"
                                class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                        @endslot
                    </x-simple-modal>
                </div>

                <!-- Spinner -->
                <div id="transactions-spinner"
                    class="absolute inset-0 flex items-center justify-center bg-white/70 dark:bg-gray-800/70 z-50 hidden">
                    <svg class="w-12 h-12 text-gray-200 animate-spin dark:text-gray-600 fill-yellow-400"
                        viewBox="0 0 100 101">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill" />
                    </svg>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-md min-h-[300px] dark:bg-gray-800" id="inventoryTableContainer">
                    <table id="inventoryTable"
                        class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border border-gray-200 dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2 border">ID</th>
                                <th class="px-4 py-2 border">Item Name</th>
                                {{-- <th class="px-4 py-2 border">SKU</th> --}}
                                <th class="px-4 py-2 border">Category</th>
                                <th class="px-4 py-2 border">Brand</th>
                                <th class="px-4 py-2 border">Unit</th>
                                <th class="px-4 py-2 border">Quantity</th>
                                <th class="px-4 py-2 border">Purchase Price</th>
                                <th class="px-4 py-2 border">Selling Price</th>
                                <th class="px-4 py-2 border">Total Value</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="transactions-wrapper">
                            <!-- DataTables will populate rows dynamically -->
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = new DataTable('#inventoryTable', {
                responsive: true,
                // searching: false,
                pageLength: 25,
                lengthChange: true,
                lengthMenu: [5, 10, 25, 50, 100, 500, 1000, 5000, 10000],
                ajax: {
                    url: "{{ route('inventory.list') }}",
                    dataSrc: function(response) {
                        console.log(response); // see your data in console
                        $('#totalRecordsCount').text(response.summary.totalItems);
                        $('#inStockCount').text(response.summary.inStock);
                        $('#outOfStockCount').text(response.summary.outOfStock);
                        $('#overallCost').text(response.summary.overallCost);
                        return response.data;
                    }
                },

                columns: [{
                        data: null, // or 'product_id' if you want, but null works
                        render: function(data, type, row, meta) {
                            // meta.row is zero-based index of the current row
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'product_name'
                    },


                    {
                        data: 'category'
                    },
                    {
                        data: 'brand'
                    },
                    {
                        data: 'unit'
                    },
                    {
                        data: 'quantity_in_stock'
                    },
                    {
                        data: 'purchase_price',
                        render: function(data, type, row) {

                            let total = parseFloat(row
                                .purchase_price);
                            return Number(total).toLocaleString();
                        }
                    },
                    {
                        data: 'selling_price',
                        render: function(data, type, row) {

                            let total = parseFloat(row
                                .selling_price);
                            return Number(total).toLocaleString();
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            // calculate total value dynamically
                            let total = parseFloat(row.quantity_in_stock) * parseFloat(row
                                .purchase_price);
                            return Number(total.toFixed(0)).toLocaleString();
                        }
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.quantity_in_stock > 0 ?
                                '<span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">In Stock</span>' :
                                '<span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">Out of Stock</span>';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            // Grab CSRF token from meta tag
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content');

                            // Dynamically generate URLs using Blade placeholders
                            const deleteUrlTemplate =
                                "{{ route('inventory.product.destroy', ':id') }}";
                            const deleteUrl = deleteUrlTemplate.replace(':id', row.product_id);

                            const editUrlTemplate =
                                "{{ route('inventory.product.update', ':id') }}";
                            const editUrl = editUrlTemplate.replace(':id', row.product_id);

                            return `
                                <!-- Edit Button -->
                                <button
                                    class="text-blue-600 hover:underline mr-2 edit-button"
                                    data-id="${row.product_id}"
                                    data-url="${editUrl}"
                                >
                                    Edit
                                </button>

                                <!-- Delete Button (SweetAlert) -->
            <button
                type="button"
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 delete-button"
                data-url="${deleteUrl}"
                data-token="${csrfToken}"
            >
                Delete
            </button>
                            `;
                        }
                    }
                ],
            });

            // Optional: Show spinner while loading data
            $('#transactions-spinner').removeClass('hidden');
            table.on('xhr', function() {
                $('#transactions-spinner').addClass('hidden');
            });
        });

        $(document).on('click', '.delete-button', function() {
            const url = $(this).data('url');
            const token = $(this).data('token');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form dynamically and submit
                    const form = $('<form>', {
                        method: 'POST',
                        action: url
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: token
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_method',
                        value: 'DELETE'
                    }));

                    $('body').append(form);
                    form.submit();
                }
            });
        });
    </script>
</x-app-layout>
