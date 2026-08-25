@extends('layouts.app')

@section('title', 'Hồ sơ bác sĩ')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <a href="{{ route('web.doctors.index') }}"
               class="text-sm font-medium text-blue-600">
                ← Danh sách bác sĩ
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Hồ sơ bác sĩ
            </h1>
        </div>

        <a href="{{ route('web.doctors.edit', $doctor->id) }}"
           class="rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white hover:bg-amber-600">
            Chỉnh sửa hồ sơ
        </a>

    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="bg-slate-50 px-6 py-8">

            <div class="flex items-center gap-5">

                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-600">
                    {{ strtoupper(substr($doctor->user?->name ?? 'B', 0, 1)) }}
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-slate-800">
                        {{ $doctor->user?->name ?? 'Chưa có tài khoản' }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $doctor->user?->email ?? '-' }}
                    </p>

                    <div class="mt-3">
                        @if($doctor->is_active)
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                Đang hoạt động
                            </span>
                        @else
                            <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-600">
                                Ngừng hoạt động
                            </span>
                        @endif
                    </div>
                </div>

            </div>

        </div>

        <div class="grid gap-6 p-6 md:grid-cols-2">

            <div>
                <div class="text-xs font-semibold uppercase text-slate-400">
                    Chuyên khoa
                </div>
                <div class="mt-2 font-semibold text-slate-800">
                    {{ $doctor->specialty?->name ?? '-' }}
                </div>
            </div>

            <div>
                <div class="text-xs font-semibold uppercase text-slate-400">
                    Giấy phép hành nghề
                </div>
                <div class="mt-2 font-semibold text-slate-800">
                    {{ $doctor->license_number }}
                </div>
            </div>

            <div>
                <div class="text-xs font-semibold uppercase text-slate-400">
                    Kinh nghiệm
                </div>
                <div class="mt-2 font-semibold text-slate-800">
                    {{ $doctor->experience_years }} năm
                </div>
            </div>

            <div>
                <div class="text-xs font-semibold uppercase text-slate-400">
                    Phí khám
                </div>
                <div class="mt-2 font-semibold text-slate-800">
                    {{ number_format($doctor->consultation_fee, 0, ',', '.') }} ₫
                </div>
            </div>

        </div>

        <div class="border-t border-slate-200 p-6">

            <div class="text-xs font-semibold uppercase text-slate-400">
                Giới thiệu
            </div>

            <p class="mt-3 whitespace-pre-line leading-7 text-slate-600">
                {{ $doctor->bio ?: 'Chưa có thông tin giới thiệu.' }}
            </p>

        </div>

    </div>

</div>
@endsection