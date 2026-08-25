@extends('layouts.app')

@section('title', 'Thêm ca làm việc | Clinic App')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div>
        <a
            href="{{ route('schedules.web.index') }}"
            class="text-sm font-medium text-slate-500 hover:text-blue-600"
        >
            ← Quay lại
        </a>

        <h1 class="mt-4 text-2xl font-bold text-slate-900">
            Thêm ca làm việc
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Thiết lập thời gian nhận bệnh của bác sĩ.
        </p>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <p class="font-semibold text-red-700">
                Không thể tạo ca làm việc
            </p>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    {{-- Form --}}
    <form
        method="POST"
        action="{{ route('schedules.web.store') }}"
        class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
    >

        @csrf

        <div class="grid gap-5 md:grid-cols-2">

            {{-- Doctor --}}
            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Bác sĩ
                    <span class="text-red-500">*</span>
                </label>

                <select
                    name="doctor_id"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

                    <option value="">
                        -- Chọn bác sĩ --
                    </option>

                    @foreach($doctors as $doctor)

                        <option
                            value="{{ $doctor->id }}"
                            @selected(old('doctor_id') == $doctor->id)
                        >
                            {{ $doctor->user?->name ?? 'Chưa có tên' }}

                            @if($doctor->specialty?->name)
                                — {{ $doctor->specialty->name }}
                            @endif
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Date --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Ngày
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="date"
                    name="date"
                    value="{{ old('date') }}"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- Max patients --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Số bệnh nhân tối đa
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="number"
                    name="max_patients"
                    min="1"
                    max="100"
                    value="{{ old('max_patients', 10) }}"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- Start --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Giờ bắt đầu
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="time"
                    name="start_time"
                    value="{{ old('start_time') }}"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

            </div>


            {{-- End --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Giờ kết thúc
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="time"
                    name="end_time"
                    value="{{ old('end_time') }}"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

            </div>

        </div>


        {{-- Actions --}}
        <div class="mt-6 flex justify-end gap-3">

            <a
                href="{{ route('schedules.web.index') }}"
                class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Hủy
            </a>

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
            >
                Thêm ca làm việc
            </button>

        </div>

    </form>

</div>

@endsection