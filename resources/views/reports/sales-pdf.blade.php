<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #1f2937;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            flex: 1;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9fafb;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 12px;
        }
        .summary-card .amount {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table thead {
            background-color: #f3f4f6;
            border-bottom: 2px solid #333;
        }
        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #1f2937;
            font-size: 12px;
        }
        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #1f2937;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p><strong>{{ $property->name }}</strong></p>
        <p>Period: {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>
        <p>Generated: {{ now()->format('M d, Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h3>Total Sales</h3>
            <div class="amount">₦{{ number_format(abs($totalSales), 2) }}</div>
        </div>
        <div class="summary-card">
            <h3>Total Transactions</h3>
            <div class="amount">{{ $payments->count() + $orders->count() }}</div>
        </div>
    </div>

    <div class="section-title">Sales by User</div>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Transactions</th>
                <th>Total Sales</th>
                <th>Average</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Combine booking payments and restaurant orders by user
                $userSales = [];

                // Add booking payments
                foreach($salesByUser as $sale) {
                    if (!isset($userSales[$sale->name])) {
                        $userSales[$sale->name] = [
                            'transactions' => 0,
                            'total' => 0
                        ];
                    }
                    $userSales[$sale->name]['transactions'] += $sale->transaction_count;
                    $userSales[$sale->name]['total'] += $sale->total;
                }

                // Add restaurant orders
                foreach($orders as $order) {
                    $userName = $order->user?->name ?? 'N/A';
                    if (!isset($userSales[$userName])) {
                        $userSales[$userName] = [
                            'transactions' => 0,
                            'total' => 0
                        ];
                    }
                    $userSales[$userName]['transactions'] += 1;
                    $userSales[$userName]['total'] += $order->total;
                }

                // Sort by total descending
                uasort($userSales, function($a, $b) {
                    return $b['total'] <=> $a['total'];
                });
            @endphp

            @forelse($userSales as $userName => $sale)
                <tr>
                    <td>{{ $userName }}</td>
                    <td>{{ $sale['transactions'] }}</td>
                    <td>₦{{ number_format(abs($sale['total']), 2) }}</td>
                    <td>₦{{ number_format(abs($sale['total'] / $sale['transactions']), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999;">No sales data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Detailed Transactions</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>User</th>
                <th>Guest</th>
                <th>Amount</th>
                <th>Method</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                    <td>Booking</td>
                    <td>{{ $payment->booking?->user?->name ?? 'N/A' }}</td>
                    <td>{{ $payment->booking?->guest?->full_name ?? 'N/A' }}</td>
                    <td>₦{{ number_format(abs($payment->amount), 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                </tr>
            @empty
                @if($orders->isEmpty())
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">No transactions found</td>
                    </tr>
                @endif
            @endforelse

            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->paid_at->format('M d, Y H:i') }}</td>
                    <td>Restaurant</td>
                    <td>{{ $order->user?->name ?? 'N/A' }}</td>
                    <td>Order #{{ $order->order_number }}</td>
                    <td>₦{{ number_format($order->total, 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This is an automatically generated report. For more information, please contact support.</p>
    </div>
</body>
</html>

