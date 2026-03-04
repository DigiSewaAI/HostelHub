<div x-data="notificationDropdown()" @click.away="open = false" class="relative">
    <!-- Bell Button -->
    <button @click="toggle()" class="relative p-2 text-white hover:text-gray-200 focus:outline-none">
        <i class="fas fa-bell text-xl"></i>
        <template x-if="notificationUnreadCount > 0">
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full min-w-[18px] h-[18px]" x-text="notificationUnreadCount"></span>
        </template>
    </button>

    <!-- Dropdown -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-20 border border-gray-200" style="display: none;">
        <div class="px-4 py-2 bg-gray-100 border-b border-gray-200">
            <h3 class="font-semibold text-gray-700 nepali">सूचनाहरू</h3>
        </div>
        <div class="max-h-96 overflow-y-auto">
            <template x-for="notification in notifications" :key="notification.id">
                <!-- ✅ FIXED: href कहिल्यै # हुँदैन -->
                <a :href="(notification.data.url && notification.data.url !== '#') ? notification.data.url : getFallbackUrl(notification)" 
                   @click.prevent="handleNotificationClick(notification.id, (notification.data.url && notification.data.url !== '#') ? notification.data.url : getFallbackUrl(notification), $event)" 
                   class="flex items-start px-4 py-3 hover:bg-gray-50 border-b border-gray-100 no-underline hover:no-underline" 
                   :class="{ 'bg-blue-50': !notification.read_at }"
                   data-ignore-selector="true">
                    <!-- Avatar / Icon -->
                    <div class="flex-shrink-0 mr-3">
                        <!-- यदि birthday notification हो भने केक आइकन देखाउने -->
                        <template x-if="notification.data.type === 'birthday'">
                            <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-600">
                                <i class="fas fa-birthday-cake text-xl"></i>
                            </div>
                        </template>
                        <!-- अन्यथा, यदि avatar छ भने त्यो देखाउने -->
                        <template x-if="notification.data.type !== 'birthday' && notification.data.avatar">
                            <img :src="notification.data.avatar" class="w-10 h-10 rounded-full object-cover">
                        </template>
                        <!-- नभए default user icon -->
                        <template x-if="notification.data.type !== 'birthday' && !notification.data.avatar">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                <i class="fas fa-user-circle text-2xl"></i>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800" :class="{ 'font-semibold': !notification.read_at }" x-text="notification.data.message"></p>
                        <p class="text-xs text-gray-500 mt-1" x-text="timeAgo(notification.created_at)"></p>
                    </div>
                </a>
            </template>
            <div x-show="notifications.length === 0" class="px-4 py-6 text-center text-gray-500">
                कुनै सूचना छैन।
            </div>
        </div>
        <div class="px-4 py-2 bg-gray-50 border-t border-gray-200 text-center">
            @php
                $notificationsRoute = '#';
                if (auth()->check()) {
                    $user = auth()->user();
                    if ($user->hasRole('admin')) {
                        $notificationsRoute = route('admin.notifications.index');
                    } elseif ($user->hasRole('owner') || $user->hasRole('hostel_manager')) {
                        $notificationsRoute = route('owner.notifications.index');
                    } elseif ($user->hasRole('student')) {
                        $notificationsRoute = route('student.notifications.index');
                    }
                }
                $notificationsRoute = $notificationsRoute === '#' ? '#dummy' : $notificationsRoute;
            @endphp
            <a href="{{ $notificationsRoute }}" class="text-indigo-600 text-sm hover:underline" data-ignore-selector="true">सबै सूचनाहरू हेर्नुहोस्</a>
        </div>
    </div>
</div>

<script>
function notificationDropdown() {
    return {
        open: false,
        notificationUnreadCount: {{ $unreadCount ?? 0 }}, // ✅ यहाँ $unreadCount प्रयोग गरियो
        notifications: @json($notifications->toArray() ?? []),

        init() {
            window.addEventListener('new-notification', (e) => {
                if (!this.notifications.some(n => n.id === e.detail.id)) {
                    this.notifications.unshift(e.detail);
                    this.notificationUnreadCount++;
                }
            });
        },

        toggle() {
            this.open = !this.open;
            if (this.open && this.notifications.length === 0) {
                this.fetchNotifications();
            }
        },

        fetchNotifications() {
            axios.get('/notifications')
                .then(response => {
                    const newOnes = response.data.filter(
                        newNotif => !this.notifications.some(old => old.id === newNotif.id)
                    );
                    this.notifications = [...newOnes, ...this.notifications];
                    this.notificationUnreadCount = this.notifications.filter(n => !n.read_at).length;
                })
                .catch(error => {
                    console.error('Failed to fetch notifications', error);
                });
        },

        getFallbackUrl(notification) {
            if (notification.data.type === 'birthday' && notification.data.student_id) {
                return '/owner/students/' + notification.data.student_id;
            }
            if (notification.data.type === 'maintenance' && notification.data.issue_id) {
                return '/owner/room-issues/' + notification.data.issue_id;
            }
            if (notification.data.type === 'student' && notification.data.student_id) {
                return '/owner/students/' + notification.data.student_id;
            }
            return '#dummy';
        },

        handleNotificationClick(id, url, event) {
            event.preventDefault();
            event.stopPropagation();
            
            console.log('Notification clicked:', id, url);
            
            if (!url || url === '#' || url === '#dummy') {
                console.warn('No valid URL for notification:', id);
                axios.post('/notifications/' + id + '/mark-as-read')
                    .catch(error => console.error('Error marking as read:', error));
                return;
            }
            
            axios.post('/notifications/' + id + '/mark-as-read')
                .then(() => {
                    window.location.href = url;
                })
                .catch(error => {
                    console.error('Error marking as read:', error);
                    window.location.href = url;
                });
        },

        markAsRead(id, url) {
            this.handleNotificationClick(id, url, { preventDefault: () => {}, stopPropagation: () => {} });
        },

        timeAgo(date) {
            const diff = (new Date() - new Date(date)) / 1000;
            if (diff < 60) return 'अहिले मात्र';
            if (diff < 3600) return Math.floor(diff/60) + ' मिनेट अघि';
            if (diff < 86400) return Math.floor(diff/3600) + ' घण्टा अघि';
            return Math.floor(diff/86400) + ' दिन अघि';
        }
    }
}
</script>