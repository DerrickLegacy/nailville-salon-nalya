@php
// Resolve variant classes
$isV2 = $variant === 'v2';
$isV3 = $variant === 'v3';

$baseBefore = 'before:absolute before:inset-0 before:backdrop-blur-md before:-z-10 sticky top-0 z-30';

// Default variant
$beforeClasses = 'max-lg:before:bg-white/90 dark:max-lg:before:bg-gray-800/90';
$extraClasses = 'max-lg:shadow-xs lg:before:bg-gray-100/90 dark:lg:before:bg-gray-900/90';

if ($isV2 || $isV3) {
// For v2 and v3
$beforeClasses = 'before:bg-white';
$extraClasses = 'after:absolute after:h-px after:inset-x-0 after:top-full after:bg-gray-200 dark:after:bg-gray-700/60 after:-z-10';

if ($isV2) $beforeClasses .= ' dark:before:bg-gray-800';
if ($isV3) $beforeClasses .= ' dark:before:bg-gray-900';
}
@endphp

<header class="{{ $baseBefore }} {{ $beforeClasses }} {{ $extraClasses }}">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 
            {{ $isV2 || $isV3 ? '' : 'lg:border-b border-gray-200 dark:border-gray-700/60' }}">

            <!-- Left Section -->
            <div class="flex">

                <!-- Mobile Hamburger -->
                <button class="text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 lg:hidden"
                    @click.stop="sidebarOpen = !sidebarOpen"
                    aria-controls="sidebar"
                    :aria-expanded="sidebarOpen">

                    <span class="sr-only">Open sidebar</span>

                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <rect x="4" y="5" width="16" height="2" />
                        <rect x="4" y="11" width="16" height="2" />
                        <rect x="4" y="17" width="16" height="2" />
                    </svg>

                </button>

            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-3">

                <!-- Notifications -->
                <x-dropdown-notifications align="right" />

                <!-- Theme toggle -->
                <x-theme-toggle />

                <!-- Divider -->
                <div class="w-px h-6 bg-gray-200 dark:bg-gray-700/60"></div>

                <!-- Profile -->
                <x-dropdown-profile align="right" />

            </div>

        </div>
    </div>
</header>