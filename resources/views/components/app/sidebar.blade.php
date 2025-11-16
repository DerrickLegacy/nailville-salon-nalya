<div x-data="{ 
    openDropdown: null,
    activeParent: '{{ Request::segment(1) }}',
    activeChild: '{{ Request::segment(2) }}',
    unreadCount: 0,
    
    async fetchUnreadCount() {
        try {
            const response = await fetch('{{ route('notifications.list') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                console.warn('Failed to fetch notifications:', response.status);
                this.unreadCount = 0;
                return;
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.warn('Response is not JSON, user might not be authenticated');
                this.unreadCount = 0;
                return;
            }
            
            const data = await response.json();
            this.unreadCount = data.unread_count || 0;
        } catch (error) {
            console.error('Error fetching unread count:', error);
            this.unreadCount = 0;
        }
    },
    
    init() {
        this.fetchUnreadCount();
        setInterval(() => this.fetchUnreadCount(), 30000);
    }
}" class="min-w-fit">

    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-30 z-40 lg:hidden transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
        @click="sidebarOpen = false"
        aria-hidden="true"
        x-cloak>
    </div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 
                h-screen overflow-y-auto no-scrollbar shrink-0 bg-white dark:bg-gray-800 
                transition-all duration-300 ease-in-out border-r border-gray-200 dark:border-gray-700
                shadow-lg lg:shadow-none"
        :class="{
             'w-64': sidebarExpanded,
             'w-20': !sidebarExpanded,
             'translate-x-0': sidebarOpen,
             '-translate-x-64': !sidebarOpen
         }"
        @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false">

        <!-- Sidebar header -->
        <div class="flex items-center justify-between px-4 py-6 border-b border-gray-200 dark:border-gray-700">


            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <img class="w-10 h-10 rounded-full border-2 border-violet-500 object-cover"
                    src="{{ asset('images/small_nailville_logo_50x50.jpg') }}"
                    alt="Nailville Logo" />
                <span class="font-bold text-lg text-gray-800 dark:text-white transition-opacity duration-300"
                    :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 w-0'">
                    Nailville
                </span>
            </a>

            <!-- Close button (mobile) -->
            <button class="lg:hidden text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                @click="sidebarOpen = false"
                aria-label="Close sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-6 space-y-2">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200
                      {{ Request::is('dashboard*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                </svg>
                <span class="font-medium text-sm transition-opacity duration-300"
                    :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 w-0'">
                    Dashboard
                </span>
            </a>

            <!-- Transactions -->
            <div class="space-y-1">
                <button @click="openDropdown = (openDropdown === 'transactions' ? null : 'transactions'); activeParent='transactions'"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200
                               {{ Request::is('transactions*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium text-sm transition-opacity duration-300"
                            :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 w-0'">
                            Transactions
                        </span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="{'rotate-180': openDropdown === 'transactions', 'opacity-0': !sidebarExpanded}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="openDropdown === 'transactions'"
                    x-transition
                    class="pl-11 space-y-1">
                    <a href="{{ route('transactions.income') }}"
                        @click="activeChild='income'"
                        :class="activeChild==='income' ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50'"
                        class="block px-3 py-2 text-sm rounded-lg transition-colors">
                        Income
                    </a>
                    <a href="{{ route('transactions.expense') }}"
                        @click="activeChild='expense'"
                        :class="activeChild==='expense' ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50'"
                        class="block px-3 py-2 text-sm rounded-lg transition-colors">
                        Expenses
                    </a>
                </div>
            </div>

            <!-- Reports -->
            <div class="space-y-1">
                <button @click="openDropdown = (openDropdown === 'reports' ? null : 'reports'); activeParent='reports'"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200
                               {{ Request::is('reports*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                        <span class="font-medium text-sm transition-opacity duration-300"
                            :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 w-0'">
                            Reports
                        </span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="{'rotate-180': openDropdown === 'reports', 'opacity-0': !sidebarExpanded}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="openDropdown === 'reports'"
                    x-transition
                    class="pl-11 space-y-1">
                    <a href="{{ route('reports.income') }}" @click="activeChild='income_report'"
                        :class="activeChild==='income_report' ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50'"
                        class="block px-3 py-2 text-sm rounded-lg transition-colors">Income Report</a>

                    <a href="{{ route('reports.expense') }}" @click="activeChild='expense_report'"
                        :class="activeChild==='expense_report' ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50'"
                        class="block px-3 py-2 text-sm rounded-lg transition-colors">Expense Report</a>

                    <a href="{{ route('reports.net.income') }}" @click="activeChild='net_income'"
                        :class="activeChild==='net_income' ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50'"
                        class="block px-3 py-2 text-sm rounded-lg transition-colors">Net Income</a>
                </div>
            </div>

            <!-- Inventory -->
            <a href="{{ route('inventory.manage') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200
                      {{ Request::is('inventory*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-sm transition-opacity duration-300"
                    :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 w-0'">
                    Inventory
                </span>
            </a>

            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}"
                class="relative flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200
                      {{ Request::is('notifications*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <div class="relative flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                    </svg>
                </div>
                <span class="font-medium text-sm transition-opacity duration-300"
                    :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 w-0'">
                    Notifications
                </span>
                <span x-show="unreadCount > 0 && sidebarExpanded"
                    class="ml-auto px-2 py-0.5 text-xs font-semibold text-white bg-red-500 rounded-full"
                    x-text="unreadCount"
                    x-cloak></span>
            </a>

            <!-- Settings -->
            <div class="space-y-1">
                <button @click="openDropdown = (openDropdown === 'settings' ? null : 'settings'); activeParent='settings'"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200
                               {{ Request::is('settings*') ? 'bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium text-sm transition-opacity duration-300"
                            :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 w-0'">
                            Settings & Mgt
                        </span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="{'rotate-180': openDropdown === 'settings', 'opacity-0': !sidebarExpanded}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="openDropdown === 'settings'"
                    x-transition
                    class="pl-11 space-y-1">
                    <a href="{{ route('settings.my.account') }}"
                        class="block px-3 py-2 text-sm rounded-lg transition-colors text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        My Account
                    </a>
                    <a href="{{ route('settings.management') }}"
                        class="block px-3 py-2 text-sm rounded-lg transition-colors text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        User Management
                    </a>

                    @if (Auth::user()?->isAdmin())
                    <a href="{{ route('admin.users.index') }}"
                        class="block px-3 py-2 text-sm rounded-lg transition-colors text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        System Users
                    </a>
                    @endif

                    <a href="{{ route('configurations.settings') }}"
                        class="block px-3 py-2 text-sm rounded-lg transition-colors text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        App Configuration
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar Toggle Button -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <button @click="sidebarExpanded = !sidebarExpanded"
                class="w-full flex items-center justify-center px-3 py-2 rounded-lg
                           text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700
                           transition-all duration-200"
                :title="sidebarExpanded ? 'Collapse sidebar' : 'Expand sidebar'">
                <svg class="w-5 h-5 transition-transform duration-300"
                    :class="{'rotate-180': !sidebarExpanded}"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="ml-2 text-sm font-medium transition-opacity duration-300"
                    :class="sidebarExpanded ? 'opacity-100' : 'opacity-0 w-0'">
                    Collapse
                </span>
            </button>
        </div>
    </div>
</div>