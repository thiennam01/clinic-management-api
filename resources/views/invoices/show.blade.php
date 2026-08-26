@extends('layouts.app')

@section('title', 'Hóa đơn | Clinic App')

@section('content')

<div class="space-y-6">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-3">

                <a
                    href="{{ route('examinations.web.show', $invoice->examination) }}"
                    class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    title="Quay lại kết quả khám"
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
                        Hóa đơn
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Chi tiết hóa đơn và thanh toán.
                    </p>
                </div>

            </div>
        </div>

        @php
            $statusClasses = [
                'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                'unpaid' => 'bg-amber-50 text-amber-700 ring-amber-200',
                'cancelled' => 'bg-red-50 text-red-700 ring-red-200',
            ];

            $statusLabels = [
                'paid' => 'Đã thanh toán',
                'unpaid' => 'Chưa thanh toán',
                'cancelled' => 'Đã hủy',
            ];
        @endphp

        <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-sm font-semibold ring-1 {{ $statusClasses[$invoice->status] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
            {{ $statusLabels[$invoice->status] ?? ucfirst($invoice->status) }}
        </span>

    </div>


    {{-- Invoice + patient --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Invoice information --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">

            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <div>
                    <h2 class="font-semibold text-slate-900">
                        Thông tin hóa đơn
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $invoice->invoice_code }}
                    </p>
                </div>

                <div class="rounded-xl bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700">
                    #{{ $invoice->id }}
                </div>
            </div>

            <div class="divide-y divide-slate-100">

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Mã hóa đơn
                    </span>

                    <span class="font-semibold text-slate-900">
                        {{ $invoice->invoice_code }}
                    </span>
                </div>

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Ngày lập
                    </span>

                    <span class="font-medium text-slate-900">
                        {{ optional($invoice->issued_at)->format('d/m/Y H:i') ?? '—' }}
                    </span>
                </div>

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Tiền khám
                    </span>

                    <span class="font-medium text-slate-900">
                        {{ number_format(max(0, (float) $invoice->subtotal - 0), 0, ',', '.') }} đ
                    </span>
                </div>

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Giảm giá
                    </span>

                    <span class="font-medium text-slate-900">
                        {{ number_format((float) $invoice->discount, 0, ',', '.') }} đ
                    </span>
                </div>

                <div class="flex items-center justify-between bg-slate-50 px-6 py-5">
                    <span class="font-semibold text-slate-900">
                        Tổng hóa đơn
                    </span>

                    <span class="text-xl font-bold text-slate-900">
                        {{ number_format((float) $invoice->total, 0, ',', '.') }} đ
                    </span>
                </div>

            </div>

        </div>


        {{-- Patient --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Bệnh nhân
                </h2>
            </div>

            <div class="p-6">

                <div class="flex items-center gap-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                        {{ strtoupper(substr($invoice->examination?->patient?->full_name ?? '?', 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <p class="truncate font-semibold text-slate-900">
                            {{ $invoice->examination?->patient?->full_name ?? 'Không xác định' }}
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $invoice->examination?->patient?->phone ?? 'Chưa có số điện thoại' }}
                        </p>
                    </div>

                </div>

                <div class="mt-5 rounded-xl bg-slate-50 px-4 py-3 text-sm">

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-500">
                            Bác sĩ
                        </span>

                        <span class="text-right font-medium text-slate-700">
                            {{ $invoice->examination?->doctor?->user?->name ?? '—' }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Payment summary --}}
    @php
        $paidAmount = (float) $invoice->payments
            ->where('status', 'completed')
            ->sum('amount');

        $remainingAmount = max(
            0,
            (float) $invoice->total - $paidAmount
        );

        $paymentPercent = (float) $invoice->total > 0
            ? min(100, ($paidAmount / (float) $invoice->total) * 100)
            : 0;
    @endphp

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Payment status --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Đã thanh toán
            </p>

            <p class="mt-2 text-2xl font-bold text-emerald-600">
                {{ number_format($paidAmount, 0, ',', '.') }} đ
            </p>

            <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                <div
                    class="h-full rounded-full bg-emerald-500"
                    style="width: {{ $paymentPercent }}%"
                ></div>
            </div>

            <p class="mt-2 text-xs text-slate-400">
                {{ number_format($paymentPercent, 0) }}% tổng hóa đơn
            </p>

        </div>


        {{-- Remaining --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Còn phải trả
            </p>

            <p class="mt-2 text-2xl font-bold {{ $remainingAmount > 0 ? 'text-blue-600' : 'text-emerald-600' }}">
                {{ number_format($remainingAmount, 0, ',', '.') }} đ
            </p>

            <p class="mt-2 text-xs text-slate-400">
                {{ $remainingAmount > 0 ? 'Chưa hoàn tất thanh toán' : 'Hóa đơn đã được thanh toán đầy đủ' }}
            </p>

        </div>


        {{-- Total --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Tổng hóa đơn
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ number_format((float) $invoice->total, 0, ',', '.') }} đ
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Invoice {{ $invoice->invoice_code }}
            </p>

        </div>

    </div>


    {{-- Payment action --}}
    @if($remainingAmount > 0 && $invoice->status !== 'cancelled')

        <div class="flex justify-end">

            <a
                href="{{ route('payments.web.show', $invoice) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m-4-4h10m0 0l-3-3m3 3l-3 3"
                    />
                </svg>

                Thanh toán hóa đơn
            </a>

        </div>

    @endif


    {{-- Payment history --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h2 class="font-semibold text-slate-900">
                Lịch sử thanh toán
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Các giao dịch của hóa đơn này.
            </p>

        </div>

        @if($invoice->payments->isEmpty())

            <div class="px-6 py-12 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
                        />
                    </svg>
                </div>

                <p class="mt-3 text-sm font-medium text-slate-700">
                    Chưa có giao dịch
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Hóa đơn chưa phát sinh thanh toán.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">

                        <tr>
                            <th class="px-6 py-3">Thời gian</th>
                            <th class="px-6 py-3">Phương thức</th>
                            <th class="px-6 py-3">Số tiền</th>
                            <th class="px-6 py-3">Trạng thái</th>
                            <th class="px-6 py-3">Mã giao dịch</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach($invoice->payments as $payment)

                            <tr class="hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                    {{ optional($payment->paid_at ?? $payment->created_at)->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-700">
                                    {{ strtoupper($payment->method) }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-900">
                                    {{ number_format((float) $payment->amount, 0, ',', '.') }} đ
                                </td>

                                <td class="px-6 py-4">

                                    @php
                                        $paymentStatus = [
                                            'completed' => [
                                                'label' => 'Hoàn tất',
                                                'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                            ],
                                            'pending' => [
                                                'label' => 'Đang chờ',
                                                'class' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                            ],
                                            'failed' => [
                                                'label' => 'Thất bại',
                                                'class' => 'bg-red-50 text-red-700 ring-red-200',
                                            ],
                                        ];

                                        $paymentMeta = $paymentStatus[$payment->status] ?? [
                                            'label' => ucfirst($payment->status),
                                            'class' => 'bg-slate-50 text-slate-700 ring-slate-200',
                                        ];
                                    @endphp

                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $paymentMeta['class'] }}">
                                        {{ $paymentMeta['label'] }}
                                    </span>

                                </td>

                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                    {{ $payment->provider_capture_id ?? $payment->provider_order_id ?? '—' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>


    {{-- Footer actions --}}
    <div class="flex flex-wrap justify-end gap-3">

        <a
            href="{{ route('examinations.web.show', $invoice->examination) }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Quay lại kết quả khám
        </a>

    </div>

</div>

@endsection