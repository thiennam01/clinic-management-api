@extends('layouts.app')

@section('title', 'Chi tiết đơn thuốc | Clinic App')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

{{-- Header --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-3">

        <a
            href="{{ route('prescriptions.web.index') }}"
            class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
            title="Quay lại"
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
                Chi tiết đơn thuốc #{{ $prescription->id }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Thông tin chi tiết đơn thuốc và các thuốc đã kê.
            </p>
        </div>

    </div>

    <a
        href="{{ route('prescriptions.web.index') }}"
        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
    >
        ← Danh sách đơn thuốc
    </a>

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

        <ul class="list-disc space-y-1 pl-5">

            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

        </ul>

    </div>

@endif


{{-- Patient / Doctor information --}}
<div class="grid gap-6 md:grid-cols-2">

    {{-- Patient --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h2 class="font-semibold text-slate-900">
                Thông tin bệnh nhân
            </h2>

        </div>

        <div class="space-y-4 p-6">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Họ và tên
                </p>

                <p class="mt-1 font-semibold text-slate-900">
                    {{ $prescription->examination?->patient?->full_name ?? 'Chưa xác định' }}
                </p>
            </div>

            @if($prescription->examination?->patient?->phone)

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Số điện thoại
                    </p>

                    <p class="mt-1 text-sm text-slate-700">
                        {{ $prescription->examination->patient->phone }}
                    </p>
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

        <div class="space-y-4 p-6">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Bác sĩ
                </p>

                <p class="mt-1 font-semibold text-slate-900">
                    {{ $prescription->doctor?->user?->name ?? 'Chưa xác định' }}
                </p>
            </div>

            @if($prescription->doctor?->specialty?->name)

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Chuyên khoa
                    </p>

                    <p class="mt-1 text-sm text-slate-700">
                        {{ $prescription->doctor->specialty->name }}
                    </p>
                </div>

            @endif

        </div>

    </div>

</div>


{{-- Examination information --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-4">

        <h2 class="font-semibold text-slate-900">
            Thông tin lượt khám
        </h2>

    </div>

    <div class="grid gap-6 p-6 md:grid-cols-3">

        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                Mã lượt khám
            </p>

            <p class="mt-1 font-semibold text-slate-900">
                #{{ $prescription->examination?->id ?? '—' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                Thời gian khám
            </p>

            <p class="mt-1 text-sm text-slate-700">
                {{ optional($prescription->examination?->examined_at)->format('d/m/Y H:i') ?? '—' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                Ngày tạo đơn
            </p>

            <p class="mt-1 text-sm text-slate-700">
                {{ optional($prescription->created_at)->format('d/m/Y H:i') }}
            </p>
        </div>

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
                Các thuốc được kê trong đơn.
            </p>

        </div>

        <span class="inline-flex w-fit rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-200">
            {{ $prescription->items->count() }} loại thuốc
        </span>

    </div>


    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50">

                <tr class="border-b border-slate-200">

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Thuốc
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Đơn vị
                    </th>

                    <th class="px-6 py-4 text-center font-semibold text-slate-600">
                        Số lượng
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Liều dùng
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Cách dùng
                    </th>

                    <th class="px-6 py-4 text-right font-semibold text-slate-600">
                        Thao tác
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($prescription->items as $item)

                    <tr class="hover:bg-slate-50">

                        {{-- Medicine --}}
                        <td class="px-6 py-4">

                            <div class="font-semibold text-slate-900">
                                {{ $item->medicine?->name ?? 'Thuốc không tồn tại' }}
                            </div>

                            @if($item->medicine?->code)

                                <div class="mt-1 text-xs text-slate-500">
                                    Mã: {{ $item->medicine->code }}
                                </div>

                            @endif

                        </td>


                        {{-- Unit --}}
                        <td class="px-6 py-4 text-slate-600">
                            {{ $item->medicine?->unit ?? '—' }}
                        </td>


                        {{-- Quantity --}}
                        <td class="px-6 py-4 text-center">

                            <span class="inline-flex min-w-10 justify-center rounded-lg bg-slate-100 px-2.5 py-1 font-semibold text-slate-700">
                                {{ $item->quantity }}
                            </span>

                        </td>


                        {{-- Dosage --}}
                        <td class="px-6 py-4 text-slate-700">
                            {{ $item->dosage ?: '—' }}
                        </td>


                        {{-- Usage --}}
                        <td class="px-6 py-4 text-slate-700">
                            {{ $item->usage_instruction ?: '—' }}
                        </td>


                        {{-- Actions --}}
                        <td class="px-6 py-4 text-right">

                            <div class="flex justify-end gap-2">

                                {{-- Update --}}
                                <details class="relative">

                                    <summary class="cursor-pointer list-none rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                                        Sửa
                                    </summary>

                                    <div class="absolute right-0 z-20 mt-2 w-80 rounded-xl border border-slate-200 bg-white p-4 text-left shadow-xl">

                                        <form
                                            method="POST"
                                            action="{{ route('prescriptions.web.items.update', $item->id) }}"
                                            class="space-y-3"
                                        >

                                            @csrf
                                            @method('PUT')

                                            <div>

                                                <label class="mb-1 block text-xs font-medium text-slate-600">
                                                    Số lượng
                                                </label>

                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    min="1"
                                                    value="{{ $item->quantity }}"
                                                    required
                                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                                >

                                            </div>

                                            <div>

                                                <label class="mb-1 block text-xs font-medium text-slate-600">
                                                    Liều dùng
                                                </label>

                                                <input
                                                    type="text"
                                                    name="dosage"
                                                    value="{{ $item->dosage }}"
                                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                                >

                                            </div>

                                            <div>

                                                <label class="mb-1 block text-xs font-medium text-slate-600">
                                                    Cách dùng
                                                </label>

                                                <textarea
                                                    name="usage_instruction"
                                                    rows="2"
                                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                                >{{ $item->usage_instruction }}</textarea>

                                            </div>

                                            <button
                                                type="submit"
                                                class="w-full rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700"
                                            >
                                                Lưu thay đổi
                                            </button>

                                        </form>

                                    </div>

                                </details>


                                {{-- Delete --}}
                                <form
                                    method="POST"
                                    action="{{ route('prescriptions.web.items.destroy', $item->id) }}"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa thuốc này khỏi đơn?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50"
                                    >
                                        Xóa
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="px-6 py-10 text-center"
                        >

                            <p class="text-sm font-medium text-slate-500">
                                Đơn thuốc chưa có thuốc.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- Add medicine --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-4">

        <h2 class="font-semibold text-slate-900">
            Thêm thuốc vào đơn
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Thêm thuốc mới và hướng dẫn sử dụng cho bệnh nhân.
        </p>

    </div>


    <form
        method="POST"
        action="{{ route('prescriptions.web.items.store', $prescription->id) }}"
        class="grid gap-5 p-6 md:grid-cols-2"
    >

        @csrf

        {{-- Medicine --}}
        <div class="md:col-span-2">

            <label class="mb-2 block text-sm font-medium text-slate-700">
                Thuốc
            </label>

            <select
                name="medicine_id"
                required
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >

                <option value="">
                    Chọn thuốc
                </option>

                @php
                    $availableMedicines = \App\Models\Medicine::query()
                        ->where('is_active', true)
                        ->where('stock', '>', 0)
                        ->orderBy('name')
                        ->get();
                @endphp

                @foreach($availableMedicines as $medicine)

                    <option value="{{ $medicine->id }}">
                        {{ $medicine->name }}
                        — Tồn kho: {{ $medicine->stock }} {{ $medicine->unit }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- Quantity --}}
        <div>

            <label class="mb-2 block text-sm font-medium text-slate-700">
                Số lượng
            </label>

            <input
                type="number"
                name="quantity"
                min="1"
                value="1"
                required
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >

        </div>


        {{-- Dosage --}}
        <div>

            <label class="mb-2 block text-sm font-medium text-slate-700">
                Liều dùng
            </label>

            <input
                type="text"
                name="dosage"
                placeholder="Ví dụ: 1 viên/lần"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >

        </div>


        {{-- Usage --}}
        <div class="md:col-span-2">

            <label class="mb-2 block text-sm font-medium text-slate-700">
                Cách dùng
            </label>

            <textarea
                name="usage_instruction"
                rows="3"
                placeholder="Ví dụ: Uống sau ăn, ngày 2 lần."
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            ></textarea>

        </div>


        <div class="flex justify-end md:col-span-2">

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
            >
                + Thêm thuốc
            </button>

        </div>

    </form>

</div>


{{-- Notes --}}
@if($prescription->notes)

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h2 class="font-semibold text-slate-900">
                Ghi chú đơn thuốc
            </h2>

        </div>

        <div class="p-6">

            <p class="whitespace-pre-line text-sm leading-6 text-slate-700">
                {{ $prescription->notes }}
            </p>

        </div>

    </div>

@endif

</div>

@endsection
