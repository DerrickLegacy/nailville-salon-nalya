<x-app-layout>
    <div class="px-3 sm:px-4 lg:px-6 py-4 sm:py-6 w-full max-w-full mx-auto">

        <!-- Dashboard Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-4 sm:mb-6">
            <div class="mb-3 sm:mb-0">
                <h1 class="text-xl sm:text-2xl lg:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard</h1>
            </div>

            <div class="flex gap-2">
                <x-dropdown-filter align="right" />
                <button
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white text-sm px-3 py-2">
                    <svg class="fill-current shrink-0 w-4 h-4 sm:mr-2" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="hidden sm:inline">Add View</span>
                </button>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <!-- Today Invoices -->
            <div class="bg-teal-400 text-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-4 flex items-center space-x-3">
                <div class="p-2 sm:p-3 bg-teal-500 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h3l2 2h4l2-2h3a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm truncate">Today Invoices</p>
                    <p class="text-base sm:text-lg font-semibold">+{{ $cardData['today_invoices'] }}</p>
                </div>
            </div>

            <!-- Today Sales -->
            <div class="bg-orange-400 text-white rounded-lg sm:rounded-xl shadow-sm p-3 sm:p-4 flex items-center space-x-3">
                <div class="p-2 sm:p-3 bg-orange-500 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13m-11-6v6m4-6v6m-8 0h16" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm truncate">Today Sales</p>
                    <p class="text-base sm:text-lg font-semibold truncate">↑{{ number_format($cardData['today_sales']) }} Ugx</p>
                </div>
            </div>