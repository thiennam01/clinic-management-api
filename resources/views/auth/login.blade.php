@extends('layouts.guest')

@section('title', 'Đăng nhập | Clinic App')

@section('content')

<div class="flex min-h-screen">

    {{-- Left: Branding --}}
    <div class="relative hidden overflow-hidden bg-blue-600 lg:flex lg:w-1/2">

        {{-- Decorative circles --}}
        <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-blue-500"></div>
        <div class="absolute -bottom-40 -left-40 h-[500px] w-[500px] rounded-full bg-blue-700"></div>

        <div class="relative z-10 flex w-full flex-col justify-between p-12 xl:p-16">

            {{-- Logo --}}
            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">

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
                            d="M19 14c1.49-1.49 2-3.57 2-5.5A7.5 7.5 0 0013.5 1C11.57 1 9.49 1.51 8 3L3 8l5 5 5-5 5 5-5 5"
                        />
                    </svg>

                </div>

                <div>
                    <div class="text-lg font-bold text-white">
                        Clinic App
                    </div>

                    <div class="text-xs text-blue-100">
                        Management System
                    </div>
                </div>

            </div>


            {{-- Main message --}}
            <div class="max-w-lg">

                <div class="mb-5 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-sm font-medium text-blue-50 ring-1 ring-white/20">

                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>

                    Hệ thống đang hoạt động

                </div>

                <h1 class="text-4xl font-bold leading-tight text-white xl:text-5xl">
                    Quản lý phòng khám
                    <span class="text-blue-200">
                        đơn giản hơn.
                    </span>
                </h1>

                <p class="mt-6 max-w-md text-base leading-7 text-blue-100">
                    Quản lý bệnh nhân, lịch khám, hồ sơ điều trị,
                    đơn thuốc và thanh toán trên một hệ thống duy nhất.
                </p>

            </div>


            {{-- Footer --}}
            <div class="text-sm text-blue-200">
                © {{ date('Y') }} Clinic App
            </div>

        </div>

    </div>


    {{-- Right: Login --}}
    <div class="flex w-full items-center justify-center bg-white px-5 py-10 lg:w-1/2">

        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <div class="mb-10 flex items-center justify-center gap-3 lg:hidden">

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

                <span class="text-lg font-bold text-slate-900">
                    Clinic App
                </span>

            </div>


            {{-- Heading --}}
            <div class="mb-8">

                <h2 class="text-2xl font-bold text-slate-900">
                    Đăng nhập
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Đăng nhập để truy cập hệ thống quản lý phòng khám.
                </p>

            </div>


            {{-- Validation errors --}}
            @if ($errors->any())

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                    <div class="flex gap-3">

                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0 text-red-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01M10.3 3.8L2.6 17a2 2 0 001.7 3h15.4a2 2 0 001.7-3L13.7 3.8a2 2 0 00-3.4 0z"
                            />
                        </svg>

                        <div>

                            <p class="text-sm font-semibold text-red-800">
                                Không thể đăng nhập
                            </p>

                            <ul class="mt-1 space-y-1 text-sm text-red-700">

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif


            {{-- Login form --}}
            <form
                method="POST"
                action="{{ route('login.submit') }}"
                class="space-y-5"
            >

                @csrf


                {{-- Email --}}
                <div>

                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Email
                    </label>

                    <div class="relative">

                        <svg
                            class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-width="1.8"
                                d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
                            />
                        </svg>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            placeholder="you@example.com"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-900 placeholder:text-slate-400"
                        >

                    </div>

                </div>


                {{-- Password --}}
                <div>

                    <div class="mb-2 flex items-center justify-between">

                        <label
                            for="password"
                            class="block text-sm font-medium text-slate-700"
                        >
                            Mật khẩu
                        </label>

                        <a
                            href="#"
                            class="text-xs font-medium text-blue-600 hover:text-blue-700"
                        >
                            Quên mật khẩu?
                        </a>

                    </div>

                    <div class="relative">

                        <svg
                            class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-width="1.8"
                                d="M7 10V7a5 5 0 0110 0v3M6 10h12a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7a2 2 0 012-2z"
                            />
                        </svg>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="Nhập mật khẩu"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-900 placeholder:text-slate-400"
                        >

                    </div>

                </div>


                {{-- Remember --}}
                <div class="flex items-center">

                    <label class="flex cursor-pointer items-center gap-2">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        >

                        <span class="text-sm text-slate-600">
                            Ghi nhớ đăng nhập
                        </span>

                    </label>

                </div>


                {{-- Submit --}}
                <button
                    type="submit"
                    class="flex h-11 w-full items-center justify-center rounded-xl bg-blue-600 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100"
                >
                    Đăng nhập
                </button>

            </form>


            {{-- Security notice --}}
            <div class="mt-8 flex items-start gap-3 rounded-xl bg-slate-50 p-4">

                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 3l8 4v5c0 5-3.5 8.5-8 9-4.5-.5-8-4-8-9V7l8-4z"
                    />
                </svg>

                <p class="text-xs leading-5 text-slate-500">
                    Tài khoản được cấp quyền theo vai trò.
                    Không chia sẻ thông tin đăng nhập với người khác.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection