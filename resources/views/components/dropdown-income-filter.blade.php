@props([
    'align' => 'right',
    'type' => 'income',
    'filterPageCount' => false,
    'showActions' => false,
    'id' => null,
])

<div class="relative inline-flex" x-data="{ open: false }">
    <!-- Trigger button -->


    @if ($showActions === 'false' || $filterPageCount === 'true' || $type === 'income')
        <button
            class="btn px-2.5 bg-white dark:bg-gray-800 border-gray-200 hover:border-gray-300
               dark:border-gray-700/60 dark:hover:border-gray-600 text-gray-400 dark:text-gray-500"
            aria-haspopup="true" @click.prevent="open = !open" :aria-expanded="open">
            @if ($type === 'income')
                <!-- Income filter icon -->
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 text-gray-400"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0
                         01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
                <span class="text-black">Filter</span>
                <svg class="-mr-1 ml-1.5 w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0
                         111.414 1.414l-4 4a1 1 0
                         01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            @endif

            @if ($showActions === 'true')
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
            @endif

            @if ($filterPageCount === 'true')
                <svg class="-ml-1 mr-2 w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0
                         111.414 1.414l-4 4a1 1 0
                         01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                <span class="text-black">Show</span>

                <svg class="-mr-1 ml-1.5 w-5 h-5 text-black" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256"
                    viewBox="0 0 256 256" xml:space="preserve">
                    <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
                        transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                        <path
                            d="M 45 73.264 c -14.869 0 -29.775 -8.864 -44.307 -26.346 c -0.924 -1.112 -0.924 -2.724 0 -3.836 C 15.225 25.601 30.131 16.737 45 16.737 c 14.868 0 29.775 8.864 44.307 26.345 c 0.925 1.112 0.925 2.724 0 3.836 C 74.775 64.399 59.868 73.264 45 73.264 z M 6.934 45 C 19.73 59.776 32.528 67.264 45 67.264 c 12.473 0 25.27 -7.487 38.066 -22.264 C 70.27 30.224 57.473 22.737 45 22.737 C 32.528 22.737 19.73 30.224 6.934 45 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        <path
                            d="M 45 62 c -9.374 0 -17 -7.626 -17 -17 s 7.626 -17 17 -17 s 17 7.626 17 17 S 54.374 62 45 62 z M 45 34 c -6.065 0 -11 4.935 -11 11 s 4.935 11 11 11 s 11 -4.935 11 -11 S 51.065 34 45 34 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                    </g>
                </svg>
            @endif
        </button>

    @endif
    @if ($showActions === 'true')
        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg" ria-haspopup="true" @click.prevent="open = !open" :aria-expanded="open">
            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
        </svg>
    @endif

    <!-- Dropdown panel -->
    <div class="origin-top-right z-10 absolute top-full left-0 right-auto min-w-56
               bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60
               pt-1.5 rounded-lg shadow-lg overflow-hidden mt-1
               {{ $align === 'right' ? 'md:left-auto md:right-0' : 'md:left-0 md:right-auto' }}"
        @click.outside="open = false" @keydown.escape.window="open = false" x-show="open"
        x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-out duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" x-cloak>
        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase pt-1.5 pb-2 px-3">
            Filters
        </div>

        <ul class="mb-4">
            @if ($type === 'income')
                <li class="py-1 px-3">
                    <label class="flex items-center">
                        <input type="checkbox" value="mobileMoney" class="form-checkbox cash-type">
                        <span class="text-sm font-medium ml-2">Mobile Money</span>
                    </label>
                </li>
                <li class="py-1 px-3">
                    <label class="flex items-center">
                        <input type="checkbox" value="cash" class="form-checkbox cash-type">
                        <span class="text-sm font-medium ml-2">Cash</span>
                    </label>
                </li>
                <li class="py-1 px-3">
                    <label class="flex items-center">
                        <input type="checkbox" value="Card" class="form-checkbox cash-type">
                        <span class="text-sm font-medium ml-2">Card</span>
                    </label>
                </li>
                <li class="py-1 px-3">
                    <label class="flex items-center">
                        <input type="checkbox" value="Other" class="form-checkbox cash-type">
                        <span class="text-sm font-medium ml-2">Others</span>
                    </label>
                </li>
            @endif

            @if ($filterPageCount === 'true')
                @foreach ([10, 50, 100, 200, 500, 1000, 2000, 'All'] as $count)
                    <li class="py-1 px-3">
                        <label class="flex items-center">
                            <input type="checkbox" class="page-size-option" value="{{ $count }}">
                            <span class="text-sm font-medium ml-2">{{ $count }}</span>
                        </label>
                    </li>
                @endforeach
            @endif

            @if ($showActions === 'true')
                <li>
                    <a href="#"
                        class=" action-link details-link block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"data-action="details"
                        data-id="{{ $id }}" @click="open = false">Details</a>
                </li>
                <li>
                    <a href="#"
                        class=" action-link block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                        data-action="edit" data-id="{{ $id }}" @click="open = false">Edit</a>
                </li>
                <li>
                    <a href="#"
                        class="action-link delete-link block py-2 px-4 hover:bg-gray-100  dark:hover:bg-gray-600 dark:hover:text-white"data-action="delete"
                        data-id="{{ $id }}" @click="open = false">Delete</a>
                </li>
            @endif
        </ul>

        <!-- Footer -->

        @if ($showActions === 'false' && $filterPageCount === 'false')
            <div class="py-2 px-3 border-t border-gray-200 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-700/20">
                <ul class="flex items-center justify-between">
                    <li>
                        <button
                            class="btn-xs bg-white dark:bg-gray-800 border-gray-200
                               dark:border-gray-700/60 hover:border-gray-300
                               dark:hover:border-gray-600 text-red-500">
                            Clear
                        </button>
                    </li>
                    <li>
                        <button
                            class="btn-xs bg-white dark:bg-gray-800 border-gray-200
                               dark:border-gray-700/60 hover:border-gray-300
                               dark:hover:border-gray-600 text-gray-800 dark:text-gray-300"
                            @click="open = false">
                            Apply
                        </button>
                    </li>
                </ul>
            </div>
        @endif

    </div>
</div>
