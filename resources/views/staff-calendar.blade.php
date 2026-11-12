<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking calendar | Master's House</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f5f3ed; color: #26382e; font: 14px/1.45 Arial, sans-serif; }
        header { display: flex; justify-content: space-between; align-items: center; gap: 20px; padding: 20px max(24px, calc((100vw - 1200px)/2)); background: #fffefa; border-bottom: 1px solid #e3e0d7; }
        .brand { font: 24px Georgia, serif; }
        .header-actions { display: flex; align-items: center; gap: 15px; }
        a { color: #37634d; text-decoration: none; }
        .header-link { font-size: 13px; font-weight: 700; }
        button { padding: 9px 13px; border: 0; border-radius: 7px; background: #37634d; color: white; cursor: pointer; font: inherit; }
        .wrap { max-width: 1200px; margin: 38px auto; padding: 0 24px; }
        .title-row { display: flex; justify-content: space-between; align-items: end; gap: 20px; margin-bottom: 22px; }
        h1 { margin: 0; font: 40px Georgia, serif; }
        .sub { margin: 8px 0 0; color: #6e756e; }
        .month-nav { display: flex; align-items: center; gap: 12px; }
        .month-nav a { padding: 8px 11px; border: 1px solid #d6d8cf; border-radius: 7px; background: #fffefa; }
        .month-label { min-width: 145px; text-align: center; font-weight: 700; }
        .legend { display: flex; gap: 16px; margin-bottom: 14px; color: #59645c; font-size: 12px; }
        .dot { display: inline-block; width: 9px; height: 9px; margin-right: 5px; border-radius: 50%; }
        .dot.confirmed { background: #37634d; } .dot.pending { background: #c18a34; }
        .calendar-scroll { overflow-x: auto; border: 1px solid #e3e0d7; border-radius: 10px; background: #fffefa; }
        .calendar { min-width: 910px; }
        .weekdays, .days { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); }
        .weekday { padding: 11px; border-bottom: 1px solid #e3e0d7; color: #667068; font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; }
        .day { min-height: 128px; padding: 8px; border-right: 1px solid #eeece5; border-bottom: 1px solid #eeece5; }
        .day:nth-child(7n) { border-right: 0; }
        .day-number { display: inline-grid; place-items: center; width: 27px; height: 27px; margin-bottom: 5px; border-radius: 50%; font-weight: 700; }
        .today .day-number { background: #37634d; color: white; }
        .event { margin: 4px 0; padding: 6px; border-left: 3px solid #37634d; border-radius: 4px; background: #e7efe8; font-size: 10px; line-height: 1.35; overflow-wrap: anywhere; }
        .event.pending { border-color: #c18a34; background: #fff3dd; }
        .event strong { display: block; margin-bottom: 2px; }
        .empty { margin: 14px 0; padding: 12px 14px; border-radius: 7px; background: #fffefa; color: #6e756e; }
        @media (max-width: 650px) { .title-row { align-items: flex-start; flex-direction: column; } h1 { font-size: 34px; } header { align-items: flex-start; } .header-actions { flex-direction: column; align-items: flex-end; } }
    </style>
</head>
<body>
<header>
    <div class="brand">Master's House <span style="font:14px Arial,sans-serif;color:#6e756e">· Staff</span></div>
    <div class="header-actions">
        <a class="header-link" href="{{ route('staff.bookings.index') }}">Booking list</a>
        <form method="POST" action="{{ route('staff.logout') }}">
            @csrf
            <button type="submit">Sign out</button>
        </form>
    </div>
</header>
<main class="wrap">
    <div class="title-row">
        <div>
            <h1>Booking calendar</h1>
            <p class="sub">Pending and confirmed stays, including arrivals and departures.</p>
        </div>
        <nav class="month-nav" aria-label="Calendar month">
            <a href="{{ route('staff.calendar', ['month' => $previousMonth]) }}" aria-label="Previous month">‹</a>
            <span class="month-label">{{ $month->format('F Y') }}</span>
            <a href="{{ route('staff.calendar', ['month' => $nextMonth]) }}" aria-label="Next month">›</a>
        </nav>
    </div>
    <div class="legend">
        <span><span class="dot confirmed"></span>Confirmed</span>
        <span><span class="dot pending"></span>Pending</span>
    </div>
    @if (collect($calendarDays)->every(fn ($day) => $day === null || $day['events']->isEmpty()))
        <div class="empty">No pending or confirmed stays for this month.</div>
    @endif
    <div class="calendar-scroll">
        <div class="calendar">
            <div class="weekdays">
                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $weekday)
                    <div class="weekday">{{ $weekday }}</div>
                @endforeach
            </div>
            <div class="days">
                @foreach ($calendarDays as $day)
                    @if ($day === null)
                        <div class="day" aria-hidden="true"></div>
                    @else
                        <div class="day {{ $day['date']->isToday() ? 'today' : '' }}">
                            <div class="day-number">{{ $day['date']->day }}</div>
                            @foreach ($day['events'] as $event)
                                <div class="event {{ $event['booking']->status }}">
                                    <strong>{{ $event['kind'] }} · {{ $event['booking']->full_name }}</strong>
                                    {{ $event['booking']->room_name }} · {{ $event['booking']->guests }} {{ \Illuminate\Support\Str::plural('guest', $event['booking']->guests) }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</main>
</body>
</html>
