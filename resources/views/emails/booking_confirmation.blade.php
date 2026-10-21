<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #16a34a;">Booking Confirmed! 🏏</h1>
        <p>Hi,</p>
        <p>Your booking with <strong>PlayCricket</strong> has been successfully confirmed.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr style="background-color: #f3f4f6;">
                <td style="padding: 10px; border: 1px solid #ddd;"><strong>Booking ID</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ strtoupper($booking->id) }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><strong>Facility</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $booking->facility }}</td>
            </tr>
            <tr style="background-color: #f3f4f6;">
                <td style="padding: 10px; border: 1px solid #ddd;"><strong>Date</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $booking->date }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><strong>Time</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $booking->start_time }}</td>
            </tr>
            <tr style="background-color: #f3f4f6;">
                <td style="padding: 10px; border: 1px solid #ddd;"><strong>Lane</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $booking->lane ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><strong>Player Name</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $booking->player_name ?? 'N/A' }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">
            <a href="{{ url('/') }}" style="background-color: #16a34a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View Booking</a>
        </p>

        <p style="margin-top: 30px; font-size: 12px; color: #777;">
            If you did not make this booking, please contact us immediately.
        </p>
    </div>
</body>
</html>
