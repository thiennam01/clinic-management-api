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
            <button
                type="button"
                class="relative rounded-xl p-2.5 text-slate-500 hover:bg-slate-100 hover:text-slate-700"
                aria-label="Thông báo"
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

                <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>

            </button>


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