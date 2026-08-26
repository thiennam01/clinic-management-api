<?php

namespace App\Constants;

class PaymentConstant
{
    public const CREATED_SUCCESSFULLY = 'Payment order created successfully.';
    public const CAPTURED_SUCCESSFULLY = 'Payment captured successfully.';

    public const PAYMENT_SUCCESS = 'Thanh toán thành công.';
    public const PAYMENT_FAILED = 'Thanh toán thất bại.';
    public const PAYMENT_CANCELLED = 'Đã hủy thanh toán.';

    public const AMOUNT_EXCEEDS_REMAINING = 'Payment amount cannot exceed the remaining invoice amount.';
    public const INVOICE_ALREADY_PAID = 'This invoice has already been fully paid.';
    public const PAYMENT_NOT_PENDING = 'Payment is not pending.';

    public const PAYPAL_AUTH_FAILED = 'Unable to authenticate with PayPal.';
    public const PAYPAL_TOKEN_NOT_RETURNED = 'PayPal access token was not returned.';
    public const PAYPAL_ORDER_CREATE_FAILED = 'Unable to create PayPal order.';
    public const PAYPAL_ORDER_ID_NOT_RETURNED = 'PayPal order ID was not returned.';
    public const PAYPAL_APPROVAL_URL_NOT_RETURNED = 'PayPal approval URL was not returned.';
    public const PAYPAL_CAPTURE_FAILED = 'Unable to capture PayPal payment.';
    public const PAYPAL_CAPTURE_ID_NOT_RETURNED = 'PayPal capture ID was not returned.';
}