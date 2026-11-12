<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffBookingController;

$roomCapacities = [
    'Garden Cottage' => 2,
    'Rally View Suite' => 2,
    'Family Lodge' => 4,
];

Route::get('/', function (Request $request) {
    $rooms = [
        [
            'name' => 'Garden Cottage',
            'type' => 'Queen bed · Garden view',
            'price' => 14500,
            'description' => 'A quiet, leafy hideaway with a private veranda and room to unwind after a day on the rally stages.',
            'tone' => 'sage',
            'icon' => '✳',
            'label' => 'A relaxed stay',
        ],
        [
            'name' => 'Rally View Suite',
            'type' => 'King bed · Hillside view',
            'price' => 22000,
            'description' => 'A bright hillside suite with wide views, a generous sitting area, and space to settle in for the weekend.',
            'tone' => 'clay',
            'icon' => '⌂',
            'label' => 'Guest favourite',
        ],
        [
            'name' => 'Family Lodge',
            'type' => 'Two bedrooms · Up to 4 guests',
            'price' => 28500,
            'description' => 'A roomy lodge for friends or family, with a shared lounge and an easy path to the resort gardens.',
            'tone' => 'gold',
            'icon' => '⌁',
            'label' => 'Room for everyone',
        ],
    ];

    return view('rooms', compact('rooms'));
})->name('home');

Route::get('/availability', function (Request $request) use ($roomCapacities) {
    $dates = $request->validate([
        'arrival_date' => ['required', 'date', 'after_or_equal:today'],
        'departure_date' => ['required', 'date', 'after:arrival_date'],
        'guests' => ['required', 'integer', 'min:1', 'max:4'],
    ]);

    $rooms = [
        ['name' => 'Garden Cottage', 'type' => 'Queen bed · Garden view', 'price' => 14500],
        ['name' => 'Rally View Suite', 'type' => 'King bed · Hillside view', 'price' => 22000],
        ['name' => 'Family Lodge', 'type' => 'Two bedrooms · Up to 4 guests', 'price' => 28500],
    ];

    foreach ($rooms as &$room) {
        $room['capacity'] = $roomCapacities[$room['name']];
        $room['fits_guests'] = $dates['guests'] <= $room['capacity'];
        $room['dates_available'] = ! DB::table('booking_requests')
            ->where('room_name', $room['name'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('arrival_date', '<', $dates['departure_date'])
            ->where('departure_date', '>', $dates['arrival_date'])
            ->exists();
        $room['available'] = $room['fits_guests'] && $room['dates_available'];
    }
    unset($room);

    return view('availability', [
        'rooms' => $rooms,
        'arrivalDate' => $dates['arrival_date'],
        'departureDate' => $dates['departure_date'],
        'guestCount' => $dates['guests'],
    ]);
})->name('availability.search');

Route::post('/bookings', function (Request $request) use ($roomCapacities) {
    $booking = $request->validate([
        'full_name' => ['required', 'string', 'max:120'],
        'email' => ['required', 'email', 'max:254'],
        'phone' => ['nullable', 'string', 'max:32'],
        'room_name' => ['required', Rule::in(['Garden Cottage', 'Rally View Suite', 'Family Lodge'])],
        'arrival_date' => ['required', 'date', 'after_or_equal:today'],
        'departure_date' => ['required', 'date', 'after:arrival_date'],
        'guests' => ['required', 'integer', 'min:1', 'max:4'],
        'notes' => ['nullable', 'string', 'max:1000'],
    ]);

    $roomCapacity = $roomCapacities[$booking['room_name']];

    if ($booking['guests'] > $roomCapacity) {
        throw ValidationException::withMessages([
            'guests' => $booking['room_name'] . ' accommodates up to ' . $roomCapacity . ' guests.',
        ]);
    }

    DB::transaction(function () use ($booking) {
        $overlap = DB::table('booking_requests')
            ->where('room_name', $booking['room_name'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('arrival_date', '<', $booking['departure_date'])
            ->where('departure_date', '>', $booking['arrival_date'])
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'arrival_date' => 'Those dates overlap an existing request for this room. Please choose different dates or another room.',
            ]);
        }

        DB::table('booking_requests')->insert([
            ...$booking,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    });

    return to_route('home')->with('booking_success', 'Thanks, ' . $booking['full_name'] . '. Your request has been received. The resort team will follow up to confirm availability.');
})->name('bookings.store');

Route::get('/staff/login', [StaffAuthController::class, 'create'])->name('staff.login');
Route::post('/staff/login', [StaffAuthController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('staff.login.store');

// Laravel's auth middleware uses the route named "login" when redirecting guests.
Route::get('/login', fn () => to_route('staff.login'))->name('login');

Route::middleware('auth')->prefix('staff')->name('staff.')->group(function () {
    Route::get('/bookings', [StaffBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/export', [StaffBookingController::class, 'export'])->name('bookings.export');
    Route::get('/calendar', [StaffBookingController::class, 'calendar'])->name('calendar');
    Route::patch('/bookings/{id}/status', [StaffBookingController::class, 'update'])
        ->whereNumber('id')
        ->name('bookings.status');
    Route::post('/logout', [StaffAuthController::class, 'destroy'])->name('logout');
});
