@extends('layouts.app')

@section('title', 'Chỉnh sửa bác sĩ')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <a href="{{ route('web.doctors.show', $doctor->id) }}"
           class="text-sm font-medium text-blue-600">
            ← Quay lại hồ sơ
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-800">
            Chỉnh sửa hồ sơ bác sĩ
        </h1>
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
          action="{{ route('web.doctors.update', $doctor->id) }}"
          class="space-y-6">

        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="grid gap-5 md:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Tài khoản bác sĩ
                    </label>

                    <select name="user_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">

                        @foreach($doctorUsers as $user)
                            <option value="{{ $user->id }}"
                                @selected(old('user_id', $doctor->user_id) == $user->id)>
                                {{ $user->name }} — {{ $user->email }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Chuyên khoa
                    </label>

                    <select name="specialty_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">

                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}"
                                @selected(old('specialty_id', $doctor->specialty_id) == $specialty->id)>
                                {{ $specialty->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Số giấy phép hành nghề
                    </label>

                    <input type="text"
                           name="license_number"
                           value="{{ old('license_number', $doctor->license_number) }}"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Kinh nghiệm
                    </label>

                    <input type="number"
                           name="experience_years"
                           min="0"
                           value="{{ old('experience_years', $doctor->experience_years) }}"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Phí khám
                    </label>

                    <input type="number"
                           name="consultation_fee"
                           min="0"
                           step="1000"
                           value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                </div>

                <div class="flex items-center gap-3 pt-8">

                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           @checked(old('is_active', $doctor->is_active))
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
                          rows="6"
                          class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">{{ old('bio', $doctor->bio) }}</textarea>

            </div>

        </div>

        <div class="flex justify-end gap-3">

            <a href="{{ route('web.doctors.show', $doctor->id) }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-600">
                Hủy
            </a>

            <button type="submit"
                    class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                Lưu thay đổi
            </button>

        </div>

    </form>

</div>
@endsection