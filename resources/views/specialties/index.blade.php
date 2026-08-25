@extends('layouts.app')

@section('title', 'Chuyên khoa')

@section('content')

<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Chuyên khoa
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Quản lý danh sách chuyên khoa của phòng khám
            </p>
        </div>

        <a
            href="{{ route('web.specialties.create') }}"
            class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
        >
            + Thêm chuyên khoa
        </a>

    </div>


    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-700">
                Danh sách chuyên khoa
            </h2>
        </div>

        @if($specialties->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Mã
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Tên chuyên khoa
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Mô tả
                            </th>

                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-500">
                                Trạng thái
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-500">
                                Thao tác
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($specialties as $specialty)

                            <tr class="transition hover:bg-slate-50">

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-mono text-sm font-semibold text-blue-600">
                                        {{ $specialty->code }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-800">
                                        {{ $specialty->name }}
                                    </div>
                                </td>

                                <td class="max-w-md px-5 py-4">
                                    <p class="truncate text-sm text-slate-500">
                                        {{ $specialty->description ?: 'Chưa có mô tả' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-center">

                                    @if($specialty->is_active)

                                        <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                            Đang hoạt động
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                            Ngừng hoạt động
                                        </span>

                                    @endif

                                </td>

                                <td class="px-5 py-4">

                                    <div class="flex justify-end gap-2">

                                        <a
                                            href="{{ route('web.specialties.show', $specialty->id) }}"
                                            class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200"
                                        >
                                            Xem
                                        </a>

                                        <a
                                            href="{{ route('web.specialties.edit', $specialty->id) }}"
                                            class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-100"
                                        >
                                            Sửa
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('web.specialties.destroy', $specialty->id) }}"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa chuyên khoa này?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100"
                                            >
                                                Xóa
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $specialties->links() }}
            </div>

        @else

            <div class="px-6 py-16 text-center">

                <div class="mb-3 text-4xl">
                    🏥
                </div>

                <h3 class="font-semibold text-slate-700">
                    Chưa có chuyên khoa
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Hãy thêm chuyên khoa đầu tiên.
                </p>

                <a
                    href="{{ route('web.specialties.create') }}"
                    class="mt-5 inline-flex rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    + Thêm chuyên khoa
                </a>

            </div>

        @endif

    </div>

</div>

@endsection