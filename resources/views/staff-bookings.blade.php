<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking requests | Master's House</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f5f3ed; color: #26382e; font: 15px/1.5 Arial, sans-serif; }
        header { display: flex; justify-content: space-between; align-items: center; gap: 20px; padding: 20px max(24px, calc((100vw - 1200px)/2)); background: #fffefa; border-bottom: 1px solid #e3e0d7; }
        .brand { font: 24px Georgia, serif; }
        .wrap { max-width: 1200px; margin: 42px auto; padding: 0 24px; }
        h1 { margin: 0; font: 40px Georgia, serif; }
        .sub { color: #6e756e; margin: 8px 0 24px; }
        .notice { padding: 12px 16px; margin-bottom: 18px; border-radius: 8px; background: #e4efe4; color: #28533c; }
        .error-notice { padding: 12px 16px; margin-bottom: 18px; border-radius: 8px; background: #fbefec; color: #8b3d32; }
        .warning-notice { padding: 12px 16px; margin-bottom: 18px; border-radius: 8px; background: #fff2d8; color: #765313; }
        .filters { display: flex; align-items: end; flex-wrap: wrap; gap: 12px; margin: 0 0 20px; padding: 16px; border: 1px solid #e3e0d7; border-radius: 10px; background: #fffefa; }
        .filters label { display: grid; gap: 5px; color: #667068; font-size: 12px; }
        .filters select, .filters input { min-width: 190px; padding: 9px 10px; border: 1px solid #d6d8cf; border-radius: 7px; background: white; font: inherit; }
        .filters button { margin: 0; padding: 10px 14px; }
        .filters .export-button { border: 1px solid #37634d; background: #fffefa; color: #37634d; }
        .clear-filter { padding: 9px 4px; color: #37634d; font-size: 13px; }
        .table-wrap { overflow-x: auto; background: #fffefa; border: 1px solid #e3e0d7; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        th, td { padding: 14px 16px; text-align: left; vertical-align: top; border-bottom: 1px solid #eeece5; }
        th { color: #667068; font-size: 12px; letter-spacing: .06em; text-transform: uppercase; }
        td small { display: block; color: #747b74; margin-top: 4px; }
        select, button { padding: 8px 10px; border: 1px solid #d6d8cf; border-radius: 7px; background: white; font: inherit; }
        button { border: 0; background: #37634d; color: white; cursor: pointer; margin-top: 7px; }
        .empty { padding: 42px; text-align: center; color: #6e756e; }
        .pagination { margin-top: 20px; }
        .pagination a, .pagination span { display: inline-block; margin-right: 8px; padding: 7px 10px; color: #37634d; }
        .logout { padding: 9px 13px; border: 0; border-radius: 7px; background: #37634d; color: white; cursor: pointer; font: inherit; }
        @media (max-width: 600px) { header { align-items: flex-start; } h1 { font-size: 32px; } .wrap { margin-top: 28px; } }
    </style>
</head>
<body>
<header>
    <div class="brand">Master's House <span style="font: 14px Arial,sans-serif;color:#6e756e">· Staff</span></div>
    <form method="POST" action="{{ route('staff.logout') }}">
        @csrf
        <button class="logout" type="submit">Sign out</button>
    </form>
</header>
<main class="wrap">
    <h1>Booking requests</h1>
    <p class="sub">Review guest details and update each request as you follow up. <a href="{{ route('staff.calendar') }}" style="color:#37634d;font-weight:700">View calendar</a></p>
    @if (session('status'))
        <div class="notice" role="status">{{ session('status') }}</div>
    @endif
    @if (session('warning'))
        <div class="warning-notice" role="alert">{{ session('warning') }}</div>
    @endif
    @if ($errors->any())
        <div class="error-notice" role="alert">{{ $errors->first() }}</div>
    @endif
    <form class="filters" method="GET" action="{{ route('staff.bookings.index') }}">
        <label for="status-filter">Status
            <select id="status-filter" name="status">
                <option value="">All statuses</option>
                @foreach (['pending' => 'Pending', 'confirmed' => 'Confirmed', 'declined' => 'Declined'] as $value => $label)
                    <option value="{{ $value }}" @selected($statusFilter === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label for="booking-search">Guest name or email
            <input id="booking-search" type="search" name="q" value="{{ $searchTerm }}" maxlength="120" placeholder="Search bookings">
        </label>
        <button type="submit">Apply filters</button>
        <button class="export-button" type="submit" formaction="{{ route('staff.bookings.export') }}">Download CSV</button>
        @if ($statusFilter !== '' || $searchTerm !== '')
            <a class="clear-filter" href="{{ route('staff.bookings.index') }}">Clear filters</a>
        @endif
    </form>
    <div class="table-wrap">
        @if ($bookings->isEmpty())
            <div class="empty">{{ $statusFilter !== '' || $searchTerm !== '' ? 'No booking requests match those filters.' : 'There are no booking requests yet.' }}</div>
        @else
            <table>
                <thead><tr><th>Guest</th><th>Stay</th><th>Dates</th><th>Guests</th><th>Notes</th><th>Status</th></tr></thead>
                <tbody>
                @foreach ($bookings as $booking)
                    <tr>
                        <td><strong>{{ $booking->full_name }}</strong><small><a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a></small><small>{{ $booking->phone ?: 'No phone provided' }}</small></td>
                        <td>{{ $booking->room_name }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($booking->arrival_date)->format('M j, Y') }}<small>to {{ \Illuminate\Support\Carbon::parse($booking->departure_date)->format('M j, Y') }}</small></td>
                        <td>{{ $booking->guests }}</td>
                        <td>{{ $booking->notes ?: '—' }}</td>
                        <td>
                            <form method="POST" action="{{ route('staff.bookings.status', $booking->id) }}">
                                @csrf
                                @method('PATCH')
                                <select name="status" aria-label="Status for {{ $booking->full_name }}">
                                    @foreach (['pending' => 'Pending', 'confirmed' => 'Confirmed', 'declined' => 'Declined'] as $value => $label)
                                        <option value="{{ $value }}" @selected($booking->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="pagination">{{ $bookings->links() }}</div>
</main>
</body>
</html>
