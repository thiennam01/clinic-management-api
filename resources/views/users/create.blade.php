@extends('layouts.app')

@section('title', 'Tạo tài khoản | Clinic App')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <a
            href="{{ route('users.web.index') }}"
            class="text-sm font-medium text-slate-500 hover:text-blue-600"
        >
            ← Quay lại
        </a>

        <h1 class="mt-4 text-2xl font-bold text-slate-900">
            Tạo tài khoản
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Tạo tài khoản mới và gán vai trò cho người dùng.
        </p>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="font-semibold text-red-700">
                Không thể tạo tài khoản
            </p>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('users.web.store') }}"
        class="space-y-6"
    >
        @csrf

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="grid gap-5 md:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Họ tên *
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Email *
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Mật khẩu *
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Xác nhận mật khẩu *
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Vai trò *
                    </label>

                    <select
                        name="role_id"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm"
                    >
                        <option value="">-- Chọn vai trò --</option>

                        @foreach($roles as $role)
                            <option
                                value="{{ $role->id }}"
                                @selected(old('role_id') == $role->id)
                            >
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-7">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        checked
                        class="h-4 w-4 rounded border-slate-300"
                    >

                    <label class="text-sm font-medium text-slate-700">
                        Tài khoản đang hoạt động
                    </label>
                </div>

            </div>

        </div>

        <div class="flex justify-end gap-3">
            <a
                href="{{ route('users.web.index') }}"
                class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700"
            >
                Hủy
            </a>

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Tạo tài khoản
            </button>
        </div>

    </form>

</div>

@endsection