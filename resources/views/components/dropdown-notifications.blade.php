@props([
'align' => 'right'
])

<div class="relative inline-flex" x-data="{
    open: false,
    notifications: [],
    unreadCount: 0,
    loading: false,
    
    async fetchNotifications() {
        if (this.notifications.length > 0) return;
        
        this.loading = true;
        try {
            const response = await fetch('{{ route('notifications.list') }}');
            const data = await response.json();
            this.notifications = data.notifications;
            this.unreadCount = data.unread_count;
        } catch (error) {
            console.error('Error fetching notifications:', error);
        } finally {
            this.loading = false;
        }
    },
    
    async markAsRead(id) {
        try {
            await fetch(`/notifications/${id}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const notification = this.notifications.find(n => n.id === id);
            if (notification && !notification.is_read) {
                notification.is_read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    },
    
    getCategoryIcon(category) {
        const icons = {
            'income': '💰',
            'expense': '💸',
            'goal': '🎯',
            'alert': '⚠️',
            'insight': '💡',
            'system': '⚙️'
        };
        return icons[category] || '📣';
    },
    
    getPriorityClass(priority) {
        const classes = {
            'critical': 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500',
            'high': 'bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500',
            'medium': 'bg-blue-50 dark:bg-blue-900/20',
            'low': 'bg-gray-50 dark:bg-gray-700/20'
        };
        return classes[priority] || classes['medium'];
    },
    
    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);
        
        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return `${diffMins}m ago`;
        if (diffHours < 24) return `${diffHours}h ago`;
        if (diffDays < 7) return `${diffDays}d ago`;
        
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }
}" @click.away="open = false">
    <button
        class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 lg:hover:bg-gray-200 dark:hover:bg-gray-700/50 dark:lg:hover:bg-gray-800 rounded-full relative"
        :class="{ 'bg-gray-200 dark:bg-gray-800': open }"
        aria-haspopup="true"
        @click.prevent="open = !open; fetchNotifications()"
        :aria-expanded="open">
        <span class="sr-only">Notifications</span>
        <svg class="fill-current text-gray-500/80 dark:text-gray-400/80" width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.5 0C2.91 0 0 2.462 0 5.5c0 1.075.37 2.074 1 2.922V12l2.699-1.542A7.454 7.454 0 0 0 6.5 11c3.59 0 6.5-2.462 6.5-5.5S10.09 0 6.5 0z" />
            <path d="M16 9.5c0-.987-.429-1.897-1.147-2.639C14.124 10.348 10.66 13 6.5 13c-.103 0-.202-.018-.305-.021C7.231 13.617 8.556 14 10 14c.449 0 .886-.04 1.307-.11L15 16v-4h-.012C15.627 11.285 16 10.425 16 9.5z" />
        </svg>
        <div x-show="unreadCount > 0"
            class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 border-2 border-gray-100 dark:border-gray-900 rounded-full"
            x-cloak></div>
    </button>
    <div
        class="origin-top-right z-10 absolute top-full -mr-48 sm:mr-0 min-w-80 max-w-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 rounded-lg shadow-lg overflow-hidden mt-1 {{$align === 'right' ? 'right-0' : 'left-0'}}"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        x-show="open"
        x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-out duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak>
        <div class="flex items-center justify-between text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase pt-3 pb-2 px-4 border-b border-gray-200 dark:border-gray-700/60">
            <span>Notifications</span>
            <span x-show="unreadCount > 0" class="bg-red-500 text-white px-2 py-0.5 rounded-full text-xs" x-text="unreadCount" x-cloak></span>
        </div>

        <div class="max-h-96 overflow-y-auto">
            <template x-if="loading">
                <div class="py-8 text-center">
                    <svg class="animate-spin h-8 w-8 mx-auto text-violet-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Loading notifications...</p>
                </div>
            </template>

            <template x-if="!loading && notifications.length === 0">
                <div class="py-8 text-center">
                    <svg class="h-12 w-12 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">No notifications yet</p>
                </div>
            </template>

            <ul x-show="!loading && notifications.length > 0">
                <template x-for="notification in notifications" :key="notification.id">
                    <li class="border-b border-gray-200 dark:border-gray-700/60 last:border-0"
                        :class="getPriorityClass(notification.priority)">
                        <a class="block py-3 px-4 hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors cursor-pointer"
                            href="{{ route('notifications.index') }}"
                            @click="markAsRead(notification.id); open = false"
                            :class="{ 'opacity-60': notification.is_read }">
                            <div class="flex items-start space-x-2">
                                <span class="text-lg flex-shrink-0" x-text="getCategoryIcon(notification.category)"></span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100 mb-1" x-text="notification.title"></p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2" x-text="notification.message"></p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500" x-text="formatDate(notification.created_at)"></span>
                                        <span x-show="!notification.is_read"
                                            class="w-2 h-2 bg-blue-500 rounded-full"
                                            x-cloak></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                </template>
            </ul>
        </div>

        <div x-show="notifications.length > 0"
            class="border-t border-gray-200 dark:border-gray-700/60 py-2 px-4 bg-gray-50 dark:bg-gray-700/20"
            x-cloak>
            <a href="{{ route('notifications.index') }}"
                class="text-xs font-medium text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 flex items-center justify-center"
                @click="open = false">
                View all notifications
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>