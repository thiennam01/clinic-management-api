@extends('layouts.app')

@section('title', 'Tạo hóa đơn | Clinic App')

@section('content')

<div class="space-y-6">

    <div class="flex items-center gap-3">

        <a
            href="{{ route('invoices.web.index') }}"
            class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
            title="Quay lại"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M15 18l-6-6 6-6"
                />
            </svg>
        </a>

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Tạo hóa đơn
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Chọn lần khám chưa có hóa đơn để tạo hóa đơn.
            </p>
        </div>

    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-900">
                Lần khám chưa lập hóa đơn
            </h2>
        </div>

        @if($examinations->isEmpty())

            <div class="px-6 py-14 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>

                <p class="mt-3 font-medium text-slate-700">
                    Không có lần khám nào cần lập hóa đơn
                </p>

                <p class="mt-1 text-sm text-slate-400">
                    Tất cả các lần khám hiện tại đã có hóa đơn.
                </p>

            </div>

        @else

            <div class="divide-y divide-slate-100">

                @foreach($examinations as $examination)

                    <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                        <div class="min-w-0">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                                    {{ strtoupper(substr($examination->patient?->full_name ?? '?', 0, 1)) }}
                                </div>

                                <div class="min-w-0">

                                    <p class="font-semibold text-slate-900">
                                        {{ $examination->patient?->full_name ?? 'Không xác định' }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $examination->patient?->phone ?? 'Chưa có số điện thoại' }}
                                    </p>

                                </div>

                            </div>

                            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-sm text-slate-500">

                                <span>
                                    Bác sĩ:
                                    <span class="font-medium text-slate-700">
                                        {{ $examination->doctor?->user?->name ?? '—' }}
                                    </span>
                                </span>

                                <span>
                                    Ngày khám:
                                    <span class="font-medium text-slate-700">
                                        {{ optional($examination->examined_at)->format('d/m/Y H:i') ?? '—' }}
                                    </span>
                                </span>

                            </div>

                        </div>

                        <form
                            method="POST"
                            action="{{ route('invoices.web.store', $examination) }}"
                            class="shrink-0"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>

                                Tạo hóa đơn
                            </button>
                        </form>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

</div>

@endsection