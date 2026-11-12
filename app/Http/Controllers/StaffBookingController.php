<?php

namespace App\Http\Controllers;

use App\Mail\BookingStatusUpdated;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StaffBookingController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['pending', 'confirmed', 'declined'])],
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        $search = trim($filters['q'] ?? '');
        $bookings = $this->filteredBookingsQuery($filters)
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('staff-bookings', [
            'bookings' => $bookings,
            'statusFilter' => $filters['status'] ?? '',
            'searchTerm' => $search,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['pending', 'confirmed', 'declined'])],
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        $bookings = $this->filteredBookingsQuery($filters)
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->cursor();

        return response()->streamDownload(function () use ($bookings) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, [
                'Booking ID', 'Guest name', 'Email', 'Phone', 'Room', 'Arrival date',
                'Departure date', 'Guests', 'Notes', 'Status', 'Request created at',
            ], ',', '"', '');

            foreach ($bookings as $booking) {
                $row = [
                    $booking->id,
                    $booking->full_name,
                    $booking->email,
                    $booking->phone,
                    $booking->room_name,
                    $booking->arrival_date,
                    $booking->departure_date,
                    $booking->guests,
                    $booking->notes,
                    $booking->status,
                    $booking->created_at,
                ];

                fputcsv($output, array_map(fn ($value) => $this->safeCsvValue($value), $row), ',', '"', '');
            }

            fclose($output);
        }, 'masters-house-bookings-' . now()->format('Y-m-d-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function filteredBookingsQuery(array $filters): Builder
    {
        $query = DB::table('booking_requests');

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $search = trim($filters['q'] ?? '');

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('full_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    private function safeCsvValue(mixed $value): string
    {
        $value = (string) ($value ?? '');

        return preg_match('/^[\s]*[=+\-@]/u', $value) === 1
            ? "'" . $value
            : $value;
    }

    public function calendar(Request $request): View
    {
        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $month = Carbon::createFromFormat('!Y-m', $validated['month'] ?? now()->format('Y-m'));
        $monthStart = $month->toDateString();
        $monthEnd = $month->copy()->endOfMonth()->toDateString();

        $bookings = DB::table('booking_requests')
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('arrival_date', '<=', $monthEnd)
            ->where('departure_date', '>=', $monthStart)
            ->orderBy('arrival_date')
            ->orderBy('full_name')
            ->get();

        $calendarDays = array_fill(0, $month->dayOfWeekIso - 1, null);

        for ($day = 1; $day <= $month->daysInMonth; $day++) {
            $date = $month->copy()->day($day);
            $dateString = $date->toDateString();
            $events = $bookings
                ->filter(function ($booking) use ($dateString) {
                    return $booking->arrival_date === $dateString
                        || $booking->departure_date === $dateString
                        || ($booking->arrival_date < $dateString && $booking->departure_date > $dateString);
                })
                ->map(function ($booking) use ($dateString) {
                    $kind = $booking->arrival_date === $dateString
                        ? 'Arrival'
                        : ($booking->departure_date === $dateString ? 'Departure' : 'In house');

                    return ['kind' => $kind, 'booking' => $booking];
                })
                ->values();

            $calendarDays[] = [
                'date' => $date,
                'events' => $events,
            ];
        }

        while (count($calendarDays) % 7 !== 0) {
            $calendarDays[] = null;
        }

        return view('staff-calendar', [
            'calendarDays' => $calendarDays,
            'month' => $month,
            'previousMonth' => $month->copy()->subMonthNoOverflow()->format('Y-m'),
            'nextMonth' => $month->copy()->addMonthNoOverflow()->format('Y-m'),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'declined'])],
        ]);

        $result = DB::transaction(function () use ($id, $validated) {
            $booking = DB::table('booking_requests')->where('id', $id)->first();
            abort_unless($booking, 404);

            if ($validated['status'] !== 'declined') {
                $overlap = DB::table('booking_requests')
                    ->where('room_name', $booking->room_name)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where('id', '<>', $id)
                    ->where('arrival_date', '<', $booking->departure_date)
                    ->where('departure_date', '>', $booking->arrival_date)
                    ->exists();

                if ($overlap) {
                    throw ValidationException::withMessages([
                        'status' => 'This request overlaps another pending or confirmed booking for the same room. Resolve the other request before accepting this one.',
                    ]);
                }
            }

            DB::table('booking_requests')
                ->where('id', $id)
                ->update(['status' => $validated['status'], 'updated_at' => now()]);

            $previousStatus = $booking->status;
            $booking->status = $validated['status'];

            return ['booking' => $booking, 'previousStatus' => $previousStatus];
        });

        if (
            $result['previousStatus'] === $validated['status'] ||
            ! in_array($validated['status'], ['confirmed', 'declined'], true)
        ) {
            return back()->with('status', 'Booking status updated.');
        }

        try {
            Mail::to($result['booking']->email)->send(new BookingStatusUpdated($result['booking']));
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('warning', 'Booking status was saved, but Laravel could not send the guest email. Check your mail settings.');
        }

        if (config('mail.default') === 'log') {
            return back()->with('status', 'Booking status saved. The email was written to the Laravel log because the mailer is set to log.');
        }

        return back()->with('status', 'Booking status saved and the guest email was sent.');
    }
}
