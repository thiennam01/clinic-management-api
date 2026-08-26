@extends('layouts.app')

@section('title', 'Thanh toán hóa đơn | Clinic App')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('examinations.web.show', $invoice->examination_id) }}"
                class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                title="Quay lại kết quả khám"
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
                        d="M15 18l-6-6 6-6"
                    />
                </svg>
            </a>

            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Thanh toán hóa đơn
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $invoice->invoice_code }}
                </p>
            </div>

        </div>

        @php
            $statusClasses = match ($invoice->status) {
                'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                'cancelled' => 'bg-red-50 text-red-700 ring-red-200',
                default => 'bg-amber-50 text-amber-700 ring-amber-200',
            };

            $statusLabel = match ($invoice->status) {
                'paid' => 'Đã thanh toán',
                'cancelled' => 'Đã hủy',
                default => 'Chưa thanh toán',
            };
        @endphp

        <span
            class="inline-flex w-fit rounded-full px-3 py-1.5 text-sm font-semibold ring-1 {{ $statusClasses }}"
        >
            {{ $statusLabel }}
        </span>

    </div>


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


    {{-- Validation errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <p class="text-sm font-semibold text-red-800">
                Không thể thực hiện thanh toán
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Patient + Invoice --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Patient --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Thông tin bệnh nhân
                </h2>
            </div>

            <div class="p-6">

                <div class="flex items-center gap-4">

                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg font-bold text-blue-700">
                        {{ strtoupper(substr($invoice->examination?->patient?->full_name ?? '?', 0, 1)) }}
                    </div>

                    <div class="min-w-0">

                        <p class="text-lg font-semibold text-slate-900">
                            {{ $invoice->examination?->patient?->full_name ?? 'Không xác định' }}
                        </p>

                        <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-slate-500">

                            <span>
                                {{ $invoice->examination?->patient?->phone ?? 'Chưa có số điện thoại' }}
                            </span>

                            @if($invoice->examination?->patient?->email)
                                <span>
                                    {{ $invoice->examination->patient->email }}
                                </span>
                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Invoice meta --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Hóa đơn
                </h2>
            </div>

            <div class="divide-y divide-slate-100">

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Mã hóa đơn
                    </span>

                    <span class="text-sm font-semibold text-slate-900">
                        {{ $invoice->invoice_code }}
                    </span>
                </div>

                <div class="flex items-center justify-between px-6 py-4">
                    <span class="text-sm text-slate-500">
                        Ngày lập
                    </span>

                    <span class="text-sm font-medium text-slate-900">
                        {{ optional($invoice->issued_at)->format('d/m/Y H:i') ?? '—' }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- Payment summary --}}
    <div class="grid gap-4 sm:grid-cols-3">

        {{-- Subtotal --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm text-slate-500">
                Tạm tính
            </p>

            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ number_format((float) $invoice->subtotal, 0, ',', '.') }}
                <span class="text-sm font-medium text-slate-500">₫</span>
            </p>

        </div>


        {{-- Paid --}}
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">

            <p class="text-sm text-emerald-700">
                Đã thanh toán
            </p>

            <p class="mt-2 text-2xl font-bold text-emerald-700">
                {{ number_format($paidAmount, 0, ',', '.') }}
                <span class="text-sm font-medium">₫</span>
            </p>

        </div>


        {{-- Remaining --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 shadow-sm">

            <p class="text-sm text-blue-700">
                Còn phải thanh toán
            </p>

            <p class="mt-2 text-2xl font-bold text-blue-700">
                {{ number_format($remainingAmount, 0, ',', '.') }}
                <span class="text-sm font-medium">₫</span>
            </p>

        </div>

    </div>


    {{-- Invoice detail --}}
    <div class="grid gap-6 lg:grid-cols-5">

        {{-- Payment form --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-3">

            <div class="border-b border-slate-200 px-6 py-4">

                <h2 class="font-semibold text-slate-900">
                    Thực hiện thanh toán
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Thanh toán được xử lý an toàn qua PayPal Sandbox.
                </p>

            </div>


            @if($remainingAmount > 0 && $invoice->status !== 'cancelled')

                <form
                    action="{{ route('payments.web.store', $invoice) }}"
                    method="POST"
                    class="space-y-6 p-6"
                >

                    @csrf


                    {{-- Amount --}}
                    <div>

                        <label
                            for="amount"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Số tiền thanh toán
                        </label>

                        <div class="relative">

                            <input
                                id="amount"
                                name="amount"
                                type="number"
                                min="0.01"
                                max="{{ $remainingAmount }}"
                                step="0.01"
                                value="{{ old('amount', $remainingAmount) }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                required
                            >

                            <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm text-slate-400">
                                ₫
                            </span>

                        </div>

                        <p class="mt-2 text-xs text-slate-500">
                            Tối đa:
                            <strong>
                                {{ number_format($remainingAmount, 0, ',', '.') }} ₫
                            </strong>
                        </p>

                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Method --}}
                    <div>

                        <label class="mb-3 block text-sm font-semibold text-slate-700">
                            Phương thức thanh toán
                        </label>

                        <div class="grid gap-3 sm:grid-cols-2">

                            {{-- PayPal --}}
                            <label class="relative cursor-pointer">

                                <input
                                    type="radio"
                                    name="method"
                                    value="paypal"
                                    class="peer sr-only"
                                    {{ old('method', 'paypal') === 'paypal' ? 'checked' : '' }}
                                >

                                <div class="rounded-xl border border-slate-200 p-4 transition peer-checked:border-blue-500 peer-checked:bg-blue-50">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

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
                                                    d="M7 20l2-12h7a4 4 0 010 8h-5"
                                                />
                                            </svg>

                                        </div>

                                        <div>

                                            <p class="text-sm font-semibold text-slate-900">
                                                PayPal
                                            </p>

                                            <p class="mt-0.5 text-xs text-slate-500">
                                                Thanh toán qua PayPal
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </label>


                            {{-- Visa --}}
                            <label class="relative cursor-pointer">

                                <input
                                    type="radio"
                                    name="method"
                                    value="visa"
                                    class="peer sr-only"
                                    {{ old('method') === 'visa' ? 'checked' : '' }}
                                >

                                <div class="rounded-xl border border-slate-200 p-4 transition peer-checked:border-blue-500 peer-checked:bg-blue-50">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-700">

                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <rect
                                                    x="3"
                                                    y="5"
                                                    width="18"
                                                    height="14"
                                                    rx="2"
                                                    stroke-width="1.8"
                                                />
                                                <path
                                                    stroke-width="1.8"
                                                    d="M3 10h18"
                                                />
                                            </svg>

                                        </div>

                                        <div>

                                            <p class="text-sm font-semibold text-slate-900">
                                                Visa
                                            </p>

                                            <p class="mt-0.5 text-xs text-slate-500">
                                                Thẻ Visa qua PayPal
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </label>

                        </div>

                        @error('method')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Sandbox notice --}}
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">

                        <div class="flex gap-3">

                            <svg
                                class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M12 9v4m0 4h.01M10.3 3.8l-7.1 12.3A2 2 0 005 19h14a2 2 0 001.8-2.9L13.7 3.8a2 2 0 00-3.4 0z"
                                />
                            </svg>

                            <div>

                                <p class="text-sm font-semibold text-amber-800">
                                    PayPal Sandbox
                                </p>

                                <p class="mt-1 text-xs leading-5 text-amber-700">
                                    Đây là môi trường thử nghiệm.
                                    Không sử dụng tiền thật.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
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
                                d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm3 9h4"
                            />
                        </svg>

                        Tiếp tục thanh toán

                    </button>

                </form>

            @else

                <div class="p-8 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">

                        <svg
                            class="h-7 w-7"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>

                    <h3 class="mt-4 text-lg font-semibold text-slate-900">
                        Hóa đơn đã được thanh toán
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Không còn số tiền cần thanh toán.
                    </p>

                </div>

            @endif

        </div>


        {{-- Invoice total --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">

            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="font-semibold text-slate-900">
                    Chi tiết hóa đơn
                </h2>
            </div>

            <div class="space-y-4 p-6">

                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">
                        Tạm tính
                    </span>

                    <span class="font-medium text-slate-900">
                        {{ number_format((float) $invoice->subtotal, 0, ',', '.') }} ₫
                    </span>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">
                        Giảm giá
                    </span>

                    <span class="font-medium text-emerald-600">
                        -{{ number_format((float) $invoice->discount, 0, ',', '.') }} ₫
                    </span>
                </div>

                <div class="border-t border-slate-100 pt-4">

                    <div class="flex items-end justify-between">

                        <span class="font-semibold text-slate-900">
                            Tổng cộng
                        </span>

                        <span class="text-2xl font-bold text-slate-900">
                            {{ number_format((float) $invoice->total, 0, ',', '.') }}
                            <span class="text-sm font-medium text-slate-500">
                                ₫
                            </span>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Payment history --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h2 class="font-semibold text-slate-900">
                Lịch sử thanh toán
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Các giao dịch liên quan đến hóa đơn này.
            </p>

        </div>


        @if($invoice->payments->isEmpty())

            <div class="px-6 py-10 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-width="1.8"
                            d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm3 9h4"
                        />
                    </svg>

                </div>

                <p class="mt-3 text-sm font-medium text-slate-700">
                    Chưa có giao dịch
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Các giao dịch thanh toán sẽ xuất hiện tại đây.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Phương thức
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Số tiền
                            </th>

                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Trạng thái
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Thời gian
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($invoice->payments as $payment)

                            @php
                                $paymentStatus = match ($payment->status) {
                                    'completed' => [
                                        'label' => 'Thành công',
                                        'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                    ],
                                    'failed' => [
                                        'label' => 'Thất bại',
                                        'class' => 'bg-red-50 text-red-700 ring-red-200',
                                    ],
                                    default => [
                                        'label' => 'Đang xử lý',
                                        'class' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                    ],
                                };
                            @endphp

                            <tr class="hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                                            @if($payment->method === 'visa')
                                                <span class="text-xs font-bold">
                                                    VISA
                                                </span>
                                            @else
                                                <span class="text-xs font-bold">
                                                    PP
                                                </span>
                                            @endif
                                        </div>

                                        <div>

                                            <p class="text-sm font-semibold capitalize text-slate-900">
                                                {{ $payment->method === 'visa' ? 'Visa' : 'PayPal' }}
                                            </p>

                                            @if($payment->provider_order_id)
                                                <p class="mt-0.5 text-xs text-slate-400">
                                                    {{ $payment->provider_order_id }}
                                                </p>
                                            @endif

                                        </div>

                                    </div>

                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-slate-900">
                                    {{ number_format((float) $payment->amount, 0, ',', '.') }} ₫
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-center">

                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $paymentStatus['class'] }}">
                                        {{ $paymentStatus['label'] }}
                                    </span>

                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-slate-500">
                                    {{ optional($payment->paid_at ?? $payment->created_at)->format('d/m/Y H:i') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>


    {{-- Footer --}}
    <div class="flex justify-end">

        <a
            href="{{ route('examinations.web.show', $invoice->examination_id) }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Quay lại kết quả khám
        </a>

    </div>

</div>

@endsection