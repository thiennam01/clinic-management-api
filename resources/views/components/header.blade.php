<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">

    <div class="flex h-16 items-center justify-between px-5 sm:px-6 lg:px-8">

        {{-- Left --}}
        <div class="flex items-center gap-3">

            {{-- Mobile sidebar button --}}
            <button
                id="sidebar-toggle"
                type="button"
                class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden"
                aria-label="Mở menu"
            >
                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>


            {{-- Page title --}}
            <div>

                <h1 class="text-base font-semibold text-slate-900 sm:text-lg">
                    @yield('page-title', 'Tổng quan')
                </h1>

                <p class="hidden text-xs text-slate-500 sm:block">
                    Hệ thống quản lý phòng khám
                </p>

            </div>

        </div>


        {{-- Right --}}
        <div class="flex items-center gap-3">

            {{-- Notification --}}
            <div class="relative" id="notification-wrapper">

                <button
                    id="notification-button"
                    type="button"
                    class="relative rounded-xl p-2.5 text-slate-500 hover:bg-slate-100 hover:text-slate-700"
                    aria-label="Thông báo"
                    aria-expanded="false"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M15 17h5l-1.5-1.5V11a6.5 6.5 0 00-13 0v4.5L4 17h5m6 0a3 3 0 01-6 0"
                        />
                    </svg>

                    <span
                        id="notification-badge"
                        class="absolute right-1.5 top-1.5 hidden min-w-4 rounded-full bg-red-500 px-1 text-center text-[10px] font-semibold leading-4 text-white ring-2 ring-white"
                    ></span>
                </button>

                <div
                    id="notification-dropdown"
                    class="absolute right-0 top-12 z-50 hidden w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl sm:w-96"
                >
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">
                                Thông báo
                            </h3>

                            <p
                                id="notification-count-text"
                                class="text-xs text-slate-500"
                            >
                                Không có thông báo mới
                            </p>
                        </div>

                        <button
                            id="notification-read-all"
                            type="button"
                            class="text-xs font-medium text-blue-600 hover:text-blue-700"
                        >
                            Đánh dấu đã đọc
                        </button>
                    </div>

                    <div
                        id="notification-list"
                        class="max-h-96 overflow-y-auto"
                    >
                        <div class="px-4 py-8 text-center text-sm text-slate-500">
                            Đang tải thông báo...
                        </div>
                    </div>
                </div>

            </div>


            {{-- Divider --}}
            <div class="hidden h-8 w-px bg-slate-200 sm:block"></div>


            {{-- User --}}
            @auth

                <div class="flex items-center gap-3">

                    {{-- Avatar --}}
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700">

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                    </div>


                    {{-- User information --}}
                    <div class="hidden min-w-0 sm:block">

                        <p class="truncate text-sm font-semibold text-slate-800">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ auth()->user()->role?->display_name ?? 'Người dùng' }}
                        </p>

                    </div>

                </div>

            @endauth

        </div>

    </div>

</header>

@auth
<script>
document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('notification-button');
    const dropdown = document.getElementById('notification-dropdown');
    const badge = document.getElementById('notification-badge');
    const list = document.getElementById('notification-list');
    const countText = document.getElementById('notification-count-text');
    const readAllButton = document.getElementById('notification-read-all');
    const wrapper = document.getElementById('notification-wrapper');

    if (!button || !dropdown) {
        return;
    }

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    function updateBadge(count) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
            countText.textContent = `${count} thông báo chưa đọc`;
        } else {
            badge.classList.add('hidden');
            countText.textContent = 'Không có thông báo mới';
        }
    }

    function renderNotifications(notifications) {
        if (!notifications.length) {
            list.innerHTML = `
                <div class="px-4 py-10 text-center">
                    <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M15 17h5l-1.5-1.5V11a6.5 6.5 0 00-13 0v4.5L4 17h5" />
                        </svg>
                    </div>

                    <p class="text-sm font-medium text-slate-700">
                        Chưa có thông báo
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Các hoạt động mới sẽ xuất hiện ở đây.
                    </p>
                </div>
            `;

            return;
        }

        list.innerHTML = notifications.map(notification => `
            <div class="border-b border-slate-100 px-4 py-3 last:border-b-0 hover:bg-slate-50">
                <div class="flex gap-3">
                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm leading-5 text-slate-700">
                            ${escapeHtml(notification.message)}
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            ${escapeHtml(notification.created_at)}
                        </p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    async function loadNotifications() {
        try {
            const response = await fetch('{{ route('notifications.index') }}', {
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Không thể tải thông báo.');
            }

            const data = await response.json();

            updateBadge(data.unread_count);
            renderNotifications(data.notifications);
        } catch (error) {
            list.innerHTML = `
                <div class="px-4 py-8 text-center text-sm text-red-500">
                    Không thể tải thông báo.
                </div>
            `;
        }
    }

    async function markAllAsRead() {
        try {
            const response = await fetch('{{ route('notifications.read-all') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({}),
            });

            if (!response.ok) {
                throw new Error('Không thể cập nhật thông báo.');
            }

            const data = await response.json();

            updateBadge(data.unread_count);

            await loadNotifications();
        } catch (error) {
            console.error(error);
        }
    }

    button.addEventListener('click', async (event) => {
        event.stopPropagation();

        const isHidden = dropdown.classList.contains('hidden');

        dropdown.classList.toggle('hidden');

        button.setAttribute('aria-expanded', isHidden ? 'true' : 'false');

        if (isHidden) {
            await loadNotifications();
        }
    });

    readAllButton.addEventListener('click', async () => {
        await markAllAsRead();
    });

    document.addEventListener('click', (event) => {
        if (!wrapper.contains(event.target)) {
            dropdown.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
        }
    });

    loadNotifications();
});
</script>
@endauth