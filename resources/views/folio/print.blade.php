<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folio #{{ $folio->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 28px;
            color: #1e40af;
        }
        .header-info {
            text-align: right;
            font-size: 12px;
        }
        .property-info {
            margin-bottom: 30px;
            padding: 15px;
            background: #f9fafb;
            border-left: 4px solid #1e40af;
        }
        .property-info h3 {
            color: #1e40af;
            margin-bottom: 5px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .guest-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .info-block {
            padding: 10px;
        }
        .info-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background: #f3f4f6;
            border-top: 2px solid #d1d5db;
            border-bottom: 2px solid #d1d5db;
        }
        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 13px;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        tbody tr:hover {
            background: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .summary-row.total {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            border-top: 2px solid #d1d5db;
            padding-top: 12px;
            margin-top: 12px;
        }
        .summary-row.balance {
            font-size: 16px;
            font-weight: bold;
            padding: 12px;
            border-radius: 4px;
            margin-top: 12px;
        }
        .balance-due {
            background: #fee2e2;
            color: #991b1b;
        }
        .balance-paid {
            background: #dcfce7;
            color: #166534;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .print-button {
            display: none;
        }
        @media print {
            body {
                background: white;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
            .print-button {
                display: none;
            }
        }
        .no-print {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        .btn-print {
            background: #1e40af;
            color: white;
        }
        .btn-print:hover {
            background: #1e3a8a;
        }
        .btn-back {
            background: #6b7280;
            color: white;
        }
        .btn-back:hover {
            background: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- No Print Buttons -->
        <div class="no-print">
            <button class="btn btn-print" onclick="window.print()">🖨️ Print Folio</button>
            <a href="{{ route('folio.show', [$property, $booking]) }}" class="btn btn-back">← Back to Folio</a>
        </div>

        <!-- Header -->
        <div class="header">
            <div>
                <h1>FOLIO</h1>
                <p style="color: #6b7280; font-size: 14px;">Folio #{{ $folio->id }}</p>
            </div>
            <div class="header-info">
                <p><strong>Date Printed:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Booking Ref:</strong> {{ $booking->booking_reference }}</p>
            </div>
        </div>

        <!-- Property Information -->
        <div class="property-info">
            <h3>{{ $property->name }}</h3>
            <p>{{ $property->address }}, {{ $property->city }}, {{ $property->country }}</p>
            <p>Phone: {{ $property->phone }} | Email: {{ $property->email }}</p>
        </div>

        <!-- Guest Information -->
        <div class="section">
            <div class="section-title">Guest Information</div>
            <div class="guest-info">
                <div class="info-block">
                    <div class="info-label">Guest Name</div>
                    <div class="info-value">{{ $booking->guest->full_name }}</div>
                </div>
                <div class="info-block">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $booking->guest->email }}</div>
                </div>
                <div class="info-block">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $booking->guest->phone }}</div>
                </div>
                <div class="info-block">
                    <div class="info-label">Room</div>
                    <div class="info-value">{{ $booking->room->room_number ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Stay Information -->
        <div class="section">
            <div class="section-title">Stay Information</div>
            <div class="guest-info">
                <div class="info-block">
                    <div class="info-label">Check-in Date</div>
                    <div class="info-value">{{ $booking->check_in_date->format('M d, Y') }}</div>
                </div>
                <div class="info-block">
                    <div class="info-label">Check-out Date</div>
                    <div class="info-value">{{ $booking->check_out_date->format('M d, Y') }}</div>
                </div>
                <div class="info-block">
                    <div class="info-label">Number of Nights</div>
                    <div class="info-value">{{ $booking->number_of_nights }}</div>
                </div>
                <div class="info-block">
                    <div class="info-label">Number of Guests</div>
                    <div class="info-value">{{ $booking->number_of_guests }}</div>
                </div>
            </div>
        </div>

        <!-- Charges -->
        <div class="section">
            <div class="section-title">Charges</div>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Type</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($folio->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ ucfirst($item->type) }}</td>
                            <td class="text-right">₦{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #9ca3af;">No charges</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="summary">
            <div class="section-title" style="margin-bottom: 15px;">Summary</div>
            <div class="summary-row">
                <span>Room Charges:</span>
                <span>₦{{ number_format(abs($booking->subtotal ?? 0), 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Tax ({{ $property->tax_rate !== null ? $property->tax_rate : 10 }}%):</span>
                <span>₦{{ number_format(abs($booking->tax ?? 0), 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Restaurant Charges:</span>
                <span>₦{{ number_format(abs($folio->restaurant_charges ?? 0), 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Other Charges:</span>
                <span>₦{{ number_format(abs($folio->other_charges ?? 0), 2) }}</span>
            </div>
            <div class="summary-row total">
                <span>Total Charges:</span>
                <span>₦{{ number_format(abs(($booking->total_amount ?? 0) + ($folio->restaurant_charges ?? 0) + ($folio->other_charges ?? 0)), 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Payments Received:</span>
                <span>₦{{ number_format(abs($folio->payments_received ?? 0), 2) }}</span>
            </div>
            <div class="summary-row balance @if((($booking->total_amount ?? 0) + ($folio->restaurant_charges ?? 0) + ($folio->other_charges ?? 0) - ($folio->payments_received ?? 0)) > 0) balance-due @else balance-paid @endif">
                <span>Balance Due:</span>
                <span>₦{{ number_format(abs((($booking->total_amount ?? 0) + ($folio->restaurant_charges ?? 0) + ($folio->other_charges ?? 0) - ($folio->payments_received ?? 0))), 2) }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an official folio from {{ $property->name }}. For inquiries, please contact us at {{ $property->phone }} or {{ $property->email }}</p>
            <p style="margin-top: 10px;">Generated on {{ now()->format('M d, Y \a\t H:i:s') }}</p>
        </div>
    </div>
</body>
</html>

