<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StaffBookingController extends Controller
{
    public function index(): View
    {
        $bookings = DB::table('booking_requests')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('staff-bookings', compact('bookings'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'declined'])],
        ]);

        DB::transaction(function () use ($id, $validated) {
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
        });

        return back()->with('status', 'Booking status updated.');
    }
}
