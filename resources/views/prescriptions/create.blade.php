@extends('layouts.app')

@section('title', 'Tạo đơn thuốc | Clinic App')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

{{-- Header --}}
<div>
    <div class="flex items-center gap-3">

        <a
            href="{{ route('prescriptions.web.index') }}"
            class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
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
                Tạo đơn thuốc
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Tạo đơn thuốc dựa trên kết quả khám của bệnh nhân.
            </p>
        </div>

    </div>
</div>


{{-- Success --}}
@if(session('success'))
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
        {{ session('success') }}
    </div>
@endif


{{-- Errors --}}
@if($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

        <div class="mb-2 font-semibold">
            Không thể tạo đơn thuốc
        </div>

        <ul class="list-disc space-y-1 pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>
@endif


<form
    method="POST"
    action="{{ route('prescriptions.web.store') }}"
    id="prescription-form"
    class="space-y-6"
>

    @csrf


    {{-- Examination --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h2 class="font-semibold text-slate-900">
                Thông tin phiếu khám
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Chọn phiếu khám đã hoàn tất để tạo đơn thuốc.
            </p>

        </div>


        <div class="grid gap-6 p-6 md:grid-cols-2">

            {{-- Examination select --}}
            <div class="md:col-span-2">

                <label
                    for="examination_id"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Phiếu khám <span class="text-red-500">*</span>
                </label>

                <select
                    name="examination_id"
                    id="examination_id"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                    <option value="">
                        -- Chọn phiếu khám --
                    </option>

                    @foreach($examinations as $examination)

                        <option
                            value="{{ $examination->id }}"
                            data-doctor="{{ $examination->doctor_id }}"
                            data-patient="{{ $examination->patient?->full_name }}"
                            @selected(old('examination_id') == $examination->id)
                        >
                            #{{ $examination->id }}
                            —
                            {{ $examination->patient?->full_name ?? 'Chưa xác định bệnh nhân' }}
                            —
                            {{ optional($examination->examined_at)->format('d/m/Y H:i') }}
                        </option>

                    @endforeach

                </select>

                @if($examinations->isEmpty())
                    <p class="mt-2 text-xs text-amber-600">
                        Không có phiếu khám nào đủ điều kiện để tạo đơn thuốc.
                    </p>
                @else
                    <p class="mt-2 text-xs text-slate-400">
                        Chỉ hiển thị các phiếu khám chưa có đơn thuốc.
                    </p>
                @endif

            </div>


            {{-- Patient --}}
            <div class="rounded-xl bg-slate-50 p-4">

                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Bệnh nhân
                </p>

                <p
                    id="patient-name"
                    class="mt-1 font-semibold text-slate-900"
                >
                    Chưa chọn phiếu khám
                </p>

            </div>


            {{-- Doctor --}}
            <div class="rounded-xl bg-slate-50 p-4">

                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Bác sĩ
                </p>

                <p
                    id="doctor-name"
                    class="mt-1 font-semibold text-slate-900"
                >
                    Chưa chọn phiếu khám
                </p>

            </div>


            <input
                type="hidden"
                name="doctor_id"
                id="doctor_id"
                value="{{ old('doctor_id') }}"
            >

        </div>

    </div>


    {{-- Medicines --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="font-semibold text-slate-900">
                    Danh sách thuốc
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Thêm các loại thuốc và hướng dẫn sử dụng cho bệnh nhân.
                </p>

            </div>


            <button
                type="button"
                id="add-medicine"
                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
            >
                + Thêm thuốc
            </button>

        </div>


        <div class="p-6">

            <div
                id="medicine-list"
                class="space-y-4"
            ></div>


            {{-- Empty state --}}
            <div
                id="empty-medicine"
                class="rounded-xl border border-dashed border-slate-300 px-6 py-10 text-center"
            >

                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100">

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
                            d="M9 5h6M9 3h6a2 2 0 012 2v1h1a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h1V5a2 2 0 012-2z"
                        />
                    </svg>

                </div>

                <p class="mt-3 text-sm font-medium text-slate-500">
                    Chưa có thuốc nào
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Nhấn "Thêm thuốc" để thêm thuốc vào đơn.
                </p>

            </div>

        </div>

    </div>


    {{-- Notes --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h2 class="font-semibold text-slate-900">
                Ghi chú đơn thuốc
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Có thể thêm các lưu ý hoặc hướng dẫn bổ sung cho bệnh nhân.
            </p>

        </div>


        <div class="p-6">

            <textarea
                name="notes"
                rows="4"
                placeholder="Nhập ghi chú hoặc hướng dẫn bổ sung..."
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >{{ old('notes') }}</textarea>

        </div>

    </div>


    {{-- Actions --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

        <a
            href="{{ route('prescriptions.web.index') }}"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Hủy
        </a>

        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
        >
            Tạo đơn thuốc
        </button>

    </div>

</form>

</div>

{{-- Medicine row template --}} <template id="medicine-template">

<div class="medicine-row rounded-xl border border-slate-200 bg-slate-50 p-4">

    <div class="grid gap-4 md:grid-cols-12">

        {{-- Medicine --}}
        <div class="md:col-span-4">

            <label class="mb-2 block text-xs font-medium text-slate-600">
                Thuốc <span class="text-red-500">*</span>
            </label>

            <select
                name="items[INDEX][medicine_id]"
                required
                class="medicine-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >

                <option value="">
                    -- Chọn thuốc --
                </option>

                @foreach($medicines as $medicine)

                    <option
                        value="{{ $medicine->id }}"
                        data-stock="{{ $medicine->stock }}"
                    >
                        {{ $medicine->name }}
                        — Tồn: {{ $medicine->stock }}
                        {{ $medicine->unit }}
                    </option>

                @endforeach

            </select>

            <p class="medicine-stock mt-1 text-xs text-slate-400">
                Chọn thuốc để xem tồn kho
            </p>

        </div>


        {{-- Quantity --}}
        <div class="md:col-span-2">

            <label class="mb-2 block text-xs font-medium text-slate-600">
                Số lượng <span class="text-red-500">*</span>
            </label>

            <input
                type="number"
                name="items[INDEX][quantity]"
                min="1"
                value="1"
                required
                class="quantity-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >

        </div>


        {{-- Dosage --}}
        <div class="md:col-span-2">

            <label class="mb-2 block text-xs font-medium text-slate-600">
                Liều dùng
            </label>

            <input
                type="text"
                name="items[INDEX][dosage]"
                placeholder="VD: 1 viên/lần"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >

        </div>


        {{-- Usage --}}
        <div class="md:col-span-3">

            <label class="mb-2 block text-xs font-medium text-slate-600">
                Cách dùng
            </label>

            <input
                type="text"
                name="items[INDEX][usage_instruction]"
                placeholder="VD: Uống sau ăn"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >

        </div>


        {{-- Remove --}}
        <div class="flex items-end md:col-span-1">

            <button
                type="button"
                class="remove-medicine w-full rounded-lg border border-red-200 bg-white px-3 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50"
            >
                Xóa
            </button>

        </div>

    </div>

</div>

</template>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const examinationSelect =
        document.getElementById('examination_id');

    const doctorInput =
        document.getElementById('doctor_id');

    const patientName =
        document.getElementById('patient-name');

    const doctorName =
        document.getElementById('doctor-name');

    const medicineList =
        document.getElementById('medicine-list');

    const emptyMedicine =
        document.getElementById('empty-medicine');

    const addMedicineButton =
        document.getElementById('add-medicine');

    const template =
        document.getElementById('medicine-template');

    let medicineIndex = 0;


    /*
    |--------------------------------------------------------------------------
    | Examination
    |--------------------------------------------------------------------------
    */

    function updateExamination() {

        const option =
            examinationSelect.options[
                examinationSelect.selectedIndex
            ];


        if (!option || !option.value) {

            patientName.textContent =
                'Chưa chọn phiếu khám';

            doctorName.textContent =
                'Chưa chọn phiếu khám';

            doctorInput.value = '';

            return;
        }


        patientName.textContent =
            option.dataset.patient ||
            'Chưa xác định';


        doctorInput.value =
            option.dataset.doctor || '';


        const doctorId =
            option.dataset.doctor;


        const doctorMap = @json(
            $examinations->mapWithKeys(
                fn ($examination) => [
                    $examination->doctor_id =>
                        $examination->doctor?->user?->name
                            ?? 'Chưa xác định'
                ]
            )
        );


        doctorName.textContent =
            doctorMap[doctorId] ||
            'Chưa xác định';
    }


    /*
    |--------------------------------------------------------------------------
    | Empty medicine state
    |--------------------------------------------------------------------------
    */

    function updateEmptyState() {

        emptyMedicine.style.display =
            medicineList.children.length
                ? 'none'
                : 'block';
    }


    /*
    |--------------------------------------------------------------------------
    | Add medicine
    |--------------------------------------------------------------------------
    */

    function addMedicineRow() {

        const html =
            template.innerHTML.replaceAll(
                'INDEX',
                medicineIndex
            );


        medicineList.insertAdjacentHTML(
            'beforeend',
            html
        );


        medicineIndex++;

        updateEmptyState();
    }


    /*
    |--------------------------------------------------------------------------
    | Remove medicine
    |--------------------------------------------------------------------------
    */

    addMedicineButton.addEventListener(
        'click',
        addMedicineRow
    );


    medicineList.addEventListener(
        'click',
        function (event) {

            if (
                !event.target.classList.contains(
                    'remove-medicine'
                )
            ) {
                return;
            }


            const row =
                event.target.closest(
                    '.medicine-row'
                );


            if (row) {
                row.remove();
            }


            updateEmptyState();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Medicine stock
    |--------------------------------------------------------------------------
    */

    medicineList.addEventListener(
        'change',
        function (event) {

            if (
                !event.target.classList.contains(
                    'medicine-select'
                )
            ) {
                return;
            }


            const select =
                event.target;

            const option =
                select.options[
                    select.selectedIndex
                ];


            const row =
                select.closest(
                    '.medicine-row'
                );


            if (!row) {
                return;
            }


            const stockElement =
                row.querySelector(
                    '.medicine-stock'
                );


            if (
                !option ||
                !option.value
            ) {

                stockElement.textContent =
                    'Chọn thuốc để xem tồn kho';

                stockElement.className =
                    'medicine-stock mt-1 text-xs text-slate-400';

                return;
            }


            const stock =
                Number(
                    option.dataset.stock || 0
                );


            stockElement.textContent =
                `Tồn kho hiện tại: ${stock}`;


            stockElement.className =
                stock > 0
                    ? 'medicine-stock mt-1 text-xs text-emerald-600'
                    : 'medicine-stock mt-1 text-xs text-red-600';
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial state
    |--------------------------------------------------------------------------
    */

    examinationSelect.addEventListener(
        'change',
        updateExamination
    );


    updateExamination();

    updateEmptyState();

});

</script>

@endsection
