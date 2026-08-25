@extends('layouts.app')

@section('title', 'Kết quả khám bệnh | Clinic App')

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
                    href="{{ route('appointments.web.show', $examination->appointment) }}"
                    class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                    title="Quay lại lịch khám"
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
                            d="M15 18l-6-6 6-6"
                        />
                    </svg>
                </a>

                <div>
                    <h1 class="text-2xl font-bold text-slate-900">
                        Kết quả khám bệnh
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Chi tiết kết quả khám của bệnh nhân.
                    </p>
                </div>

            </div>
        </div>

        <span class="inline-flex w-fit rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-200">
            Đã hoàn tất
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
                    {{ strtoupper(substr($examination->patient?->full_name ?? '?', 0, 1)) }}
                </div>

                <div>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ $examination->patient?->full_name ?? 'Không xác định' }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $examination->patient?->phone ?? 'Chưa có số điện thoại' }}
                    </p>

                    @if($examination->patient?->email)
                        <p class="text-sm text-slate-500">
                            {{ $examination->patient->email }}
                        </p>
                    @endif
                </div>

            </div>

        </div>

    </div>


    {{-- Examination information --}}
    <div class="grid gap-6 lg:grid-cols-2">

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
                        {{ strtoupper(substr($examination->doctor?->user?->name ?? '?', 0, 1)) }}
                    </div>

                    <div>
                        <p class="font-semibold text-slate-900">
                            {{ $examination->doctor?->user?->name ?? 'Chưa xác định' }}
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $examination->doctor?->specialty?->name ?? 'Chưa có chuyên khoa' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- Examination time --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Thông tin khám
                </h2>
            </div>

            <div class="divide-y divide-slate-100">

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Mã hồ sơ khám
                    </span>

                    <span class="font-medium text-slate-900">
                        #{{ $examination->id }}
                    </span>
                </div>

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Mã lịch khám
                    </span>

                    <span class="font-medium text-slate-900">
                        #{{ $examination->appointment_id }}
                    </span>
                </div>

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Thời điểm khám
                    </span>

                    <span class="font-medium text-slate-900">
                        {{ optional($examination->examined_at)->format('d/m/Y H:i') ?? '—' }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- Diagnosis --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-900">
                Chẩn đoán
            </h2>
        </div>

        <div class="px-6 py-5">

            <div class="rounded-xl bg-slate-50 px-5 py-4 text-sm leading-7 text-slate-700">
                {!! nl2br(e($examination->diagnosis)) !!}
            </div>

        </div>

    </div>


    {{-- Notes --}}
    @if($examination->notes)

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Ghi chú
                </h2>
            </div>

            <div class="px-6 py-5">

                <div class="rounded-xl bg-slate-50 px-5 py-4 text-sm leading-7 text-slate-700">
                    {!! nl2br(e($examination->notes)) !!}
                </div>

            </div>

        </div>

    @endif


    {{-- Actions --}}
    <div class="flex flex-wrap justify-end gap-3">

        <a
            href="{{ route('appointments.web.show', $examination->appointment) }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            Quay lại lịch khám
        </a>

    </div>

</div>

@endsection