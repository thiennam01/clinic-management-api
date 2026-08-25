@extends('layouts.app')

@section('title', 'Thêm bệnh nhân | Clinic App')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">

        <a
            href="{{ route('web.patients.index') }}"
            class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-700"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M15 19l-7-7 7-7"
                />
            </svg>
        </a>

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Thêm bệnh nhân
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Nhập thông tin để tạo hồ sơ bệnh nhân mới.
            </p>
        </div>

    </div>


    {{-- Validation summary --}}
    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="flex gap-3">

                <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 9v4M12 17h.01M10.29 3.86l-8.17 14a2 2 0 001.73 3h16.3a2 2 0 001.73-3l-8.17-14a2 2 0 00-3.42 0z"
                    />
                </svg>

                <div>

                    <p class="text-sm font-semibold text-red-700">
                        Vui lòng kiểm tra lại thông tin
                    </p>

                    <ul class="mt-1 list-inside list-disc text-sm text-red-600">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- Form --}}
    <form
        method="POST"
        action="{{ route('web.patients.store') }}"
        class="space-y-6"
    >

        @csrf


        {{-- Basic information --}}
        <div class="card overflow-hidden">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="font-semibold text-slate-900">
                    Thông tin cơ bản
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Thông tin định danh của bệnh nhân.
                </p>

            </div>


            <div class="grid gap-5 p-5 md:grid-cols-2">

                {{-- Full name --}}
                <div class="md:col-span-2">

                    <label
                        for="full_name"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Họ và tên
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="full_name"
                        type="text"
                        name="full_name"
                        value="{{ old('full_name') }}"
                        placeholder="Nguyễn Văn A"
                        autofocus
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('full_name') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                    >

                    @error('full_name')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Gender --}}
                <div>

                    <label
                        for="gender"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Giới tính
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="gender"
                        name="gender"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('gender') border-red-300 @enderror"
                    >

                        <option value="">
                            Chọn giới tính
                        </option>

                        <option
                            value="male"
                            @selected(old('gender') === 'male')
                        >
                            Nam
                        </option>

                        <option
                            value="female"
                            @selected(old('gender') === 'female')
                        >
                            Nữ
                        </option>

                        <option
                            value="other"
                            @selected(old('gender') === 'other')
                        >
                            Khác
                        </option>

                    </select>

                    @error('gender')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Date of birth --}}
                <div>

                    <label
                        for="date_of_birth"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Ngày sinh
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="date_of_birth"
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                        max="{{ now()->format('Y-m-d') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('date_of_birth') border-red-300 @enderror"
                    >

                    @error('date_of_birth')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

        </div>


        {{-- Contact information --}}
        <div class="card overflow-hidden">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="font-semibold text-slate-900">
                    Thông tin liên hệ
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Thông tin để liên hệ với bệnh nhân khi cần thiết.
                </p>

            </div>


            <div class="grid gap-5 p-5 md:grid-cols-2">

                {{-- Phone --}}
                <div>

                    <label
                        for="phone"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Số điện thoại
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="phone"
                        type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="0912345678"
                        autocomplete="tel"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('phone') border-red-300 @enderror"
                    >

                    @error('phone')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Email --}}
                <div>

                    <label
                        for="email"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Email
                        <span class="text-xs font-normal text-slate-400">
                            (không bắt buộc)
                        </span>
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="patient@example.com"
                        autocomplete="email"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('email') border-red-300 @enderror"
                    >

                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Address --}}
                <div class="md:col-span-2">

                    <label
                        for="address"
                        class="mb-1.5 block text-sm font-medium text-slate-700"
                    >
                        Địa chỉ
                        <span class="text-xs font-normal text-slate-400">
                            (không bắt buộc)
                        </span>
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố..."
                        class="w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('address') border-red-300 @enderror"
                    >{{ old('address') }}</textarea>

                    @error('address')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

        </div>


        {{-- Information --}}
        <div class="flex gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">

            <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-width="1.8"
                    d="M12 16v-4M12 8h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>

            <p class="text-sm leading-6 text-blue-700">
                Mã bệnh nhân sẽ được hệ thống tự động tạo sau khi lưu hồ sơ.
            </p>

        </div>


        {{-- Actions --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('web.patients.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
            >
                Hủy
            </a>

            <button
                type="submit"
                class="btn-primary inline-flex items-center justify-center gap-2"
            >

                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>

                Lưu bệnh nhân

            </button>

        </div>

    </form>

</div>

@endsection