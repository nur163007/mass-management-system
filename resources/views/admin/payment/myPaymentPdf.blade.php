<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Payments Statement - {{ $member->full_name }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            color: #28a745;
            font-size: 22px;
        }
        .header h3 {
            margin: 0 0 5px 0;
            color: #495057;
            font-size: 16px;
        }
        .header p {
            margin: 0;
            color: #6c757d;
            font-size: 11px;
        }
        .member-info {
            margin-bottom: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px 12px;
        }
        .member-info table {
            width: 100%;
        }
        .member-info td {
            padding: 3px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 7px 8px;
            text-align: left;
        }
        .table th {
            background-color: #28a745;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
        }
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-center {
            text-align: center !important;
        }
        .text-right {
            text-align: right !important;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .total-box {
            margin-top: 10px;
            float: right;
            width: 250px;
            border: 1px solid #28a745;
            background-color: #e8f5e9;
            padding: 8px 12px;
            text-align: right;
        }
        .footer {
            clear: both;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            font-size: 10px;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Mess Management System</h2>
            <h3>Personal Payment Statement</h3>
            <p>Generated on: {{ date('d M Y, h:i A') }}</p>
        </div>

        <div class="member-info">
            <table>
                <tr>
                    <td style="width: 50%;"><strong>Member Name:</strong> {{ $member->full_name }}</td>
                    <td style="width: 50%; text-align: right;"><strong>Phone:</strong> {{ $member->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong> {{ $member->email ?? 'N/A' }}</td>
                    <td style="text-align: right;">
                        @if(!empty($selectedMonth))
                            <strong>Filtered Month:</strong> {{ $selectedMonth }}
                        @else
                            <strong>Period:</strong> All Time
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 35px;">SL</th>
                    <th>Payment Date</th>
                    <th>Month</th>
                    <th>Payment Type</th>
                    <th class="text-right">Amount</th>
                    <th class="text-center">Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    @php
                        $pType = $payment->payment_type ?? 'other';
                        $pLabel = $paymentTypes[$pType] ?? ucfirst(str_replace('_', ' ', $pType));
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ date('d M, Y', strtotime($payment->date)) }}</td>
                        <td>{{ $payment->month }}</td>
                        <td>{{ $pLabel }}</td>
                        <td class="text-right">Tk. {{ number_format($payment->payment_amount, 2) }}</td>
                        <td class="text-center">
                            @if($payment->status == 1)
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>{{ $payment->notes ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No payment records found.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f1f1f1; font-weight: bold;">
                    <td colspan="4" class="text-right">Grand Total:</td>
                    <td class="text-right" style="color: #28a745; font-size: 13px;">Tk. {{ number_format($totalPaid, 2) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>This is a computer-generated statement from Mess Management System.</p>
        </div>
    </div>
</body>
</html>
