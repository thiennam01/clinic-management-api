@extends('layouts.app')

@section('title', 'Chi tiết lịch khám | Clinic App')

@section('content')

@if(session('success'))
    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
        {{ session('error') }}
    </div>
@endif

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('appointments.web.index') }}"
                    class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                    title="Quay lại"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M15 18l-6-6 6-6"
                        />
                    </svg>
                </a>

                <div>
                    <h1 class="text-2xl font-bold text-slate-900">
                        Chi tiết lịch khám
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Thông tin chi tiết lịch hẹn của bệnh nhân.
                    </p>
                </div>
            </div>
        </div>

        @php
            $status = $appointment->status;

            $statusConfig = [
                'pending' => [
                    'label' => 'Chờ xử lý',
                    'class' => 'bg-amber-50 text-amber-700 ring-amber-200',
                ],
                'scheduled' => [
                    'label' => 'Đã đặt lịch',
                    'class' => 'bg-blue-50 text-blue-700 ring-blue-200',
                ],
                'confirmed' => [
                    'label' => 'Đã xác nhận',
                    'class' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
                ],
                'completed' => [
                    'label' => 'Hoàn tất',
                    'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                ],
                'cancelled' => [
                    'label' => 'Đã hủy',
                    'class' => 'bg-red-50 text-red-700 ring-red-200',
                ],
            ];

            $config = $statusConfig[$status] ?? [
                'label' => ucfirst($status),
                'class' => 'bg-slate-50 text-slate-600 ring-slate-200',
            ];
        @endphp

        <span
            class="inline-flex w-fit rounded-full px-3 py-1.5 text-sm font-semibold ring-1 {{ $config['class'] }}"
        >
            {{ $config['label'] }}
        </span>

    </div>


    {{-- Patient --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-900">
                Thông tin bệnh nhân
            </h2>
        </div>

        <div class="p-6">

            <div class="flex items-center gap-4">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg font-bold text-blue-700">
                    {{ strtoupper(substr($appointment->patient?->full_name ?? '?', 0, 1)) }}
                </div>

                <div>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ $appointment->patient?->full_name ?? 'Không xác định' }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $appointment->patient?->phone ?? 'Chưa có số điện thoại' }}
                    </p>

                    @if($appointment->patient?->email)
                        <p class="text-sm text-slate-500">
                            {{ $appointment->patient->email }}
                        </p>
                    @endif
                </div>

            </div>

        </div>

    </div>


    {{-- Appointment information --}}
    <div class="grid gap-6 lg:grid-cols-2">

        {{-- Schedule --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Thông tin lịch khám
                </h2>
            </div>

            <div class="divide-y divide-slate-100">

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Ngày khám
                    </span>

                    <span class="font-medium text-slate-900">
                        {{ optional($appointment->appointment_date)->format('d/m/Y') ?? '—' }}
                    </span>
                </div>

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Giờ khám
                    </span>

                    <span class="font-medium text-slate-900">
                        {{ optional($appointment->appointment_date)->format('H:i') ?? '—' }}
                    </span>
                </div>

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Trạng thái
                    </span>

                    <span
                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $config['class'] }}"
                    >
                        {{ $config['label'] }}
                    </span>
                </div>

                @if($appointment->id)
                    <div class="flex items-center justify-between px-6 py-4">
                        <span class="text-sm text-slate-500">
                            Mã lịch khám
                        </span>

                        <span class="font-medium text-slate-900">
                            #{{ $appointment->id }}
                        </span>
                    </div>
                @endif

            </div>

        </div>


        {{-- Doctor --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Thông tin bác sĩ
                </h2>
            </div>

            <div class="p-6">

                <div class="flex items-center gap-4">

                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-700">
                        {{ strtoupper(substr($appointment->schedule?->doctor?->user?->name ?? '?', 0, 1)) }}
                    </div>

                    <div>
                        <p class="font-semibold text-slate-900">
                            {{ $appointment->schedule?->doctor?->user?->name ?? 'Chưa phân công' }}
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $appointment->schedule?->doctor?->specialty?->name ?? 'Chưa có chuyên khoa' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Notes --}}
    @if(!empty($appointment->notes))
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Ghi chú
                </h2>
            </div>

            <div class="px-6 py-5 text-sm leading-6 text-slate-600">
                {{ $appointment->notes }}
            </div>

        </div>
    @endif



    {{-- Actions --}}
    <div class="flex flex-wrap items-center justify-end gap-3">

        {{-- Quay lại --}}
        <a
            href="{{ route('appointments.web.index') }}"
            style="display:inline-block; padding:10px 16px; background:#fff; color:#334155; border:1px solid #e2e8f0; border-radius:10px; font-weight:600;"
        >
            Quay lại
        </a>


        {{-- Chỉnh sửa --}}
        @if(!in_array($status, ['completed', 'cancelled']))
            <a
                href="{{ route('appointments.web.edit', $appointment) }}"
                style="display:inline-block; padding:10px 16px; background:#fff; color:#334155; border:1px solid #e2e8f0; border-radius:10px; font-weight:600;"
            >
                Chỉnh sửa lịch khám
            </a>
        @endif


        {{-- PENDING --}}
        @if($status === 'pending')

            {{-- Xác nhận --}}
            <form
                method="POST"
                action="{{ route('appointments.web.update-status', $appointment) }}"
                style="display:inline;"
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
                    style="padding:10px 16px; background:#4f46e5; color:#fff; border-radius:10px; font-weight:600;"
                    onclick="return confirm('Xác nhận lịch khám này?')"
                >
                    Xác nhận lịch
                </button>
            </form>


            {{-- Hủy --}}
            <form
                method="POST"
                action="{{ route('appointments.web.update-status', $appointment) }}"
                style="display:inline;"
            >
                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="status"
                    value="cancelled"
                >

                <button
                    type="submit"
                    style="padding:10px 16px; background:#dc2626; color:#fff; border-radius:10px; font-weight:600;"
                    onclick="return confirm('Bạn có chắc muốn hủy lịch khám này?')"
                >
                    Hủy lịch
                </button>
            </form>

        @endif


        {{-- CONFIRMED --}}
        @if($status === 'confirmed')

            @if(!$appointment->examination)

                <a
                    href="{{ route('examinations.web.create', $appointment) }}"
                    style="display:inline-block; padding:10px 16px; background:#059669; color:#fff; border-radius:10px; font-weight:600;"
                >
                    Bắt đầu khám
                </a>

            @endif

            {{-- Hủy lịch --}}
            <form
                method="POST"
                action="{{ route('appointments.web.update-status', $appointment) }}"
                style="display:inline;"
            >
                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="status"
                    value="cancelled"
                >

                <button
                    type="submit"
                    style="padding:10px 16px; background:#dc2626; color:#fff; border-radius:10px; font-weight:600;"
                    onclick="return confirm('Bạn có chắc muốn hủy lịch khám này?')"
                >
                    Hủy lịch
                </button>
            </form>

        @endif


        {{-- Đã có examination --}}
        @if($appointment->examination)
            <a
                href="{{ route('examinations.web.show', $appointment->examination) }}"
                style="display:inline-block; padding:10px 16px; background:#4f46e5; color:#fff; border-radius:10px; font-weight:600;"
            >
                Xem kết quả khám
            </a>
        @endif

    </div>


@endsection