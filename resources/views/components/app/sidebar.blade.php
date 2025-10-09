{{-- sidebar.blade.php component in components.app --}}
<div class="min-w-fit" x-data="{ sidebarOpen: false, sidebarExpanded: true, openDropdown: null }">
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="flex lg:flex! flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:w-64! shrink-0 bg-white dark:bg-gray-800 p-4 transition-all duration-200 ease-in-out {{ $variant === 'v2' ? 'border-r border-gray-200 dark:border-gray-700/60' : 'rounded-r-2xl shadow-xs' }}"
        :class="sidebarOpen ? 'max-lg:translate-x-0' : 'max-lg:-translate-x-64'" @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false">

        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pr-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-gray-500 hover:text-gray-400" @click.stop="sidebarOpen = !sidebarOpen"
                aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
            <!-- Logo -->
            <a class="block" href="{{ route('dashboard') }}">
                <img class="object-cover object-center rounded-full border-2 border-purple-600"
                    src="{{ asset('images/small_nailville_logo_50x50.jpg') }}" alt="Authentication image" />
            </a>
        </div>

        <!-- Links -->
        <div class="space-y-8">
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Pages</span>
                </h3>
                <ul class="mt-3">

                    {{-- Dashboard --}}
                    <li
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['dashboard'])) from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04] @endif">
                        <a href="#0"
                            class="block text-gray-800 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['dashboard'])) hover:text-gray-900 dark:hover:text-white @endif"
                            @click.prevent="openDropdown = (openDropdown === 'dashboard' ? null : 'dashboard'); sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['dashboard'])) text-violet-500 @else text-gray-400 dark:text-gray-500 @endif"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                        <path
                                            d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                                </div>
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"
                                        :class="openDropdown === 'dashboard' ? 'rotate-180' : 'rotate-0'"
                                        viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <ul class="pl-8 mt-1" x-show="openDropdown === 'dashboard'" x-transition>
                            <li>
                                <a href="{{ route('dashboard') }}"
                                    class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">
                                    Main
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Transactions --}}
                    <li
                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['finance'])) from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04] @endif">
                        <a href="#0"
                            class="block text-gray-800 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['finance'])) hover:text-gray-900 dark:hover:text-white @endif"
                            @click.prevent="openDropdown = (openDropdown === 'finance' ? null : 'finance'); sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['finance'])) text-violet-500 @else text-gray-400 dark:text-gray-500 @endif"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M6 0a6 6 0 0 0-6 6c0 1.077.304 2.062.78 2.912a1 1 0 1 0 1.745-.976A3.945 3.945 0 0 1 2 6a4 4 0 0 1 4-4c.693 0 1.344.194 1.936.525A1 1 0 1 0 8.912.779 5.944 5.944 0 0 0 6 0Z" />
                                        <path
                                            d="M10 4a6 6 0 1 0 0 12 6 6 0 0 0 0-12Zm-4 6a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Transactions</span>
                                </div>
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"
                                        :class="openDropdown === 'finance' ? 'rotate-180' : 'rotate-0'"
                                        viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <ul class="pl-8 mt-1" x-show="openDropdown === 'finance'" x-transition>
                            <li><a href="{{ route('transactions.income', 'income') }}"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Income
                                    Transactions</a></li>
                            <li><a href="{{ route('transactions.expense', 'expense') }}"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Expense
                                    Transactions</a></li>
                        </ul>
                    </li>

                    {{-- Reports --}}
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r">
                        <a href="#0" class="block text-gray-800 dark:text-gray-100 truncate transition"
                            @click.prevent="openDropdown = (openDropdown === 'reports' ? null : 'reports'); sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M6 0a6 6 0 0 0-6 6c0 1.077.304 2.062.78 2.912a1 1 0 1 0 1.745-.976A3.945 3.945 0 0 1 2 6a4 4 0 0 1 4-4c.693 0 1.344.194 1.936.525A1 1 0 1 0 8.912.779 5.944 5.944 0 0 0 6 0Z" />
                                        <path
                                            d="M10 4a6 6 0 1 0 0 12 6 6 0 0 0 0-12Zm-4 6a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Reports</span>
                                </div>
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"
                                        :class="openDropdown === 'reports' ? 'rotate-180' : 'rotate-0'"
                                        viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <ul class="pl-8 mt-1" x-show="openDropdown === 'reports'" x-transition>
                            <li><a href="{{ route('reports.income') }}"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Income</a>
                            </li>
                            <li><a href="{{ route('reports.expense') }}"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Expense</a>
                            </li>
                            <li><a href="{{ route('reports.profit') }}"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Profit</a>
                            </li>
                            <li><a href="{{ route('reports.goals') }}"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Goals</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Inventory --}}
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r">
                        <a href="#0" class="block text-gray-800 dark:text-gray-100 truncate transition"
                            @click.prevent="openDropdown = (openDropdown === 'inventory' ? null : 'inventory'); sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M21 16V8c0-.5-.25-.9-.67-1.13l-8-4.5c-.2-.12-.46-.12-.66 0l-8 4.5C3.25 7.1 3 7.5 3 8v8c0 .36.19.7.5.89l8 4.8c.3.18.7.18 1 0l8-4.8c.31-.19.5-.53.5-.89ZM12 4.15 18.74 8 12 11.85 5.26 8 12 4.15ZM5 9.46l6 3.6v6.53l-6-3.6V9.46Zm8 10.13v-6.53l6-3.6v6.53l-6 3.6Z" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Inventory</span>
                                </div>
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"
                                        :class="openDropdown === 'inventory' ? 'rotate-180' : 'rotate-0'"
                                        viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <ul class="pl-8 mt-1" x-show="openDropdown === 'inventory'" x-transition>
                            <li><a href="{{ route('inventory.manage') }}"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Manage
                                    Items</a></li>
                            {{-- <li><a href="{{ route('inventory.price.changes') }}"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Price
                                    Changes</a></li> --}}
                        </ul>
                    </li>

                    {{-- Settings --}}
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r">
                        <a href="#0" class="block text-gray-800 dark:text-gray-100 truncate transition"
                            @click.prevent="openDropdown = (openDropdown === 'settings' ? null : 'settings'); sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M10.5 1a3.502 3.502 0 0 1 3.355 2.5H15a1 1 0 1 1 0 2h-1.145a3.502 3.502 0 0 1-6.71 0H1a1 1 0 0 1 0-2h6.145A3.502 3.502 0 0 1 10.5 1ZM9 4.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM5.5 9a3.502 3.502 0 0 1 3.355 2.5H15a1 1 0 1 1 0 2H8.855a3.502 3.502 0 0 1-6.71 0H1a1 1 0 1 1 0-2h1.145A3.502 3.502 0 0 1 5.5 9ZM4 12.5a1.5 1.5 0 1 0 3 0 1.5 1.5 0 0 0-3 0Z" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Settings</span>
                                </div>
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"
                                        :class="openDropdown === 'settings' ? 'rotate-180' : 'rotate-0'"
                                        viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <ul class="pl-8 mt-1" x-show="openDropdown === 'settings'" x-transition>
                            <li><a href="{{ route('settings.my.account') }}"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">My
                                    Account</a></li>
                            <li><a href="{{ route('settings.management') }}"
                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">User
                                    Management</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- More group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3"> <span
                        class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span> <span
                        class="lg:hidden lg:sidebar-expanded:block 2xl:block">More</span> </h3>
                <ul class="mt-3"> <!-- Onboarding -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0" x-data="{ open: false }"> <a
                            class="block text-gray-800 dark:text-gray-100 truncate transition"
                            :class="open ? '' : 'hover:text-gray-900 dark:hover:text-white'" href="#0"
                            @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center"> <svg
                                        class="shrink-0 fill-current text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M6.668.714a1 1 0 0 1-.673 1.244 6.014 6.014 0 0 0-4.037 4.037 1 1 0 1 1-1.916-.571A8.014 8.014 0 0 1 5.425.041a1 1 0 0 1 1.243.673ZM7.71 4.709a3 3 0 1 0 0 6 3 3 0 0 0 0-6ZM9.995.04a1 1 0 1 0-.57 1.918 6.014 6.014 0 0 1 4.036 4.037 1 1 0 0 0 1.917-.571A8.014 8.014 0 0 0 9.995.041ZM14.705 8.75a1 1 0 0 1 .673 1.244 8.014 8.014 0 0 1-5.383 5.384 1 1 0 0 1-.57-1.917 6.014 6.014 0 0 0 4.036-4.037 1 1 0 0 1 1.244-.673ZM1.958 9.424a1 1 0 0 0-1.916.57 8.014 8.014 0 0 0 5.383 5.384 1 1 0 0 0 .57-1.917 6.014 6.014 0 0 1-4.037-4.037Z" />
                                    </svg> <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Configurations</span>
                                </div> <!-- Icon -->
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"
                                        :class="{ 'rotate-180': open }" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['onboarding'])) {{ 'hidden' }} @endif"
                                :class="open ? 'block!' : 'hidden'">
                                <li class="mb-1 last:mb-0"> <a
                                        class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                        href="{{ route('configurations.settings') }}"> <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Goals
                                        </span> </a> </li>

                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Sidebar expand/collapse button -->
        <div class="pt-3 inline-flex justify-end mt-auto">
            <div class="w-12 pl-4 pr-3 py-2">
                <button
                    class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition-colors"
                    @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500 sidebar-expanded:rotate-180"
                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
