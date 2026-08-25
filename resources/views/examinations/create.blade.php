@extends('layouts.app')

@section('title', 'Khám bệnh | Clinic App')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-3">

                <a
                    href="{{ route('appointments.web.show', $appointment) }}"
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
                        Khám bệnh
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Ghi nhận kết quả khám cho bệnh nhân.
                    </p>
                </div>

            </div>
        </div>

        <span class="inline-flex w-fit rounded-full bg-indigo-50 px-3 py-1.5 text-sm font-semibold text-indigo-700 ring-1 ring-indigo-200">
            Đã xác nhận
        </span>

    </div>


    {{-- Patient / Appointment information --}}
    <div class="grid gap-6 lg:grid-cols-2">

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


        {{-- Appointment --}}
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
                        Bác sĩ
                    </span>

                    <span class="font-medium text-slate-900">
                        {{ $appointment->schedule?->doctor?->user?->name ?? 'Chưa phân công' }}
                    </span>
                </div>

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Chuyên khoa
                    </span>

                    <span class="font-medium text-slate-900">
                        {{ $appointment->schedule?->doctor?->specialty?->name ?? '—' }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- Examination form --}}
    <form
        action="{{ route('examinations.web.store', $appointment) }}"
        method="POST"
        class="rounded-2xl border border-slate-200 bg-white shadow-sm"
    >

        @csrf

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-900">
                Kết quả khám bệnh
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Nhập chẩn đoán và ghi chú của bác sĩ.
            </p>
        </div>


        <div class="space-y-6 p-6">

            {{-- Diagnosis --}}
            <div>

                <label
                    for="diagnosis"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Chẩn đoán
                    <span class="text-red-500">*</span>
                </label>

                <textarea
                    id="diagnosis"
                    name="diagnosis"
                    rows="5"
                    required
                    maxlength="5000"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    placeholder="Nhập kết quả chẩn đoán..."
                >{{ old('diagnosis') }}</textarea>

                @error('diagnosis')
                    <p class="mt-1.5 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Notes --}}
            <div>

                <label
                    for="notes"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Ghi chú
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="5"
                    maxlength="5000"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    placeholder="Nhập ghi chú thêm nếu có..."
                >{{ old('notes') }}</textarea>

                @error('notes')
                    <p class="mt-1.5 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Examined at --}}
            <div>

                <label
                    for="examined_at"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Thời điểm khám
                </label>

                <input
                    type="datetime-local"
                    id="examined_at"
                    name="examined_at"
                    value="{{ old('examined_at', now()->format('Y-m-d\TH:i')) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                @error('examined_at')
                    <p class="mt-1.5 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>


        {{-- Actions --}}
        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200 px-6 py-4">

            <a
                href="{{ route('appointments.web.show', $appointment) }}"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Hủy
            </a>

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Lưu kết quả khám
            </button>

        </div>

    </form>

</div>

@endsection