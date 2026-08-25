@extends('layouts.app')

@section('title', 'Thêm bác sĩ')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <a href="{{ route('web.doctors.index') }}"
           class="text-sm font-medium text-blue-600 hover:text-blue-700">
            ← Quay lại danh sách
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-800">
            Thêm bác sĩ
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Tạo hồ sơ chuyên môn cho tài khoản bác sĩ.
        </p>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <ul class="space-y-1 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('web.doctors.store') }}"
          class="space-y-6">

        @csrf

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="mb-5 text-lg font-bold text-slate-800">
                Thông tin bác sĩ
            </h2>

            <div class="grid gap-5 md:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Tài khoản bác sĩ *
                    </label>

                    <select name="user_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">

                        <option value="">
                            -- Chọn tài khoản DOCTOR --
                        </option>

                        @foreach($doctorUsers as $user)
                            <option value="{{ $user->id }}"
                                @selected(old('user_id') == $user->id)>
                                {{ $user->name }} — {{ $user->email }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Chuyên khoa *
                    </label>

                    <select name="specialty_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">

                        <option value="">
                            -- Chọn chuyên khoa --
                        </option>

                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}"
                                @selected(old('specialty_id') == $specialty->id)>
                                {{ $specialty->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Số giấy phép hành nghề *
                    </label>

                    <input type="text"
                           name="license_number"
                           value="{{ old('license_number') }}"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                           placeholder="VD: GP-123456">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Kinh nghiệm
                    </label>

                    <div class="relative">
                        <input type="number"
                               name="experience_years"
                               value="{{ old('experience_years', 0) }}"
                               min="0"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-16 text-sm">

                        <span class="absolute right-4 top-3 text-sm text-slate-400">
                            năm
                        </span>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Phí khám
                    </label>

                    <div class="relative">
                        <input type="number"
                               name="consultation_fee"
                               value="{{ old('consultation_fee', 0) }}"
                               min="0"
                               step="1000"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 text-sm">

                        <span class="absolute right-4 top-3 text-sm text-slate-400">
                            ₫
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-8">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           @checked(old('is_active', true))
                           class="h-4 w-4 rounded border-slate-300 text-blue-600">

                    <label class="text-sm font-semibold text-slate-700">
                        Đang hoạt động
                    </label>
                </div>

            </div>

            <div class="mt-5">
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Giới thiệu
                </label>

                <textarea name="bio"
                          rows="5"
                          class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"
                          placeholder="Thông tin giới thiệu về bác sĩ...">{{ old('bio') }}</textarea>
            </div>

        </div>

        <div class="flex justify-end gap-3">

            <a href="{{ route('web.doctors.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                Hủy
            </a>

            <button type="submit"
                    class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                Lưu bác sĩ
            </button>

        </div>

    </form>

</div>
@endsection