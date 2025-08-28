<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $payment->order?->order_id }}</title>
    <style>
        /* Basic document styles */
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            color: #333;
            -webkit-text-size-adjust: 100%;
            font-size: 13px;
            /* Further reduced font size */
        }

        /* A4 Page Dimensions */
        @page {
            size: A4;
            margin: 10mm;
            /* A little more margin for safety */
        }

        .invoice-container {
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            overflow: hidden;
        }

        /* Header section */
        .invoice-header {
            background-color: #f97316;
            background-image: linear-gradient(to bottom right, #f97316, #fb923c);
            color: #fff;
            padding: 18px;
            /* Reduced padding */
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo-wrapper {
            background-color: #fff;
            color: #f97316;
            padding: 8px;
            /* Reduced padding */
            border-radius: 6px;
            display: inline-block;
        }

        .logo-text {
            font-size: 16px;
            /* Reduced font size */
        }

        /* Main content */
        .invoice-body {
            padding: 18px;
            /* Reduced padding */
        }

        /* Details sections using a table for layout */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            /* Reduced margin */
        }

        .details-table td {
            padding: 12px;
            /* Reduced padding */
            background-color: #f9fafb;
            border-radius: 6px;
            vertical-align: top;
            width: 33.33%;
        }

        .details-table td:not(:last-child) {
            padding-right: 10px;
        }

        .detail-title {
            font-size: 15px;
            /* Reduced font size */
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
            border-bottom: 2px solid #f97316;
            padding-bottom: 4px;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 10px;
            /* Reduced font size */
        }

        .badge-soft-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        /* Items table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            border: 1px solid #f97316;
            border-radius: 6px;
        }

        .items-table th,
        .items-table td {
            padding: 10px 12px;
            /* Reduced padding */
            text-align: left;
            font-size: 12px;
            /* Reduced font size */
        }

        .items-table thead {
            background-color: #f97316;
            color: #fff;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge-credits {
            background-color: rgba(249, 115, 22, 0.1);
            color: #f97316;
            padding: 5px 8px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 11px;
            /* Reduced font size */
            white-space: nowrap;
        }

        /* Summary and footer using tables */
        .summary-details {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 18px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            vertical-align: top;
            padding: 4px;
            /* Reduced padding */
        }

        .total-amount-box {
            background-color: #fff;
            padding: 4px;
            /* Reduced padding */
            border: 2px solid #f97316;
            border-radius: 6px;
            text-align: center;
        }

        .notes-section {
            background-color: #fffbeb;
            border-left: 4px solid #fbbf24;
            padding: 10px;
            /* Reduced padding */
            margin-bottom: 18px;
        }

        .invoice-footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-text {
            text-align: right;
            font-size: 11px;
            /* Reduced font size */
        }

        .footer-generated {
            font-size: 9px;
            /* Reduced font size */
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <table class="header-table">
                <tr>
                    <td style="width: 50%;">
                        <h1 style="font-size: 26px; font-weight: bold; margin-bottom: 5px;">INVOICE</h1>
                        <div
                            style="background-color: rgba(251, 146, 60, 0.2); border-radius: 6px; padding: 10px; backdrop-filter: blur(4px);">
                            <p style="font-size: 9px; opacity: 0.9;">Invoice ID</p>
                            <p style="font-size: 14px; font-weight: 600;">{{ invoiceId() }}</p>
                        </div>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <div class="logo-wrapper">
                            {{-- <span style="font-size: 16px; font-weight: bold;">R</span> --}}
                            <span class="logo-text" style="font-weight: bold;">REPOST<span
                                    style="color: #f97316;">CHAIN</span></span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-body">
            <table class="details-table">
                <tr>
                    <td>
                        <h3 class="detail-title">Bill To</h3>
                        <div style="font-size: 12px; color: #4b5563;">
                            <p style="font-weight: 500; margin-bottom: 2px;">
                                {{ $payment->name ?? $payment->user?->name }}</p>
                            <p>{{ $payment->email_address ?? $payment->user?->email }}</p>
                            <p>{{ $payment->address ?? $payment->user?->address }}</p>
                        </div>
                    </td>

                    <td>
                        <h3 class="detail-title">Invoice Details</h3>
                        <div style="font-size: 12px; color: #4b5563;">
                            <p><span>Date:</span> <span
                                    style="font-weight: 500;">{{ date('M d, Y', strtotime($payment->created_at)) }}</span>
                            </p>
                            <p><span>Order ID:</span> <span
                                    style="font-weight: 500;">{{ $payment->order?->order_id }}</span></p>
                            <p><span>Status:</span> <span
                                    class="badge badge-soft-{{ $payment->status_color }}">{{ $payment->status }}</span>
                            </p>
                        </div>
                    </td>

                    <td>
                        <h3 class="detail-title">Payment Details</h3>
                        <div style="font-size: 12px; color: #4b5563;">
                            <p><span>Method:</span> <span
                                    style="font-weight: 500;">{{ $payment->payment_method ?? 'N/A' }}</span></p>
                            <p><span>Gateway:</span> <span
                                    style="font-weight: 500;">{{ $payment->payment_gateway_label ?? 'N/A' }}</span></p>
                            <p><span>Currency:</span> <span
                                    style="font-weight: 500;">{{ $payment->currency ?? 'N/A' }}</span></p>
                        </div>
                    </td>
                </tr>
            </table>

            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">Purchase Details</h3>
            <div style="border-radius: 6px; border: 1px solid #f97316; overflow: hidden;">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-center">Credits</th>
                            <th class="text-center">Rate</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div>
                                    <p style="font-weight: 500; color: #1f2937;">{{ $payment->notes }}</p>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-credits">
                                    {{ $payment->credits_purchased ?? '0.00' }} Credits
                                </span>
                            </td>
                            <td class="text-center" style="font-weight: 500;">
                                {{ ($payment->exchange_rate ?? '0.00') . ' ' . $payment->currency }}
                            </td>
                            <td class="text-right" style="font-weight: bold; font-size: 14px;">
                                {{ ($payment->amount ?? '0.00') . ' ' . $payment->currency }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="summary-details">
                <h3 style="font-size: 15px; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Transaction Summary
                </h3>
                <table class="summary-table">
                    <tr>
                        <td style="width: 50%;">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="font-size: 12px; color: #4b5563;">Subtotal:</td>
                                    <td style="text-align: right; font-weight: 500; font-size: 12px;">
                                        {{ ($payment->amount ?? '0.00') . ' ' . $payment->currency }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; color: #4b5563;">Processing Fee:</td>
                                    <td style="text-align: right; font-weight: 500; font-size: 12px;">
                                        {{ '0.00' . ' ' . $payment->currency }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="font-size: 15px; font-weight: 600; border-top: 1px solid #f97316; padding-top: 5px;">
                                        Total Amount:</td>
                                    <td
                                        style="text-align: right; font-size: 16px; font-weight: bold; color: #f97316; border-top: 1px solid #f97316; padding-top: 5px;">
                                        {{ ($payment->amount ?? '0.00') . ' ' . $payment->currency }}</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%; padding-left: 15px;">
                            <div class="total-amount-box">
                                @if ($payment->order?->source_type == App\Models\Plan::class)
                                    <p style="font-size: 10px; color: #4b5563;">Your Subscribed Plan
                                    </p>
                                    <p style="font-size: 22px; font-weight: bold; color: #f97316;">
                                        {{ $payment->order?->source?->name ?? 'N/A' }}</p>
                                @else
                                    <p style="font-size: 10px; color: #4b5563;">Credits Purchased
                                    </p>
                                    <p style="font-size: 22px; font-weight: bold; color: #f97316;">
                                        {{ $payment->credits_purchased ?? '0.00' }}</p>
                                    <p style="font-size: 8px; color: #6b7280;">Added to your account
                                    </p>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="notes-section">
                <h4 style="font-weight: 600; color: #b45309; margin-bottom: 5px;">Transaction Notes</h4>
                <p style="font-size: 12px; color: #92400e;">{{ $payment->notes ?? 'N/A' }}</p>
            </div>

            <div style="border-top: 1px solid #f97316; padding-top: 15px;">
                <table class="invoice-footer-table">
                    <tr>
                        <td style="width: 50%;">
                            <h4 style="font-weight: 600; color: #1f2937; margin-bottom: 5px;">Payment Information</h4>
                            <p style="font-size: 11px; color: #4b5563;">
                                Payment processed securely through {{ $payment->payment_gateway_label ?? 'N/A' }}.<br>
                                Order ID: <span
                                    style="font-family: monospace; background-color: #f3f4f6; padding: 1px 3px; border-radius: 3px;">{{ $payment->order?->order_id ?? 'N/A' }}</span>
                            </p>
                        </td>
                        <td class="footer-text" style="width: 50%;">
                            <h4 style="font-weight: 600; color: #1f2937; margin-bottom: 5px;">Contact Information</h4>
                            <p style="font-size: 11px; color: #4b5563;">
                                support@yourcompany.com<br>
                                +1 (555) 123-4567
                            </p>
                        </td>
                    </tr>
                </table>
                <div style="margin-top: 15px; text-align: center; padding: 8px 0; border-top: 1px solid #f97316;">
                    <p style="font-size: 9px; color: #6b7280;">
                        This invoice was generated automatically on <span
                            id="generated-date">{{ date('M d, Y h:i A', strtotime($payment->created_at)) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
