<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/', function () {
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

Route::post('/bookings', function (Request $request) {
    $booking = $request->validate([
        'full_name' => ['required', 'string', 'max:120'],
        'email' => ['required', 'email', 'max:254'],
        'phone' => ['nullable', 'string', 'max:32'],
        'room_name' => ['required', Rule::in(['Garden Cottage', 'Rally View Suite', 'Family Lodge'])],
        'arrival_date' => ['required', 'date', 'after_or_equal:today'],
        'departure_date' => ['required', 'date', 'after:arrival_date'],
        'guests' => ['required', 'integer', 'min:1', 'max:8'],
        'notes' => ['nullable', 'string', 'max:1000'],
    ]);

    DB::table('booking_requests')->insert([
        ...$booking,
        'status' => 'pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return to_route('home')->with('booking_success', 'Thanks, ' . $booking['full_name'] . '. Your request has been received. The resort team will follow up to confirm availability.');
})->name('bookings.store');
