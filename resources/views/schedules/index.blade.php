@extends('layouts.app')

@section('title', 'Lịch làm việc | Clinic App')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Lịch làm việc bác sĩ
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Quản lý các ca làm việc và thời gian nhận bệnh của bác sĩ.
            </p>
        </div>

        <a
            href="{{ route('schedules.web.create') }}"
            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
        >
            + Thêm ca làm việc
        </a>

    </div>


    {{-- Messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <form
            method="GET"
            action="{{ route('schedules.web.index') }}"
            class="grid gap-4 md:grid-cols-4"
        >

            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Bác sĩ
                </label>

                <select
                    name="doctor_id"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm"
                >

                    <option value="">
                        Tất cả bác sĩ
                    </option>

                    @foreach($doctors as $doctor)

                        <option
                            value="{{ $doctor->id }}"
                            @selected(request('doctor_id') == $doctor->id)
                        >
                            {{ $doctor->user?->name ?? 'Chưa có tên' }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Ngày
                </label>

                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm"
                >

            </div>


            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Trạng thái
                </label>

                <select
                    name="is_active"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm"
                >

                    <option value="">
                        Tất cả
                    </option>

                    <option
                        value="1"
                        @selected(request('is_active') === '1')
                    >
                        Đang hoạt động
                    </option>

                    <option
                        value="0"
                        @selected(request('is_active') === '0')
                    >
                        Tạm khóa
                    </option>

                </select>

            </div>


            <div class="flex items-end">

                <button
                    type="submit"
                    class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
                >
                    Lọc
                </button>

            </div>

        </form>

    </div>


    {{-- Schedule table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                            Bác sĩ
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                            Chuyên khoa
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                            Ngày
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                            Ca làm việc
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                            Sức chứa
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-500">
                            Trạng thái
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-slate-500">
                            Thao tác
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($schedules as $schedule)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">

                                <p class="font-semibold text-slate-900">
                                    {{ $schedule->doctor?->user?->name ?? 'Chưa phân công' }}
                                </p>

                            </td>


                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $schedule->doctor?->specialty?->name ?? '—' }}
                            </td>


                            <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                {{ $schedule->date?->format('d/m/Y') }}
                            </td>


                            <td class="px-6 py-4">

                                <span class="font-semibold text-slate-900">
                                    {{ substr($schedule->start_time, 0, 5) }}
                                </span>

                                <span class="text-slate-400">
                                    →
                                </span>

                                <span class="font-semibold text-slate-900">
                                    {{ substr($schedule->end_time, 0, 5) }}
                                </span>

                            </td>


                            <td class="px-6 py-4 text-sm">

                                <span class="font-semibold text-slate-900">
                                    {{ $schedule->current_patients }}
                                </span>

                                <span class="text-slate-400">
                                    / {{ $schedule->max_patients }}
                                </span>

                            </td>


                            <td class="px-6 py-4">

                                @if($schedule->is_active)

                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                        Đang hoạt động
                                    </span>

                                @else

                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                        Tạm khóa
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('schedules.web.edit', $schedule) }}"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                    >
                                        Sửa
                                    </a>


                                    @if($schedule->current_patients === 0)

                                        <form
                                            method="POST"
                                            action="{{ route('schedules.web.destroy', $schedule) }}"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa ca làm việc này?')"
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

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-12 text-center"
                            >

                                <p class="font-semibold text-slate-700">
                                    Chưa có ca làm việc
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Hãy thêm ca làm việc đầu tiên cho bác sĩ.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($schedules->hasPages())

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $schedules->withQueryString()->links() }}
            </div>

        @endif

    </div>

</div>

@endsection