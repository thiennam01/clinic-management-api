@extends('layouts.app')

@section('title', 'Dashboard | Clinic App')

@section('page-title', 'Tổng quan')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">

        <div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Chào mừng trở lại,
                {{ auth()->user()->name }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Đây là tình hình phòng khám hôm nay.
            </p>

        </div>

        <div class="text-sm text-slate-500">
            {{ now()->locale('vi')->translatedFormat('l, d/m/Y') }}
        </div>

    </div>


    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Appointments --}}
        <div class="card p-5">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Lịch khám hôm nay
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $stats['appointments_today'] }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-width="1.8"
                            d="M8 2v4M16 2v4M3 10h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
                        />
                    </svg>

                </div>

            </div>

            <p class="mt-3 text-xs text-slate-500">
                Lịch được đặt trong ngày
            </p>

        </div>


        {{-- Waiting --}}
        <div class="card p-5">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Chờ khám
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $stats['waiting'] }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-width="1.8"
                            d="M12 7v5l3 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                </div>

            </div>

            <p class="mt-3 text-xs text-slate-500">
                Cần được tiếp nhận
            </p>

        </div>


        {{-- In progress --}}
        <div class="card p-5">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Đang khám
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $stats['in_progress'] }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-width="1.8"
                            d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                </div>

            </div>

            <p class="mt-3 text-xs text-slate-500">
                Lịch đã được xác nhận
            </p>

        </div>


        {{-- Completed --}}
        <div class="card p-5">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Hoàn tất
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $stats['completed'] }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                </div>

            </div>

            <p class="mt-3 text-xs text-slate-500">
                Đã hoàn thành trong ngày
            </p>

        </div>

    </div>


    {{-- Main content --}}
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">

        {{-- Today's appointments --}}
        <div class="card overflow-hidden">

            <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="font-semibold text-slate-900">
                        Lịch khám hôm nay
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Các lịch khám được sắp xếp theo thời gian
                    </p>

                </div>

                <a
                    href="{{ url('/appointments') }}"
                    class="btn-primary inline-flex items-center justify-center"
                >
                    Xem lịch khám
                </a>

            </div>


            @if ($appointments->isEmpty())

                <div class="flex flex-col items-center justify-center px-5 py-14 text-center">

                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">

                        <svg
                            class="h-7 w-7 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-width="1.7"
                                d="M8 2v4M16 2v4M3 10h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
                            />
                        </svg>

                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-slate-800">
                        Chưa có lịch khám
                    </h3>

                    <p class="mt-1 max-w-sm text-xs text-slate-500">
                        Hôm nay chưa có lịch khám nào được ghi nhận.
                    </p>

                </div>

            @else

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="border-b border-slate-200 bg-slate-50">

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Giờ
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Bệnh nhân
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Bác sĩ
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Chuyên khoa
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Trạng thái
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach ($appointments as $appointment)

                                @php

                                    $status = match ($appointment->status) {
                                        'pending' => [
                                            'label' => 'Chờ khám',
                                            'class' => 'bg-amber-50 text-amber-700',
                                        ],

                                        'confirmed' => [
                                            'label' => 'Đang khám',
                                            'class' => 'bg-blue-50 text-blue-700',
                                        ],

                                        'completed' => [
                                            'label' => 'Hoàn tất',
                                            'class' => 'bg-emerald-50 text-emerald-700',
                                        ],

                                        'cancelled' => [
                                            'label' => 'Đã hủy',
                                            'class' => 'bg-red-50 text-red-700',
                                        ],

                                        default => [
                                            'label' => $appointment->status,
                                            'class' => 'bg-slate-100 text-slate-600',
                                        ],
                                    };

                                @endphp


                                <tr class="hover:bg-slate-50">

                                    <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-slate-800">
                                        {{ $appointment->appointment_date->format('H:i') }}
                                    </td>


                                    <td class="px-5 py-4">

                                        <div class="font-medium text-slate-900">
                                            {{ $appointment->patient?->full_name ?? 'Không xác định' }}
                                        </div>

                                        <div class="text-xs text-slate-500">
                                            {{ $appointment->patient?->code ?? '—' }}
                                        </div>

                                    </td>


                                    <td class="px-5 py-4 text-sm text-slate-600">

                                        {{ $appointment->schedule?->doctor?->user?->name ?? '—' }}

                                    </td>


                                    <td class="px-5 py-4 text-sm text-slate-600">

                                        {{ $appointment->schedule?->doctor?->specialty?->name ?? '—' }}

                                    </td>


                                    <td class="px-5 py-4">

                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $status['class'] }}">
                                            {{ $status['label'] }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>


        {{-- Waiting patients --}}
<div class="card overflow-hidden">

    {{-- Header --}}
    <div class="border-b border-slate-200 p-5">

        <div class="flex items-start justify-between gap-3">

            <div>
                <h2 class="font-semibold text-slate-900">
                    Đang chờ khám
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Bệnh nhân đã đặt lịch và đang chờ tiếp nhận
                </p>
            </div>

            @if ($stats['waiting'] > 0)
                <span class="inline-flex shrink-0 items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                    {{ $stats['waiting'] }} bệnh nhân
                </span>
            @endif

        </div>

    </div>


    {{-- Empty --}}
    @if ($waitingAppointments->isEmpty())

        <div class="px-5 py-12 text-center">

            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50">

                <svg
                    class="h-6 w-6 text-emerald-600"
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

            </div>

            <p class="mt-3 text-sm font-semibold text-slate-800">
                Không có bệnh nhân chờ
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Tất cả bệnh nhân hôm nay đã được tiếp nhận.
            </p>

        </div>

    @else

        {{-- Waiting list --}}
        <div class="divide-y divide-slate-100">

            @foreach ($waitingAppointments as $appointment)

                <div class="px-5 py-4">

                    <div class="flex items-start gap-3">

                        {{-- Avatar --}}
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-700">
                            {{ strtoupper(substr($appointment->patient?->full_name ?? '?', 0, 1)) }}
                        </div>


                        {{-- Patient info --}}
                        <div class="min-w-0 flex-1">

                            <p class="truncate text-sm font-semibold text-slate-800">
                                {{ $appointment->patient?->full_name ?? 'Không xác định' }}
                            </p>

                            <p class="mt-0.5 text-xs text-slate-500">

                                {{ $appointment->patient?->code ?? '—' }}

                                <span class="mx-1">
                                    •
                                </span>

                                {{ $appointment->appointment_date->format('H:i') }}

                            </p>

                            <p class="mt-0.5 truncate text-xs text-slate-400">

                                {{ $appointment->schedule?->doctor?->user?->name ?? 'Chưa phân công' }}

                            </p>

                        </div>


                        {{-- Waiting indicator --}}
                        <span
                            class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-amber-400"
                            title="Đang chờ"
                        ></span>

                    </div>


                    {{-- Action --}}
                    <div class="mt-3 pl-[52px]">

                        <form
                            method="POST"
                            action="{{ route('appointments.web.update-status', $appointment) }}"
                        >

                            @csrf
                            @method('PATCH')

                            <input
                                type="hidden"
                                name="status"
                                value="confirmed"
                            >

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >

                                <svg
                                    class="h-4 w-4"
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

                                Tiếp nhận bệnh nhân

                            </button>

                        </form>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- View all --}}
        @if ($stats['waiting'] > $waitingAppointments->count())

            <div class="border-t border-slate-200 p-4">

                <a
                    href="{{ route('appointments.web.index', ['status' => 'pending']) }}"
                    class="flex items-center justify-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700"
                >
                    Xem tất cả bệnh nhân đang chờ

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>

                </a>

            </div>

        @endif

    @endif

