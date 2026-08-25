@extends('layouts.app')

@section('title', 'Đơn thuốc | Clinic App')

@section('content')

<div class="space-y-6">

{{-- Header --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Đơn thuốc
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Quản lý đơn thuốc và thuốc đã kê cho bệnh nhân.
        </p>
    </div>

    <a
        href="{{ route('prescriptions.web.create') }}"
        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
    >
        + Tạo đơn thuốc
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


{{-- Table --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50">

                <tr class="border-b border-slate-200">

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Mã đơn
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Bệnh nhân
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Bác sĩ
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Số loại thuốc
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Ngày tạo
                    </th>

                    <th class="px-6 py-4 text-right font-semibold text-slate-600">
                        Thao tác
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($prescriptions as $prescription)

                    <tr class="transition hover:bg-slate-50">

                        {{-- ID --}}
                        <td class="whitespace-nowrap px-6 py-4">

                            <span class="font-semibold text-slate-900">
                                #{{ $prescription->id }}
                            </span>

                        </td>


                        {{-- Patient --}}
                        <td class="px-6 py-4">

                            <div class="font-medium text-slate-900">
                                {{ $prescription->examination?->patient?->full_name ?? 'Chưa xác định' }}
                            </div>

                            @if($prescription->examination?->patient?->phone)

                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $prescription->examination->patient->phone }}
                                </div>

                            @endif

                        </td>


                        {{-- Doctor --}}
                        <td class="px-6 py-4">

                            <div class="font-medium text-slate-900">
                                {{ $prescription->doctor?->user?->name ?? 'Chưa xác định' }}
                            </div>

                            @if($prescription->doctor?->specialty?->name)

                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $prescription->doctor->specialty->name }}
                                </div>

                            @endif

                        </td>


                        {{-- Medicines --}}
                        <td class="px-6 py-4">

                            @php
                                $medicineCount = $prescription->items->count();
                            @endphp

                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-200">

                                {{ $medicineCount }}

                                {{ $medicineCount === 1 ? 'loại thuốc' : 'loại thuốc' }}

                            </span>

                        </td>


                        {{-- Created --}}
                        <td class="whitespace-nowrap px-6 py-4 text-slate-500">

                            {{ optional($prescription->created_at)->format('d/m/Y H:i') }}

                        </td>


                        {{-- Action --}}
                        <td class="whitespace-nowrap px-6 py-4 text-right">

                            <a
                                href="{{ route('prescriptions.web.show', $prescription->id) }}"
                                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100"
                            >
                                Xem chi tiết
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="px-6 py-12 text-center"
                        >

                            <div class="text-sm font-medium text-slate-500">
                                Chưa có đơn thuốc nào.
                            </div>

                            <div class="mt-1 text-xs text-slate-400">
                                Tạo đơn thuốc từ một ca khám đã hoàn tất.
                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

@endsection
