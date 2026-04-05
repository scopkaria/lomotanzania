<x-app-layout>
    <x-slot name="header">Notifications</x-slot>

    <div x-data="notificationsPage()" x-init="init()" class="space-y-4">
        {{-- Controls --}}
        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-[#131414] text-white' : 'bg-white text-gray-600 border border-gray-200'" class="px-3 py-1.5 rounded-lg text-xs font-medium transition">All</button>
                <button @click="filter = 'unread'" :class="filter === 'unread' ? 'bg-[#131414] text-white' : 'bg-white text-gray-600 border border-gray-200'" class="px-3 py-1.5 rounded-lg text-xs font-medium transition">Unread</button>
            </div>
            <button @click="markAllRead()" x-show="notifications.filter(n => !n.read_at).length > 0" class="text-xs text-[#FEBC11] font-medium hover:underline">
                Mark all as read
            </button>
        </div>

        {{-- Notification list --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden divide-y divide-gray-50">
            <template x-if="notifications.length === 0">
                <div class="px-6 py-12 text-center text-gray-400 text-sm">No notifications.</div>
            </template>
            <template x-for="notification in filtered" :key="notification.id">
                <div @click="markRead(notification)" :class="!notification.read_at ? 'bg-[#FEBC11]/5' : ''" class="flex items-start gap-3 px-6 py-4 hover:bg-gray-50 transition cursor-pointer">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 mt-0.5">
                        <template x-if="notification.data.type === 'chat'">
                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                        </template>
                        <template x-if="notification.data.type === 'booking'">
                            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                        </template>
                        <template x-if="notification.data.type === 'request'">
                            <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            </div>
                        </template>
                        <template x-if="!['chat','booking','request'].includes(notification.data.type)">
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                        </template>
                    </div>
                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900" x-text="notification.data.title"></p>
                        <p class="text-xs text-gray-500 mt-0.5" x-text="notification.data.message"></p>
                        <p class="text-xs text-gray-400 mt-1" x-text="timeAgo(notification.created_at)"></p>
                    </div>
                    {{-- Unread dot --}}
                    <div x-show="!notification.read_at" class="w-2 h-2 rounded-full bg-[#FEBC11] mt-2 flex-shrink-0"></div>
                </div>
            </template>
        </div>
    </div>

    <script>
    function notificationsPage() {
        return {
            notifications: [],
            filter: 'all',

            get filtered() {
                if (this.filter === 'unread') return this.notifications.filter(n => !n.read_at);
                return this.notifications;
            },

            init() {
                this.fetch();
                setInterval(() => this.fetch(), 10000);
            },

            async fetch() {
                const res = await fetch('{{ route("admin.notifications.fetch") }}');
                this.notifications = await res.json();
            },

            async markRead(notification) {
                if (!notification.read_at) {
                    await fetch(`/admin/notifications/${notification.id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                    notification.read_at = new Date().toISOString();
                }
                if (notification.data.url) window.location.href = notification.data.url;
            },

            async markAllRead() {
                await fetch('{{ route("admin.notifications.read-all") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                this.notifications.forEach(n => n.read_at = n.read_at || new Date().toISOString());
            },

            timeAgo(date) {
                const seconds = Math.floor((new Date() - new Date(date)) / 1000);
                if (seconds < 60) return 'just now';
                if (seconds < 3600) return Math.floor(seconds / 60) + 'm ago';
                if (seconds < 86400) return Math.floor(seconds / 3600) + 'h ago';
                return Math.floor(seconds / 86400) + 'd ago';
            }
        }
    }
    </script>
</x-app-layout>
