<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 20px;
        }
        .section {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .section:last-child {
            border-bottom: none;
        }
        .section h2 {
            color: #667eea;
            font-size: 18px;
            margin-top: 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .booking-number {
            background-color: #f0f0f0;
            padding: 10px;
            border-left: 4px solid #667eea;
            margin: 15px 0;
            font-weight: bold;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin: 15px 0;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        table td:first-child {
            font-weight: bold;
            width: 40%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Booking Confirmed</h1>
        </div>

        <div class="content">
            <!-- Greeting -->
            <p>Dear {{ $guest->first_name }},</p>
            <p>Thank you for your booking! We're excited to welcome you to {{ $property->name }}. Your reservation has been confirmed.</p>

            <!-- Booking Number -->
            <div class="booking-number">
                Booking Number: {{ $booking->booking_number }}
            </div>

            <!-- Status -->
            <div class="status-confirmed">
                ✓ Your booking is confirmed
            </div>

            <!-- Guest Information -->
            <div class="section">
                <h2>Guest Information</h2>
                <table>
                    <tr>
                        <td>Name:</td>
                        <td>{{ $guest->first_name }} {{ $guest->last_name }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ $guest->email }}</td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td>{{ $guest->phone }}</td>
                    </tr>
                </table>
            </div>

            <!-- Booking Details -->
            <div class="section">
                <h2>Booking Details</h2>
                <table>
                    <tr>
                        <td>Property:</td>
                        <td>{{ $property->name }}</td>
                    </tr>
                    <tr>
                        <td>Room:</td>
                        <td>{{ $room->room_number }} ({{ $room->roomType->name }})</td>
                    </tr>
                    <tr>
                        <td>Check-in:</td>
                        <td>{{ $booking->check_in_date->format('M d, Y') }} ({{ $property->check_in_time }}:00)</td>
                    </tr>
                    <tr>
                        <td>Check-out:</td>
                        <td>{{ $booking->check_out_date->format('M d, Y') }} ({{ $property->check_out_time }}:00)</td>
                    </tr>
                    <tr>
                        <td>Number of Nights:</td>
                        <td>{{ $booking->check_out_date->diffInDays($booking->check_in_date) }}</td>
                    </tr>
                    <tr>
                        <td>Number of Guests:</td>
                        <td>{{ $booking->number_of_guests }}</td>
                    </tr>
                </table>
            </div>

            <!-- Pricing -->
            <div class="section">
                <h2>Pricing Summary</h2>
                <table>
                    <tr>
                        <td>Room Rate:</td>
                        <td>{{ $property->currency }} {{ number_format($booking->room_rate, 2) }} per night</td>
                    </tr>
                    <tr>
                        <td>Number of Nights:</td>
                        <td>{{ $booking->check_out_date->diffInDays($booking->check_in_date) }}</td>
                    </tr>
                    <tr>
                        <td>Subtotal:</td>
                        <td>{{ $property->currency }} {{ number_format($booking->total_amount, 2) }}</td>
                    </tr>
                    <tr style="font-weight: bold; font-size: 16px;">
                        <td>Total Amount:</td>
                        <td>{{ $property->currency }} {{ number_format($booking->total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Property Information -->
            <div class="section">
                <h2>Property Information</h2>
                <table>
                    <tr>
                        <td>Address:</td>
                        <td>{{ $property->address }}, {{ $property->city }}, {{ $property->state }} {{ $property->postal_code }}</td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td>{{ $property->phone }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ $property->email }}</td>
                    </tr>
                    @if($property->website)
                    <tr>
                        <td>Website:</td>
                        <td><a href="{{ $property->website }}">{{ $property->website }}</a></td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- Important Information -->
            <div class="section">
                <h2>Important Information</h2>
                <ul>
                    <li>Check-in time: {{ $property->check_in_time }}:00</li>
                    <li>Check-out time: {{ $property->check_out_time }}:00</li>
                    <li>Please bring a valid ID at check-in</li>
                    <li>For any changes or cancellations, please contact the property directly</li>
                </ul>
            </div>

            <!-- Call to Action -->
            <p style="text-align: center;">
                <a href="{{ url('/') }}" class="button">View Your Booking</a>
            </p>

            <!-- Closing -->
            <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
            <p>Best regards,<br>{{ $property->name }} Team</p>
        </div>

        <div class="footer">
            <p>This is an automated email. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $property->name }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

