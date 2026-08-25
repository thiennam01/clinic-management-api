@extends('layouts.app')

@section('title', 'Danh mục thuốc | Clinic App')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Danh mục thuốc
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Quản lý thuốc, đơn vị, giá bán và tồn kho.
            </p>
        </div>

        <a
            href="{{ route('medicines.web.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 4v16m8-8H4"
                />
            </svg>

            Thêm thuốc
        </a>

    </div>


    {{-- Flash message --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- Validation errors --}}
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">
            <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Mã thuốc
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Tên thuốc
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Đơn vị
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Giá
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Tồn kho
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Trạng thái
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Thao tác
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100 bg-white">

                    @forelse($medicines as $medicine)

                        <tr class="transition hover:bg-gray-50">

                            {{-- Code --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                <span class="font-medium text-gray-900">
                                    {{ $medicine->code }}
                                </span>

                            </td>


                            {{-- Name --}}
                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $medicine->name }}
                                </div>

                            </td>


                            {{-- Unit --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                {{ $medicine->unit }}
                            </td>


                            {{-- Price --}}
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-gray-900">
                                {{ number_format($medicine->price, 0, ',', '.') }} ₫
                            </td>


                            {{-- Stock --}}
                            <td class="whitespace-nowrap px-6 py-4 text-center">

                                @if($medicine->stock <= 0)

                                    <span class="font-semibold text-red-600">
                                        Hết hàng
                                    </span>

                                @elseif($medicine->stock <= 10)

                                    <span class="font-semibold text-orange-600">
                                        {{ $medicine->stock }}
                                    </span>

                                @else

                                    <span class="font-semibold text-gray-900">
                                        {{ $medicine->stock }}
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td class="whitespace-nowrap px-6 py-4 text-center">

                                @if($medicine->is_active)

                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        Đang hoạt động
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                        Ngừng sử dụng
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('medicines.web.edit', $medicine->id) }}"
                                        class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                                    >
                                        Sửa
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('medicines.web.destroy', $medicine->id) }}"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa thuốc này không?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg border border-red-200 px-3 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50"
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
                                colspan="7"
                                class="px-6 py-12 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-12 w-12 text-gray-300"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A3.375 3.375 0 0111.25 4.875v-1.5A3.375 3.375 0 007.875 0H6.75A2.25 2.25 0 004.5 2.25v19.5A2.25 2.25 0 006.75 24h10.5a2.25 2.25 0 002.25-2.25v-7.5z"
                                        />
                                    </svg>

                                    <p class="mt-3 text-sm font-medium text-gray-900">
                                        Chưa có thuốc
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Hãy thêm thuốc đầu tiên vào hệ thống.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($medicines->hasPages())

            <div class="border-t border-gray-200 px-6 py-4">
                {{ $medicines->links() }}
            </div>

        @endif

    </div>

</div>

@endsection