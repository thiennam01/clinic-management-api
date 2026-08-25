@extends('layouts.app')

@section('title', 'Quản lý bác sĩ')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Quản lý bác sĩ
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Danh sách bác sĩ và hồ sơ chuyên môn
            </p>
        </div>

        <a href="{{ route('web.doctors.create') }}"
           class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
            + Thêm bác sĩ
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-slate-600">
                            Bác sĩ
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-slate-600">
                            Chuyên khoa
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-slate-600">
                            GPLH
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-slate-600">
                            Kinh nghiệm
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-slate-600">
                            Phí khám
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

                @forelse($doctors as $doctor)

                    <tr class="transition hover:bg-slate-50">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">
                                    {{ strtoupper(substr($doctor->user?->name ?? 'B', 0, 1)) }}
                                </div>

                                <div>
                                    <div class="font-semibold text-slate-800">
                                        {{ $doctor->user?->name ?? 'Chưa có tài khoản' }}
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        {{ $doctor->user?->email ?? '-' }}
                                    </div>
                                </div>

                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ $doctor->specialty?->name ?? '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 font-medium text-slate-700">
                            {{ $doctor->license_number }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $doctor->experience_years }} năm
                        </td>

                        <td class="px-6 py-4 font-medium text-slate-700">
                            {{ number_format($doctor->consultation_fee, 0, ',', '.') }} ₫
                        </td>

                        <td class="px-6 py-4">
                            @if($doctor->is_active)
                                <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                    Đang hoạt động
                                </span>
                            @else
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                    Ngừng hoạt động
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">

                                <a href="{{ route('web.doctors.show', $doctor->id) }}"
                                   class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200">
                                    Xem
                                </a>

                                <a href="{{ route('web.doctors.edit', $doctor->id) }}"
                                   class="rounded-lg bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-600 hover:bg-amber-100">
                                    Sửa
                                </a>

                                <form method="POST"
                                      action="{{ route('web.doctors.destroy', $doctor->id) }}"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa hồ sơ bác sĩ này?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100">
                                        Xóa
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                            Chưa có bác sĩ nào.
                        </td>
                    </tr>

                @endforelse

                </tbody>
            </table>
        </div>

        @if($doctors->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $doctors->links() }}
            </div>
        @endif

    </div>

</div>
@endsection