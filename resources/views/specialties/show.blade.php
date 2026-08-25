@extends('layouts.app')

@section('title', 'Chi tiết chuyên khoa')

@section('content')

<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- Header --}}
    <div class="mb-6 flex items-start justify-between">

        <div>

            <a
                href="{{ route('web.specialties.index') }}"
                class="text-sm font-medium text-slate-500 hover:text-blue-600"
            >
                ← Danh sách chuyên khoa
            </a>

            <div class="mt-3 flex items-center gap-3">

                <h1 class="text-2xl font-bold text-slate-800">
                    {{ $specialty->name }}
                </h1>

                @if($specialty->is_active)

                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                        Đang hoạt động
                    </span>

                @else

                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                        Ngừng hoạt động
                    </span>

                @endif

            </div>

            <p class="mt-1 font-mono text-sm text-blue-600">
                {{ $specialty->code }}
            </p>

        </div>


        <a
            href="{{ route('web.specialties.edit', $specialty->id) }}"
            class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
        >
            Chỉnh sửa
        </a>

    </div>


    {{-- Information --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-700">
                Thông tin chuyên khoa
            </h2>
        </div>


        <div class="divide-y divide-slate-100">

            <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-3">

                <div class="text-sm font-medium text-slate-500">
                    Mã chuyên khoa
                </div>

                <div class="font-mono text-sm font-semibold text-slate-800 sm:col-span-2">
                    {{ $specialty->code }}
                </div>

            </div>


            <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-3">

                <div class="text-sm font-medium text-slate-500">
                    Tên chuyên khoa
                </div>

                <div class="text-sm font-semibold text-slate-800 sm:col-span-2">
                    {{ $specialty->name }}
                </div>

            </div>


            <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-3">

                <div class="text-sm font-medium text-slate-500">
                    Mô tả
                </div>

                <div class="text-sm leading-6 text-slate-700 sm:col-span-2">
                    {{ $specialty->description ?: 'Chưa có mô tả.' }}
                </div>

            </div>


            <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-3">

                <div class="text-sm font-medium text-slate-500">
                    Trạng thái
                </div>

                <div class="sm:col-span-2">

                    @if($specialty->is_active)

                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                            Đang hoạt động
                        </span>

                    @else

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                            Ngừng hoạt động
                        </span>

                    @endif

                </div>

            </div>


            <div class="grid grid-cols-1 gap-2 px-6 py-5 sm:grid-cols-3">

                <div class="text-sm font-medium text-slate-500">
                    Ngày tạo
                </div>

                <div class="text-sm text-slate-700 sm:col-span-2">
                    {{ $specialty->created_at?->format('d/m/Y H:i') }}
                </div>

            </div>

        </div>

    </div>


    {{-- Delete --}}
    <div class="mt-6 flex justify-between">

        <a
            href="{{ route('web.specialties.index') }}"
            class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50"
        >
            Quay lại
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
                class="rounded-lg bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-100"
            >
                Xóa chuyên khoa
            </button>

        </form>

    </div>

</div>

@endsection