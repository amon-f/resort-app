<!doctype html>
<html lang="en">
<body style="margin:0;padding:32px;background:#f5f3ed;color:#25332b;font:16px/1.6 Arial,sans-serif">
    <main style="max-width:600px;margin:0 auto;padding:32px;background:#fffefa;border:1px solid #dedfd5;border-radius:8px">
        <p style="margin-top:0;color:#315b46;font-size:13px;font-weight:bold;letter-spacing:1px;text-transform:uppercase">Master's House</p>
        <p>Hello {{ $booking->full_name }},</p>

        @if ($booking->status === 'confirmed')
            <p>Your booking request has been <strong>confirmed</strong>. We look forward to welcoming you.</p>
        @else
            <p>We’re sorry, but we’re unable to confirm your booking request for these dates. Please contact Master's House if you’d like to discuss other dates or rooms.</p>
        @endif

        <div style="margin:22px 0;padding:16px;border-radius:6px;background:#f5f3ed">
            <strong>{{ $booking->room_name }}</strong><br>
            Arrival: {{ \Illuminate\Support\Carbon::parse($booking->arrival_date)->format('M j, Y') }}<br>
            Departure: {{ \Illuminate\Support\Carbon::parse($booking->departure_date)->format('M j, Y') }}<br>
            Guests: {{ $booking->guests }}
        </div>

        <p style="margin-bottom:0;color:#68736a;font-size:13px">This message is about a booking request you sent to Master's House.</p>
    </main>
</body>
</html>
