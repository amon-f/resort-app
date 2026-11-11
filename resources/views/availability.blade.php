<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f3ed">
    <title>Availability | Master's House</title>
    <style>
        :root { --ink: #25332b; --muted: #68736a; --paper: #f5f3ed; --line: #dedfd5; --green: #315b46; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: inherit; text-decoration: none; }
        .wrap { width: min(1040px, calc(100% - 40px)); margin: auto; }
        header { height: 76px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); }
        .brand { font: 22px Georgia, serif; }
        .back { color: var(--green); font-size: 13px; font-weight: 700; }
        main { padding: 54px 0 70px; }
        .eyebrow { color: var(--green); font-size: 11px; font-weight: 750; letter-spacing: 1.5px; text-transform: uppercase; }
        h1 { margin: 10px 0; font: 400 clamp(36px, 5vw, 54px)/1.08 Georgia, serif; }
        .summary { margin: 0; color: var(--muted); }
        .search { display: grid; grid-template-columns: 1fr 1fr auto; align-items: end; gap: 14px; margin: 28px 0 36px; padding: 20px; border: 1px solid var(--line); border-radius: 7px; background: #fffefa; }
        label { display: block; color: #46554a; font-size: 12px; font-weight: 650; }
        input { display: block; width: 100%; margin-top: 7px; padding: 11px; border: 1px solid #cfd4c9; border-radius: 4px; background: white; font: inherit; }
        button, .book { display: inline-block; padding: 12px 16px; border: 0; border-radius: 4px; background: var(--green); color: white; font: inherit; font-weight: 700; cursor: pointer; }
        .rooms { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        article { padding: 20px; border: 1px solid var(--line); border-radius: 7px; background: #fffefa; }
        .status { display: inline-block; padding: 6px 9px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .available { background: #e2eee2; color: #315b46; }
        .unavailable { background: #efeee9; color: #777b74; }
        h2 { margin: 17px 0 4px; font: 400 25px Georgia, serif; }
        .type { color: var(--muted); font-size: 12px; }
        .price { margin: 18px 0 20px; font-weight: 750; }
        .price small { color: var(--muted); font-size: 11px; font-weight: 400; }
        .unavailable-copy { color: var(--muted); font-size: 12px; }
        .note { margin-top: 24px; color: var(--muted); font-size: 12px; line-height: 1.6; }
        footer { border-top: 1px solid var(--line); padding: 20px 0 25px; color: #737b73; font-size: 11px; }
        @media (max-width: 760px) { .rooms { grid-template-columns: 1fr; } .search { grid-template-columns: 1fr; } main { padding-top: 38px; } }
    </style>
</head>
<body>
<header class="wrap">
    <a class="brand" href="{{ route('home') }}">Master's House</a>
    <a class="back" href="{{ route('home') }}">Back to the resort</a>
</header>
<main class="wrap">
    <div class="eyebrow">Your stay</div>
    <h1>Rooms for your dates</h1>
    <p class="summary">{{ \Illuminate\Support\Carbon::parse($arrivalDate)->format('M j, Y') }} – {{ \Illuminate\Support\Carbon::parse($departureDate)->format('M j, Y') }}</p>

    <form class="search" action="{{ route('availability.search') }}" method="GET">
        <label>Arrival date
            <input type="date" name="arrival_date" min="{{ now()->toDateString() }}" value="{{ $arrivalDate }}" required>
        </label>
        <label>Departure date
            <input type="date" name="departure_date" value="{{ $departureDate }}" required>
        </label>
        <button type="submit">Search again</button>
    </form>

    <section class="rooms" aria-label="Room availability">
        @foreach ($rooms as $room)
            <article>
                @if ($room['available'])
                    <span class="status available">Available</span>
                @else
                    <span class="status unavailable">Unavailable for these dates</span>
                @endif
                <h2>{{ $room['name'] }}</h2>
                <div class="type">{{ $room['type'] }}</div>
                <div class="price">KSh {{ number_format($room['price']) }} <small>/ night</small></div>
                @if ($room['available'])
                    <a class="book" href="{{ route('home', ['room_name' => $room['name'], 'arrival_date' => $arrivalDate, 'departure_date' => $departureDate]) }}#contact">Request this stay</a>
                @else
                    <div class="unavailable-copy">Try another room or change your dates.</div>
                @endif
            </article>
        @endforeach
    </section>
    <p class="note">Availability is based on pending and confirmed requests at the time of your search. The resort team will follow up to confirm your booking. No payment is taken here.</p>
</main>
<footer><div class="wrap">Master's House · A countryside stay for the WRC weekend · Sample prototype</div></footer>
</body>
</html>
