@extends('layouts.app')

@section('title', 'Hồ sơ bệnh nhân | Clinic App')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">

        <div class="flex items-center gap-4">

            <a
                href="{{ route('web.patients.index') }}"
                class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-700"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </a>

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Hồ sơ bệnh nhân
                </p>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ $patient->full_name }}
                </h1>
            </div>

        </div>

        <a
            href="{{ route('web.patients.edit', $patient->id) }}"
            class="btn-primary inline-flex items-center gap-2"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                />
            </svg>

            Chỉnh sửa
        </a>

    </div>


    {{-- Success message --}}
    @if(session('success'))

        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">

            <div class="flex gap-3">

                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M5 13l4 4L19 7"
                    />
                </svg>

                <p class="text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </p>

            </div>

        </div>

    @endif


    {{-- Patient identity --}}
    <div class="card overflow-hidden">

        <div class="border-b border-slate-200 px-5 py-4">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="font-semibold text-slate-900">
                        Thông tin bệnh nhân
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Thông tin định danh và liên hệ.
                    </p>
                </div>

                <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700">
                    {{ $patient->code }}
                </span>

            </div>

        </div>


        <div class="grid gap-6 p-5 md:grid-cols-2">

            {{-- Full name --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Họ và tên
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $patient->full_name }}
                </p>
            </div>


            {{-- Gender --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Giới tính
                </p>

                <p class="mt-1 text-sm text-slate-700">
                    @switch($patient->gender)
                        @case('male')
                            Nam
                            @break

                        @case('female')
                            Nữ
                            @break

                        @case('other')
                            Khác
                            @break

                        @default
                            Chưa cập nhật
                    @endswitch
                </p>
            </div>


            {{-- Date of birth --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Ngày sinh
                </p>

                <p class="mt-1 text-sm text-slate-700">
                    {{ $patient->date_of_birth?->format('d/m/Y') ?? 'Chưa cập nhật' }}
                </p>
            </div>


            {{-- Phone --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Số điện thoại
                </p>

                <p class="mt-1 text-sm text-slate-700">
                    {{ $patient->phone ?: 'Chưa cập nhật' }}
                </p>
            </div>


            {{-- Email --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Email
                </p>

                <p class="mt-1 text-sm text-slate-700">
                    {{ $patient->email ?: 'Chưa cập nhật' }}
                </p>
            </div>


            {{-- Address --}}
            <div class="md:col-span-2">

                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Địa chỉ
                </p>

                <p class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-700">
                    {{ $patient->address ?: 'Chưa cập nhật' }}
                </p>

            </div>

        </div>

    </div>


    {{-- System information --}}
    <div class="card overflow-hidden">

        <div class="border-b border-slate-200 px-5 py-4">

            <h2 class="font-semibold text-slate-900">
                Thông tin hệ thống
            </h2>

        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Mã bệnh nhân
                </p>

                <p class="mt-1 font-mono text-sm font-semibold text-slate-700">
                    {{ $patient->code }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Ngày tạo hồ sơ
                </p>

                <p class="mt-1 text-sm text-slate-700">
                    {{ $patient->created_at?->format('d/m/Y H:i') ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Cập nhật lần cuối
                </p>

                <p class="mt-1 text-sm text-slate-700">
                    {{ $patient->updated_at?->format('d/m/Y H:i') ?? '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">

        <a
            href="{{ route('web.patients.index') }}"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        >
            Quay lại danh sách
        </a>

        <a
            href="{{ route('web.patients.edit', $patient->id) }}"
            class="btn-primary inline-flex items-center justify-center gap-2"
        >
            Chỉnh sửa hồ sơ
        </a>

    </div>

</div>

@endsection