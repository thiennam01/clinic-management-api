@extends('layouts.app')

@section('title', 'Lịch khám | Clinic App')
@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Lịch khám
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Quản lý lịch hẹn và trạng thái khám của bệnh nhân.
            </p>
        </div>

        <a
            href="{{ route('appointments.web.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-width="1.8"
                    d="M12 5v14m-7-7h14"
                />
            </svg>

            Đặt lịch khám
        </a>

    </div>


    {{-- Flash --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- Search / Filter --}}
    <form
        method="GET"
        action="{{ route('appointments.web.index') }}"
        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
    >

        <div class="grid gap-3 md:grid-cols-[1fr_220px_auto]">

            <div class="relative">

                <svg
                    class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-width="1.8"
                        d="m21 21-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"
                    />
                </svg>

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Tìm bệnh nhân, email..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

            </div>


            <select
                name="status"
                class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
            >
                <option value="">Tất cả trạng thái</option>

                @foreach($statuses as $key => $label)
                    <option
                        value="{{ $key }}"
                        @selected(request('status') === $key)
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>


            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
            >
                Lọc
            </button>

        </div>

    </form>


    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">

                        <th class="px-5 py-4">
                            Bệnh nhân
                        </th>

                        <th class="px-5 py-4">
                            Bác sĩ
                        </th>

                        <th class="px-5 py-4">
                            Chuyên khoa
                        </th>

                        <th class="px-5 py-4">
                            Thời gian
                        </th>

                        <th class="px-5 py-4">
                            Trạng thái
                        </th>

                        <th class="px-5 py-4 text-right">
                            Thao tác
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($appointments as $appointment)

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


                        <tr class="transition hover:bg-slate-50">

                            {{-- Patient --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-700">
                                        {{ strtoupper(substr($appointment->patient?->full_name ?? '?', 0, 1)) }}
                                    </div>

                                    <div>

                                        <a
                                            href="{{ route('appointments.web.show', $appointment) }}"
                                            class="font-semibold text-slate-900 hover:text-blue-600"
                                        >
                                            {{ $appointment->patient?->full_name ?? 'Không xác định' }}
                                        </a>

                                        <p class="text-xs text-slate-500">
                                            {{ $appointment->patient?->phone ?? '—' }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- Doctor --}}
                            <td class="px-5 py-4 text-slate-700">

                                {{ $appointment->schedule?->doctor?->user?->name ?? 'Chưa phân công' }}

                            </td>


                            {{-- Specialty --}}
                            <td class="px-5 py-4 text-slate-600">

                                {{ $appointment->schedule?->doctor?->specialty?->name ?? '—' }}

                            </td>


                            {{-- Date --}}
                            <td class="px-5 py-4">

                                <div class="font-medium text-slate-900">
                                    {{ optional($appointment->appointment_date)->format('d/m/Y') }}
                                </div>

                                <div class="text-xs text-slate-500">
                                    {{ optional($appointment->appointment_date)->format('H:i') }}
                                </div>

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                <span
                                    class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $config['class'] }}"
                                >
                                    {{ $config['label'] }}
                                </span>

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex justify-end gap-1">

                                    <a
                                        href="{{ route('appointments.web.show', $appointment) }}"
                                        class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                                        title="Xem"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-width="1.8"
                                                d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"
                                            />
                                            <circle cx="12" cy="12" r="2.5" stroke-width="1.8"/>
                                        </svg>
                                    </a>


                                    @if(!in_array($status, ['completed', 'cancelled']))

                                        <a
                                            href="{{ route('appointments.web.edit', $appointment) }}"
                                            class="rounded-lg p-2 text-slate-400 hover:bg-amber-50 hover:text-amber-600"
                                            title="Chỉnh sửa"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-width="1.8"
                                                    d="M4 20h4l10.5-10.5a2.12 2.12 0 00-3-3L5 17v3zm9-12 3 3"
                                                />
                                            </svg>
                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-5 py-16 text-center">

                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">

                                    <svg class="h-7 w-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-width="1.8"
                                            d="M8 3v3m8-3v3M4 9h16M6 5h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"
                                        />
                                    </svg>

                                </div>

                                <p class="mt-3 font-semibold text-slate-700">
                                    Chưa có lịch khám
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Hãy tạo lịch khám đầu tiên.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($appointments->hasPages())

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $appointments->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
