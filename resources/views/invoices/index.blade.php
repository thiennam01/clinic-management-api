@extends('layouts.app')

@section('title', 'Hóa đơn | Clinic App')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Hóa đơn
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Quản lý và theo dõi các hóa đơn của bệnh nhân.
            </p>
        </div>

        <a
            href="{{ route('invoices.web.create') }}"
            class="inline-flex w-fit items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
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
        </a>

    </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Hóa đơn
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Quản lý và theo dõi các hóa đơn của bệnh nhân.
            </p>
        </div>
    </div>

    {{-- Filters --}}
    <form
        method="GET"
        action="{{ route('invoices.web.index') }}"
        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
    >
        <div class="grid gap-3 md:grid-cols-3">

            <div class="md:col-span-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Tìm mã hóa đơn, tên bệnh nhân, số điện thoại..."
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
            </div>

            <div>
                <select
                    name="status"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">Tất cả trạng thái</option>
                    <option value="unpaid" @selected(request('status') === 'unpaid')>
                        Chưa thanh toán
                    </option>
                    <option value="paid" @selected(request('status') === 'paid')>
                        Đã thanh toán
                    </option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>
                        Đã hủy
                    </option>
                </select>
            </div>

        </div>

        <div class="mt-3 flex justify-end">
            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
            >
                Tìm kiếm
            </button>
        </div>
    </form>

    {{-- Invoice table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Hóa đơn
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Bệnh nhân
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Ngày lập
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Tổng tiền
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Trạng thái
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Thao tác
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($invoices as $invoice)

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

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-slate-900">
                                        {{ $invoice->invoice_code }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        #{{ $invoice->id }}
                                    </p>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-slate-900">
                                        {{ $invoice->examination?->patient?->full_name ?? '—' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $invoice->examination?->patient?->phone ?? 'Chưa có SĐT' }}
                                    </p>
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ optional($invoice->issued_at)->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <span class="font-semibold text-slate-900">
                                    {{ number_format((float) $invoice->total, 0, ',', '.') }} đ
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $statusClasses[$invoice->status] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                                    {{ $statusLabels[$invoice->status] ?? ucfirst($invoice->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('invoices.web.show', $invoice) }}"
                                    class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-50"
                                >
                                    Xem
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">

                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M9 14h6m-6-4h6m2 10H7a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4v10a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                </div>

                                <p class="mt-3 font-medium text-slate-700">
                                    Chưa có hóa đơn
                                </p>

                                <p class="mt-1 text-sm text-slate-400">
                                    Không tìm thấy hóa đơn phù hợp.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        @if($invoices->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $invoices->links() }}
            </div>
        @endif

    </div>

</div>

@endsection