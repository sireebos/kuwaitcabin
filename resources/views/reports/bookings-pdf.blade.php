<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bookings Report</title>
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
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-confirmed {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-checked-in {
            background-color: #dbeafe;
            color: #0c4a6e;
        }
        .status-checked-out {
            background-color: #f3f4f6;
            color: #374151;
        }
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bookings Report</h1>
        <p><strong>{{ $property->name }}</strong></p>
        <p>Period: {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}</p>
        <p>Generated: {{ now()->format('M d, Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h3>Total Bookings</h3>
            <div class="amount">{{ $totalBookings }}</div>
        </div>
        <div class="summary-card">
            <h3>Total Revenue</h3>
            <div class="amount">₦{{ number_format(abs($totalRevenue), 2) }}</div>
        </div>
        <div class="summary-card">
            <h3>Average Booking</h3>
            <div class="amount">₦{{ number_format(abs($totalBookings > 0 ? $totalRevenue / $totalBookings : 0), 2) }}</div>
        </div>
    </div>

    <div class="section-title">Bookings by User</div>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Bookings</th>
                <th>Total Revenue</th>
                <th>Average</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookingsByUser as $booking)
                <tr>
                    <td>{{ $booking->name }}</td>
                    <td>{{ $booking->booking_count }}</td>
                    <td>₦{{ number_format(abs($booking->total_revenue), 2) }}</td>
                    <td>₦{{ number_format(abs($booking->total_revenue / $booking->booking_count), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999;">No booking data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Detailed Bookings</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Guest</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                    <td>{{ $booking->user?->name ?? 'N/A' }}</td>
                    <td>{{ $booking->guest?->full_name ?? 'N/A' }}</td>
                    <td>{{ $booking->room?->room_number ?? 'N/A' }}</td>
                    <td>{{ $booking->check_in_date }}</td>
                    <td>{{ $booking->check_out_date }}</td>
                    <td>₦{{ number_format(abs($booking->total_amount), 2) }}</td>
                    <td>
                        <span class="status status-{{ str_replace('_', '-', $booking->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #999;">No bookings found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This is an automatically generated report. For more information, please contact support.</p>
    </div>
</body>
</html>

