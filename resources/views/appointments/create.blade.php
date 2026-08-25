@extends('layouts.app')

@section('title', 'Đặt lịch khám | Clinic App')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    {{-- Header --}}
    <div>
        <a
            href="{{ route('appointments.web.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-blue-600"
        >
            ← Quay lại danh sách
        </a>

        <h1 class="mt-4 text-2xl font-bold text-slate-900">
            Đặt lịch khám
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Tạo lịch hẹn mới cho bệnh nhân.
        </p>
    </div>


    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <p class="font-semibold text-red-700">
                Không thể tạo lịch khám
            </p>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- Form --}}
    <form
        method="POST"
        action="{{ route('appointments.web.store') }}"
        class="space-y-6"
    >

        @csrf


        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-semibold text-slate-900">
                Thông tin lịch khám
            </h2>


            <div class="mt-6 grid gap-5 md:grid-cols-2">


                {{-- Patient --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Bệnh nhân
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="patient_id"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            -- Chọn bệnh nhân --
                        </option>

                        @foreach($patients as $patient)

                            <option
                                value="{{ $patient->id }}"
                                @selected(old('patient_id') == $patient->id)
                            >

                                {{ $patient->full_name }}

                                @if($patient->phone)
                                    - {{ $patient->phone }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Doctor --}}
                <div>

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
                                    - {{ $doctor->specialty->name }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Appointment date --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Ngày khám
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        id="appointment_date"
                        name="appointment_date"
                        value="{{ old('appointment_date') ? \Carbon\Carbon::parse(old('appointment_date'))->format('Y-m-d') : '' }}"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                    >

                </div>


                {{-- Appointment time --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Giờ khám
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="time"
                        id="appointment_time"
                        name="appointment_time"
                        value="{{ old('appointment_time') }}"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                    >

                </div>

            </div>


            {{-- Schedule information --}}
            <div
                id="schedule-info"
                class="mt-5 hidden rounded-xl border border-blue-200 bg-blue-50 px-4 py-3"
            >

                <p class="text-sm font-semibold text-blue-800">
                    Ca khám
                </p>

                <p
                    id="schedule-info-text"
                    class="mt-1 text-sm text-blue-700"
                ></p>

            </div>


            {{-- No schedule --}}
            <div
                id="schedule-error"
                class="mt-5 hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3"
            >

                <p class="text-sm font-medium text-red-700">
                    Không tìm thấy ca khám phù hợp.
                </p>

                <p class="mt-1 text-xs text-red-600">
                    Vui lòng chọn bác sĩ, ngày và giờ nằm trong ca làm việc của bác sĩ.
                </p>

            </div>


            {{-- Notes --}}
            <div class="mt-5">

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Ghi chú
                </label>

                <textarea
                    name="notes"
                    rows="4"
                    maxlength="500"
                    placeholder="Nhập ghi chú nếu cần..."
                    class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >{{ old('notes') }}</textarea>

                <p class="mt-1.5 text-xs text-slate-500">
                    Tối đa 500 ký tự.
                </p>

            </div>

        </div>


        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">

            <a
                href="{{ route('appointments.web.index') }}"
                class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Hủy
            </a>

            <button
                id="submit-button"
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
            >
                Tạo lịch khám
            </button>

        </div>

    </form>

</div>


{{-- Schedule data --}}
<script>
    const schedules = {!! json_encode(
        $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'doctor_id' => $schedule->doctor_id,
                'date' => $schedule->date?->format('Y-m-d'),
                'start_time' => substr($schedule->start_time, 0, 5),
                'end_time' => substr($schedule->end_time, 0, 5),
                'max_patients' => $schedule->max_patients,
                'current_patients' => $schedule->current_patients,
            ];
        })->values()->toArray()
    ) !!};

    const doctorSelect = document.querySelector('[name="doctor_id"]');
    const dateInput = document.getElementById('appointment_date');
    const timeInput = document.getElementById('appointment_time');

    const scheduleInfo = document.getElementById('schedule-info');
    const scheduleInfoText = document.getElementById('schedule-info-text');

    const scheduleError = document.getElementById('schedule-error');
    const submitButton = document.getElementById('submit-button');


    function checkSchedule() {
    const doctorId = doctorSelect.value;
    const date = dateInput.value;
    const time = timeInput.value;

    scheduleInfo.classList.add('hidden');
    scheduleError.classList.add('hidden');

    submitButton.disabled = false;
    submitButton.classList.remove(
        'opacity-50',
        'cursor-not-allowed'
    );

    if (!doctorId || !date || !time) {
        return;
    }

    const schedule = schedules.find(function (item) {
        return (
            String(item.doctor_id) === String(doctorId)
            &&
            String(item.date) === String(date)
            &&
            time >= item.start_time
            &&
            time < item.end_time
            &&
            Number(item.current_patients) < Number(item.max_patients)
        );
    });

    if (!schedule) {
        scheduleError.classList.remove('hidden');

        submitButton.disabled = true;
        submitButton.classList.add(
            'opacity-50',
            'cursor-not-allowed'
        );

        return;
    }

    const remaining =
        Number(schedule.max_patients) -
        Number(schedule.current_patients);

    scheduleInfoText.textContent =
        `Ca khám: ${schedule.start_time} - ${schedule.end_time} | Còn ${remaining} chỗ`;

    scheduleInfo.classList.remove('hidden');
}

    doctorSelect.addEventListener('change', checkSchedule);

    dateInput.addEventListener('change', checkSchedule);

    timeInput.addEventListener('change', checkSchedule);


    checkSchedule();

</script>

@endsection