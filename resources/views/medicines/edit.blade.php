@extends('layouts.app')

@section('title', 'Sửa thuốc | Clinic App')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div>

        <a
            href="{{ route('medicines.web.index') }}"
            class="text-sm font-medium text-blue-600 hover:text-blue-700"
        >
            ← Quay lại danh mục thuốc
        </a>

        <h1 class="mt-3 text-2xl font-bold text-gray-900">
            Chỉnh sửa thuốc
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Cập nhật thông tin thuốc trong hệ thống.
        </p>

    </div>


    {{-- Form --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <form
            method="POST"
            action="{{ route('medicines.web.update', $medicine->id) }}"
            class="space-y-6"
        >

            @csrf
            @method('PUT')


            {{-- Code --}}
            <div>

                <label
                    for="code"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Mã thuốc <span class="text-red-500">*</span>
                </label>

                <input
                    id="code"
                    name="code"
                    type="text"
                    value="{{ old('code', $medicine->code) }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>


            {{-- Name --}}
            <div>

                <label
                    for="name"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Tên thuốc <span class="text-red-500">*</span>
                </label>

                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $medicine->name) }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>


            {{-- Unit + Price --}}
            <div class="grid gap-6 md:grid-cols-2">

                <div>

                    <label
                        for="unit"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Đơn vị <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="unit"
                        name="unit"
                        type="text"
                        value="{{ old('unit', $medicine->unit) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                    @error('unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                </div>


                <div>

                    <label
                        for="price"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Giá bán <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">

                        <input
                            id="price"
                            name="price"
                            type="number"
                            min="0"
                            step="0.01"
                            value="{{ old('price', $medicine->price) }}"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 pr-10 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >

                        <span class="absolute right-3 top-2.5 text-sm text-gray-400">
                            ₫
                        </span>

                    </div>

                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                </div>

            </div>


            {{-- Stock --}}
            <div>

                <label
                    for="stock"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Số lượng tồn kho <span class="text-red-500">*</span>
                </label>

                <input
                    id="stock"
                    name="stock"
                    type="number"
                    min="0"
                    value="{{ old('stock', $medicine->stock) }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                @error('stock')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>


            {{-- Status --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">

                <label class="flex cursor-pointer items-center gap-3">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $medicine->is_active) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >

                    <span>

                        <span class="block text-sm font-medium text-gray-900">
                            Cho phép kê đơn
                        </span>

                        <span class="block text-xs text-gray-500">
                            Tắt tùy chọn này nếu thuốc không còn được sử dụng.
                        </span>

                    </span>

                </label>

            </div>


            {{-- Actions --}}
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">

                <a
                    href="{{ route('medicines.web.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Hủy
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                >
                    Lưu thay đổi
                </button>

            </div>

        </form>

    </div>

</div>

@endsection