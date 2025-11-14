<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
            
            @if($unreadCount > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="mt-4 sm:mt-0 px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors">
                    Mark All as Read ({{ $unreadCount }})
                </button>
            </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
            @forelse($notifications as $notification)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 
                        {{ !$notification->is_read ? 'border-l-4 border-l-violet-500' : '' }}
                        hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Title with Priority Badge -->
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $notification->title }}
                                </h3>
                                
                                @if($notification->priority === 'high' || $notification->priority === 'critical')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                             {{ $notification->priority === 'critical' ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ ucfirst($notification->priority) }}
                                </span>
                                @endif
                                
                                @if(!$notification->is_read)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-violet-100 text-violet-800">
                                    New
                                </span>
                                @endif
                            </div>
                            
                            <!-- Message -->
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line mb-3">
                                {{ $notification->message }}
                            </p>
                            
                            <!-- Meta Info -->
                            <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                
                                <span class="px-2 py-1 rounded-full text-xs
                                             {{ $notification->category === 'goal' ? 'bg-green-100 text-green-800' : '' }}
                                             {{ $notification->category === 'alert' ? 'bg-red-100 text-red-800' : '' }}
                                             {{ $notification->category === 'insight' ? 'bg-blue-100 text-blue-800' : '' }}
                                             {{ $notification->category === 'income' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                             {{ $notification->category === 'expense' ? 'bg-orange-100 text-orange-800' : '' }}">
                                    {{ ucfirst($notification->category) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Mark as Read Button -->
                        @if(!$notification->is_read)
                        <button onclick="markAsRead({{ $notification->id }})"
                                class="ml-4 p-2 text-gray-400 hover:text-violet-600 transition-colors"
                                title="Mark as read">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No notifications</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You're all caught up!</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>

    <script>
        function markAsRead(id) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</x-app-layout>
