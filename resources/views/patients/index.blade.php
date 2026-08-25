@extends('layouts.app')

@section('title', 'Bệnh nhân | Clinic App')

@section('content')

<div class="space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                        />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        Bệnh nhân
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Quản lý hồ sơ và thông tin bệnh nhân
                    </p>
                </div>
            </div>
        </div>

        {{-- Add patient --}}
        <a
            href="{{ route('web.patients.create') }}"
            class="btn-primary inline-flex items-center justify-center gap-2"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 5v14M5 12h14"
                />
            </svg>

            Thêm bệnh nhân
        </a>

    </div>


    {{-- Flash success --}}
    @if(session('success'))

        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">

            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
            </svg>

            <span>{{ session('success') }}</span>

        </div>

    @endif


    {{-- Search / filters --}}
    <div class="card p-5">

        <form
            method="GET"
            action="{{ route('web.patients.index') }}"
            class="grid gap-4 lg:grid-cols-[1fr_180px_auto]"
        >

            {{-- Search --}}
            <div>
                <label
                    for="q"
                    class="mb-1.5 block text-sm font-medium text-slate-700"
                >
                    Tìm kiếm
                </label>

                <div class="relative">

                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-width="1.8"
                                d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"
                            />
                        </svg>

                    </div>

                    <input
                        id="q"
                        type="text"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="Tên, mã bệnh nhân, số điện thoại..."
                        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>
            </div>


            {{-- Gender --}}
            <div>

                <label
                    for="gender"
                    class="mb-1.5 block text-sm font-medium text-slate-700"
                >
                    Giới tính
                </label>

                <select
                    id="gender"
                    name="gender"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">Tất cả</option>

                    <option
                        value="male"
                        @selected(($filters['gender'] ?? '') === 'male')
                    >
                        Nam
                    </option>

                    <option
                        value="female"
                        @selected(($filters['gender'] ?? '') === 'female')
                    >
                        Nữ
                    </option>

                    <option
                        value="other"
                        @selected(($filters['gender'] ?? '') === 'other')
                    >
                        Khác
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="btn-primary w-full lg:w-auto"
                >
                    Tìm kiếm
                </button>

                @if(!empty($filters['q']) || !empty($filters['gender']))

                    <a
                        href="{{ route('web.patients.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                    >
                        Xóa lọc
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- Patient table --}}
    <div class="card overflow-hidden">

        {{-- Table header --}}
        <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="font-semibold text-slate-900">
                    Danh sách bệnh nhân
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    @if($patients->total() > 0)
                        Hiển thị
                        {{ $patients->firstItem() }}
                        –
                        {{ $patients->lastItem() }}
                        trong tổng số
                        {{ $patients->total() }}
                        bệnh nhân
                    @else
                        Không có bệnh nhân
                    @endif
                </p>
            </div>

            <div class="text-xs text-slate-400">
                Cập nhật theo dữ liệu hệ thống
            </div>

        </div>


        {{-- Empty state --}}
        @if($patients->isEmpty())

            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">

                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.6"
                            d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                        />
                    </svg>

                </div>

                <h3 class="mt-4 font-semibold text-slate-800">
                    Không tìm thấy bệnh nhân
                </h3>

                <p class="mt-1 max-w-sm text-sm text-slate-500">
                    Thử thay đổi từ khóa tìm kiếm hoặc bộ lọc.
                </p>

                <a
                    href="{{ route('web.patients.create') }}"
                    class="btn-primary mt-5"
                >
                    + Thêm bệnh nhân
                </a>

            </div>

        @else

            {{-- Desktop table --}}
            <div class="hidden overflow-x-auto md:block">

                <table class="w-full">

                    <thead>

                        <tr class="border-b border-slate-200 bg-slate-50">

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Bệnh nhân
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Mã BN
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Giới tính
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Ngày sinh
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Liên hệ
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($patients as $patient)

                            <tr class="transition hover:bg-slate-50">

                                {{-- Patient --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-600">
                                            {{ mb_strtoupper(mb_substr($patient->full_name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">

                                            <a
                                                href="{{ route('web.patients.show', $patient->id) }}"
                                                class="block truncate font-semibold text-slate-900 hover:text-blue-600"
                                            >
                                                {{ $patient->full_name }}
                                            </a>

                                            <div class="mt-0.5 text-xs text-slate-400">
                                                ID #{{ $patient->id }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Code --}}
                                <td class="px-5 py-4">

                                    <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        {{ $patient->code }}
                                    </span>

                                </td>


                                {{-- Gender --}}
                                <td class="px-5 py-4 text-sm">

                                    @if($patient->gender === 'male')

                                        <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                            Nam
                                        </span>

                                    @elseif($patient->gender === 'female')

                                        <span class="inline-flex rounded-full bg-pink-50 px-2.5 py-1 text-xs font-semibold text-pink-700">
                                            Nữ
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                            Khác
                                        </span>

                                    @endif

                                </td>


                                {{-- Date --}}
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">

                                    {{ $patient->date_of_birth?->format('d/m/Y') ?? '—' }}

                                </td>


                                {{-- Contact --}}
                                <td class="px-5 py-4">

                                    <div class="text-sm font-medium text-slate-700">
                                        {{ $patient->phone }}
                                    </div>

                                    @if($patient->email)

                                        <div class="mt-0.5 max-w-[180px] truncate text-xs text-slate-400">
                                            {{ $patient->email }}
                                        </div>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-5 py-4">

                                    <div class="flex justify-end gap-1">

                                        <a
                                            href="{{ route('web.patients.show', $patient->id) }}"
                                            title="Xem hồ sơ"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-blue-50 hover:text-blue-600"
                                        >

                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"
                                                />

                                                <circle
                                                    cx="12"
                                                    cy="12"
                                                    r="2.5"
                                                    stroke-width="1.8"
                                                />
                                            </svg>

                                        </a>


                                        <a
                                            href="{{ route('web.patients.edit', $patient->id) }}"
                                            title="Chỉnh sửa"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600"
                                        >

                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M12 20h9M16.5 3.5a2.12 2.12 0 013 3L8 18l-4 1 1-4 12.5-11.5z"
                                                />
                                            </svg>

                                        </a>


                                        <form
                                            method="POST"
                                            action="{{ route('web.patients.destroy', $patient->id) }}"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa bệnh nhân này?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                title="Xóa"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600"
                                            >

                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5"
                                                    />
                                                </svg>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Mobile cards --}}
            <div class="divide-y divide-slate-100 md:hidden">

                @foreach($patients as $patient)

                    <div class="p-5">

                        <div class="flex items-start justify-between gap-4">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-600">
                                    {{ mb_strtoupper(mb_substr($patient->full_name, 0, 1)) }}
                                </div>

                                <div class="min-w-0">

                                    <a
                                        href="{{ route('web.patients.show', $patient->id) }}"
                                        class="block truncate font-semibold text-slate-900"
                                    >
                                        {{ $patient->full_name }}
                                    </a>

                                    <div class="mt-1 text-xs text-slate-400">
                                        {{ $patient->code }}
                                    </div>

                                </div>

                            </div>

                            @if($patient->gender === 'male')

                                <span class="shrink-0 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                    Nam
                                </span>

                            @elseif($patient->gender === 'female')

                                <span class="shrink-0 rounded-full bg-pink-50 px-2.5 py-1 text-xs font-semibold text-pink-700">
                                    Nữ
                                </span>

                            @else

                                <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                    Khác
                                </span>

                            @endif

                        </div>


                        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">

                            <div>
                                <p class="text-xs text-slate-400">
                                    Ngày sinh
                                </p>

                                <p class="mt-1 font-medium text-slate-700">
                                    {{ $patient->date_of_birth?->format('d/m/Y') ?? '—' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">
                                    Điện thoại
                                </p>

                                <p class="mt-1 font-medium text-slate-700">
                                    {{ $patient->phone }}
                                </p>
                            </div>

                        </div>


                        <div class="mt-4 flex items-center justify-end gap-2">

                            <a
                                href="{{ route('web.patients.show', $patient->id) }}"
                                class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50"
                            >
                                Xem hồ sơ
                            </a>

                            <a
                                href="{{ route('web.patients.edit', $patient->id) }}"
                                class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-100"
                            >
                                Chỉnh sửa
                            </a>

                            <form
                                action="{{ route('web.patients.destroy', $patient->id) }}"
                                method="POST"
                                class="inline"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa bệnh nhân này?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50"
                                    title="Xóa bệnh nhân"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0v12a1 1 0 01-1 1H8a1 1 0 01-1-1V7m3 4v6m4-6v6"
                                        />
                                    </svg>
                                </button>
                            </form>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- Pagination --}}
            @if($patients->hasPages())

                <div class="border-t border-slate-200 px-5 py-4">

                    {{ $patients->onEachSide(1)->links() }}

                </div>

            @endif

        @endif

    </div>

</div>

@endsection