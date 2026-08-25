@extends('layouts.app')

@section('title', 'Chỉnh sửa lịch khám | Clinic App')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-3">

                <a
                    href="{{ route('appointments.web.show', $appointment) }}"
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
                        Chỉnh sửa lịch khám
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Cập nhật lịch khám và thông tin của bệnh nhân.
                    </p>
                </div>

            </div>
        </div>

    </div>


    {{-- Errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <p class="font-semibold text-red-800">
                Không thể cập nhật lịch khám
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    <form
        method="POST"
        action="{{ route('appointments.web.update', $appointment) }}"
        class="space-y-6"
    >

        @csrf
        @method('PUT')


        {{-- Patient --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Bệnh nhân
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Bệnh nhân của lịch khám này.
                </p>
            </div>

            <div class="p-6">

                <div class="flex items-center gap-4 rounded-xl bg-slate-50 p-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                        {{ strtoupper(substr($appointment->patient?->full_name ?? '?', 0, 1)) }}
                    </div>

                    <div>
                        <p class="font-semibold text-slate-900">
                            {{ $appointment->patient?->full_name ?? 'Không xác định' }}
                        </p>

                        <p class="text-sm text-slate-500">
                            {{ $appointment->patient?->phone ?? 'Chưa có số điện thoại' }}
                        </p>
                    </div>

                </div>

                <input
                    type="hidden"
                    name="patient_id"
                    value="{{ $appointment->patient_id }}"
                >

            </div>

        </div>


        {{-- Schedule --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">

                <h2 class="font-semibold text-slate-900">
                    Lịch khám
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Chọn lịch khám để thay đổi bác sĩ, ngày hoặc giờ khám.
                </p>

            </div>

            <div class="p-6">

                <label
                    for="schedule_id"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Khung lịch khám <span class="text-red-500">*</span>
                </label>

                <select
                    id="schedule_id"
                    name="schedule_id"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

                    <option value="">
                        -- Chọn lịch khám --
                    </option>

                    @foreach($schedules as $schedule)

                        @php
                            $selected = old(
                                'schedule_id',
                                $appointment->schedule_id
                            ) == $schedule->id;
                        @endphp

                        <option
                            value="{{ $schedule->id }}"
                            @selected($selected)
                        >
                            {{ optional($schedule->date)->format('d/m/Y') }}
                            ·
                            {{ substr($schedule->start_time, 0, 5) }}
                            - {{ substr($schedule->end_time, 0, 5) }}
                            ·
                            {{ $schedule->doctor?->user?->name ?? 'Chưa phân công' }}
                            ·
                            {{ $schedule->doctor?->specialty?->name ?? 'Chưa có chuyên khoa' }}
                            · Còn {{ max(0, $schedule->max_patients - $schedule->current_patients) }} chỗ
                        </option>

                    @endforeach

                </select>

                @error('schedule_id')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror


                {{-- Current schedule preview --}}
                @php
                    $currentSchedule = $schedules->firstWhere(
                        'id',
                        old('schedule_id', $appointment->schedule_id)
                    );
                @endphp

                @if($currentSchedule)

                    <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">
                            Lịch đang chọn
                        </p>

                        <div class="mt-2 grid gap-3 sm:grid-cols-3">

                            <div>
                                <p class="text-xs text-slate-500">
                                    Bác sĩ
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $currentSchedule->doctor?->user?->name ?? 'Chưa phân công' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Ngày
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ optional($currentSchedule->date)->format('d/m/Y') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Thời gian
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ substr($currentSchedule->start_time, 0, 5) }}
                                    -
                                    {{ substr($currentSchedule->end_time, 0, 5) }}
                                </p>
                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- Status --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">

                <h2 class="font-semibold text-slate-900">
                    Trạng thái
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Cập nhật trạng thái của lịch hẹn.
                </p>

            </div>

            <div class="p-6">

                <select
                    name="status"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

                    @foreach($statuses as $key => $label)

                        <option
                            value="{{ $key }}"
                            @selected(old('status', $appointment->status) === $key)
                        >
                            {{ $label }}
                        </option>

                    @endforeach

                </select>

                @error('status')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>


        {{-- Notes --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">

                <h2 class="font-semibold text-slate-900">
                    Ghi chú
                </h2>

            </div>

            <div class="p-6">

                <textarea
                    name="notes"
                    rows="4"
                    placeholder="Nhập ghi chú nếu cần..."
                    class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >{{ old('notes', $appointment->notes) }}</textarea>

                @error('notes')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>


        {{-- Actions --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('appointments.web.show', $appointment) }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Hủy
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
            >
                Lưu thay đổi
            </button>

        </div>

    </form>

</div>

@endsection