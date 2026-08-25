{{-- Mobile overlay --}}
<div
    id="sidebar-overlay"
    class="fixed inset-0 z-40 hidden bg-slate-900/40 lg:hidden"
></div>


<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-200 lg:static lg:translate-x-0"
>

    {{-- Logo --}}
    <div class="flex h-16 shrink-0 items-center border-b border-slate-200 px-5">

        <a
            href="{{ route('dashboard') }}"
            class="flex items-center gap-3"
        >

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white">

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
                        d="M19 14c1.49-1.49 2-3.57 2-5.5A7.5 7.5 0 0013.5 1C11.57 1 9.49 1.51 8 3L3 8l5 5 5-5 5 5"
                    />
                </svg>

            </div>

            <div>
                <p class="text-sm font-bold text-slate-900">
                    Clinic App
                </p>

                <p class="text-xs text-slate-400">
                    Management System
                </p>
            </div>

        </a>

    </div>


    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-5">

        <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
            Tổng quan
        </p>


        {{-- Dashboard --}}
        <a
            href="{{ route('dashboard') }}"
            class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium
            {{ request()->routeIs('dashboard')
                ? 'bg-blue-50 text-blue-700'
                : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
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
                    d="M4 13h6V4H4v9zm10 7h6v-9h-6v9zM4 20h6v-3H4v3zm10-12h6V4h-6v4z"
                />
            </svg>

            Dashboard

        </a>


        <p class="mb-2 mt-6 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
            Phòng khám
        </p>


        {{-- Appointments --}}
        <a
            href="{{ url('/appointments') }}"
            class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900"
        >

            <svg
                class="h-5 w-5 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-width="1.8"
                    d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"
                />
            </svg>

            Lịch khám

        </a>

        {{-- Schedules --}}
        <a
            href="{{ route('schedules.web.index') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
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
                    d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M8 13h2m2 0h2m2 0h2M8 17h2m2 0h2"
                />
            </svg>

            Lịch làm việc
        </a>

        {{-- Patients --}}
        <a
            href="{{ url('/patients') }}"
            class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900"
        >

            <svg
                class="h-5 w-5 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-width="1.8"
                    d="M16 19v-1a4 4 0 00-4-4H8a4 4 0 00-4 4v1m6-8a4 4 0 100-8 4 4 0 000 8zm7-4v6m3-3h-6"
                />
            </svg>

            Bệnh nhân

        </a>

                {{-- Doctors --}}
        <a
            href="{{ url('/doctors') }}"
            class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900"
        >

            <svg
                class="h-5 w-5 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M15 19a4 4 0 00-8 0m4-8a4 4 0 100-8 4 4 0 000 8zm5-3v6m3-3h-6"
                />
            </svg>

            Bác sĩ

        </a>

        {{-- Examination --}}
        <a
            href="{{ url('/examinations') }}"
            class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900"
        >

            <svg
                class="h-5 w-5 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-width="1.8"
                    d="M8 3h8l1 3h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h2l1-3zm2 7h4m-2-2v4"
                />
            </svg>

            Khám bệnh

        </a>

        {{-- Medicines --}}
        <a
        href="{{ route('medicines.web.index') }}"
        class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium
        {{ request()->routeIs('medicines.web.*')
        ? 'bg-blue-50 text-blue-700'
        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"

        >

        <svg
            class="h-5 w-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M9 3h6m-5 0v4.5a2 2 0 01-.6 1.4l-2.8 2.8a4 4 0 000 5.7l1.4 1.4a4 4 0 005.7 0l2.8-2.8a2 2 0 00.6-1.4V3"
            />
            <path
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                d="M8 13h8m-7 3h6"
            />
        </svg>

        Thuốc

        </a>


        {{-- Prescriptions --}}
        <a
            href="{{ url('/prescriptions') }}"
            class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900"
        >

            <svg
                class="h-5 w-5 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-width="1.8"
                    d="M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2zm3 5h4m-4 4h4m-4 4h2"
                />
            </svg>

            Đơn thuốc

        </a>


        {{-- Payments --}}
        <a
            href="{{ url('/payments') }}"
            class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900"
        >

            <svg
                class="h-5 w-5 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-width="1.8"
                    d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm3 9h4"
                />
            </svg>

            Thanh toán

        </a>


        {{-- Admin --}}
        @if (auth()->user()?->role?->name === 'ADMIN')

            <p class="mb-2 mt-6 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                Quản trị
            </p>


            <a
                href="{{ url('/users') }}"
                class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900"
            >

                <svg
                    class="h-5 w-5 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-width="1.8"
                        d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm7-5v6m3-3h-6"
                    />
                </svg>

                Người dùng

            </a>


            <a
                href="{{ url('/specialties') }}"
                class="mb-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900"
            >

                <svg
                    class="h-5 w-5 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-width="1.8"
                        d="M9 3v4m6-4v4M5 9h14M6 5h12a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"
                    />
                </svg>

                Chuyên khoa

            </a>

        @endif

    </nav>


    {{-- User / Logout --}}
    <div class="shrink-0 border-t border-slate-200 p-3">

        @auth

            <div class="mb-2 flex items-center gap-3 rounded-xl px-3 py-2">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700">

                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                </div>

                <div class="min-w-0">

                    <p class="truncate text-sm font-medium text-slate-800">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-slate-500">
                        {{ auth()->user()->role?->display_name ?? 'Người dùng' }}
                    </p>

                </div>

            </div>


            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900"
                >

                    <svg
                        class="h-5 w-5 text-slate-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-width="1.8"
                            d="M10 6H6a2 2 0 00-2 2v8a2 2 0 002 2h4m5-8l4 4m0 0l-4 4m4-4H9"
                        />
                    </svg>

                    Đăng xuất

                </button>

            </form>

        @endauth

    </div>

</aside>