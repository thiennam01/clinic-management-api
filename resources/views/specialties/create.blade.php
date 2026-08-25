@extends('layouts.app')

@section('title', 'Thêm chuyên khoa')

@section('content')

<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">

    <div class="mb-6">
        <a
            href="{{ route('web.specialties.index') }}"
            class="text-sm font-medium text-slate-500 hover:text-blue-600"
        >
            ← Quay lại danh sách
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-800">
            Thêm chuyên khoa
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Tạo hồ sơ chuyên khoa mới
        </p>
    </div>


    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <form
            method="POST"
            action="{{ route('web.specialties.store') }}"
            class="space-y-6"
        >

            @csrf

            {{-- Name --}}
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Tên chuyên khoa
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Ví dụ: Nội khoa"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- Description --}}
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Mô tả
                </label>

                <textarea
                    name="description"
                    rows="5"
                    placeholder="Nhập mô tả chuyên khoa..."
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >{{ old('description') }}</textarea>

                @error('description')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- Status --}}
            <div>

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Trạng thái
                </label>

                <label class="flex items-center gap-3">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', true))
                        class="h-4 w-4 rounded border-slate-300 text-blue-600"
                    >

                    <span class="text-sm text-slate-600">
                        Đang hoạt động
                    </span>

                </label>

            </div>


            {{-- Actions --}}
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">

                <a
                    href="{{ route('web.specialties.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50"
                >
                    Hủy
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Lưu chuyên khoa
                </button>

            </div>

        </form>

    </div>

</div>

@endsection