</div>


    {{-- Quick actions --}}
    <div class="card p-5">

        <div class="mb-4">

            <h2 class="font-semibold text-slate-900">
                Thao tác nhanh
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Truy cập nhanh các chức năng thường dùng
            </p>

        </div>


        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">

            <a
                href="{{ url('/patients') }}"
                class="group rounded-xl border border-slate-200 p-4 transition hover:border-blue-200 hover:bg-blue-50/40"
            >

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100">

                        <svg
                            class="h-5 w-5"
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

                    </div>

                    <div>

                        <p class="text-sm font-semibold text-slate-800">
                            Bệnh nhân
                        </p>

                        <p class="text-xs text-slate-500">
                            Quản lý hồ sơ
                        </p>

                    </div>

                </div>

            </a>


            <a
                href="{{ url('/appointments') }}"
                class="group rounded-xl border border-slate-200 p-4 transition hover:border-blue-200 hover:bg-blue-50/40"
            >

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100">

                        <svg
                            class="h-5 w-5"
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

                    </div>

                    <div>

                        <p class="text-sm font-semibold text-slate-800">
                            Lịch khám
                        </p>

                        <p class="text-xs text-slate-500">
                            Đặt và quản lý lịch
                        </p>

                    </div>

                </div>

            </a>


            <a
                href="{{ url('/examinations') }}"
                class="group rounded-xl border border-slate-200 p-4 transition hover:border-blue-200 hover:bg-blue-50/40"
            >

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100">

                        <svg
                            class="h-5 w-5"
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

                    </div>

                    <div>

                        <p class="text-sm font-semibold text-slate-800">
                            Khám bệnh
                        </p>

                        <p class="text-xs text-slate-500">
                            Tiếp nhận và khám
                        </p>

                    </div>

                </div>

            </a>


            <a
                href="{{ url('/prescriptions') }}"
                class="group rounded-xl border border-slate-200 p-4 transition hover:border-blue-200 hover:bg-blue-50/40"
            >

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 text-purple-600 group-hover:bg-purple-100">

                        <svg
                            class="h-5 w-5"
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

                    </div>

                    <div>

                        <p class="text-sm font-semibold text-slate-800">
                            Đơn thuốc
                        </p>

                        <p class="text-xs text-slate-500">
                            Kê đơn và cấp thuốc
                        </p>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>

@endsection