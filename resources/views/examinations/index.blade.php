@extends('layouts.app')

@section('title', 'Khám bệnh | Clinic App')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Khám bệnh
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Danh sách kết quả khám bệnh của bệnh nhân.
            </p>
        </div>

        <a
            href="{{ route('appointments.web.index') }}"
            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
        >
            Xem lịch khám
        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- Error --}}
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- Examination list --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        @if($examinations->isEmpty())

            <div class="px-6 py-12 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                    <svg
                        class="h-6 w-6 text-slate-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414A1 1 0 0118 8.414V19a2 2 0 01-2 2z"
                        />
                    </svg>
                </div>

                <h3 class="mt-4 text-sm font-semibold text-slate-900">
                    Chưa có phiếu khám
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Chưa có kết quả khám bệnh nào được ghi nhận.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Bệnh nhân
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Bác sĩ
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Chẩn đoán
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Thời gian khám
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Thao tác
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($examinations as $examination)

                            <tr class="hover:bg-slate-50">

                                {{-- Patient --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="font-semibold text-slate-900">
                                        {{ $examination->patient?->full_name ?? 'Không xác định' }}
                                    </div>

                                    @if($examination->patient?->phone)
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $examination->patient->phone }}
                                        </div>
                                    @endif

                                </td>


                                {{-- Doctor --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $examination->doctor?->user?->name ?? 'Không xác định' }}
                                    </div>

                                    @if($examination->doctor?->specialty?->name)
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $examination->doctor->specialty->name }}
                                        </div>
                                    @endif

                                </td>


                                {{-- Diagnosis --}}
                                <td class="max-w-xs px-6 py-4">

                                    <p class="truncate text-sm text-slate-700">
                                        {{ $examination->diagnosis }}
                                    </p>

                                </td>


                                {{-- Examined at --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="text-sm text-slate-700">
                                        {{ $examination->examined_at?->format('d/m/Y') }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $examination->examined_at?->format('H:i') }}
                                    </div>

                                </td>


                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <a
                                        href="{{ route('examinations.web.show', $examination->id) }}"
                                        class="inline-flex items-center rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100"
                                    >
                                        Xem kết quả
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection