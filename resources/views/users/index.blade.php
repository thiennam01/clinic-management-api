@extends('layouts.app')

@section('title', 'Người dùng | Clinic App')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Người dùng</h1>
            <p class="mt-1 text-sm text-slate-500">
                Quản lý tài khoản và phân quyền người dùng.
            </p>
        </div>

        <a
            href="{{ route('users.web.create') }}"
            class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
        >
            + Tạo tài khoản
        </a>
    </div>

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

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" class="grid gap-3 md:grid-cols-4">

            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Tìm tên hoặc email..."
                class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:bg-white"
            >

            <select
                name="role_id"
                class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm"
            >
                <option value="">Tất cả vai trò</option>

                @foreach($roles as $role)
                    <option
                        value="{{ $role->id }}"
                        @selected(request('role_id') == $role->id)
                    >
                        {{ $role->display_name }}
                    </option>
                @endforeach
            </select>

            <select
                name="is_active"
                class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm"
            >
                <option value="">Tất cả trạng thái</option>
                <option value="1" @selected(request('is_active') === '1')>
                    Đang hoạt động
                </option>
                <option value="0" @selected(request('is_active') === '0')>
                    Đã khóa
                </option>
            </select>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
            >
                Lọc
            </button>

        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-slate-600">
                            Người dùng
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-slate-600">
                            Email
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-slate-600">
                            Vai trò
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-slate-600">
                            Trạng thái
                        </th>

                        <th class="px-6 py-4 text-right font-semibold text-slate-600">
                            Thao tác
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($users as $user)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-700">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $user->name }}
                                        </p>

                                        <p class="text-xs text-slate-400">
                                            #{{ $user->id }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $user->email }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                    {{ $user->role?->display_name ?? 'Chưa phân quyền' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">

                                @if($user->is_active ?? true)
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Đang hoạt động
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                        Đã khóa
                                    </span>
                                @endif

                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('users.web.edit', $user) }}"
                                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50"
                                    >
                                        Sửa
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('users.web.destroy', $user) }}"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50"
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
                                colspan="5"
                                class="px-6 py-12 text-center text-sm text-slate-500"
                            >
                                Chưa có người dùng nào.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        @if($users->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $users->links() }}
            </div>
        @endif

    </div>

</div>

@endsection