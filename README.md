# Master's House

Master's House is a Laravel web application prototype for a countryside resort stay during WRC weekend. It presents a small selection of accommodation options and lets visitors send a booking request for the resort team to review.

The project is intentionally a request-based demo: it does not check live availability, confirm a reservation, or collect payment details.

## What the application does

- Shows three sample stays: Garden Cottage, Rally View Suite, and Family Lodge.
- Displays room descriptions, nightly prices in Kenyan shillings (KSh), and sample amenities.
- Provides a booking request form for a guest's contact details, stay type, arrival and departure dates, guest count, and optional notes.
- Validates the submitted details and stores valid requests in the `booking_requests` database table with a `pending` status.
- Shows a success message after a request is submitted and validation feedback when a submission needs correction.
- Adapts the landing page layout for smaller screens.

The room descriptions, prices, and WRC weekend date label are sample content in the application and should be confirmed before being used for real bookings.

## Technology

- PHP 8.3 or later
- Laravel 13
- SQLite by default, configurable through Laravel's database environment variables
- Composer for PHP dependencies
- Node.js and npm for the Vite asset pipeline
- Vite for frontend asset development; Tailwind CSS 4 is included in the frontend toolchain
- Pest for automated tests

## Getting started

### Requirements

Install PHP 8.3+, Composer, Node.js, and npm. The PHP installation must include the extensions required by Laravel and the configured database driver.

### Install dependencies and configure the application

From the project directory:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

If `Copy-Item` reports that `.env` already exists, keep your existing local configuration. The default configuration uses SQLite. Ensure the SQLite database file exists before running migrations:

```powershell
if (-not (Test-Path database\database.sqlite)) {
    New-Item -ItemType File database\database.sqlite | Out-Null
}
php artisan migrate
```

Install the JavaScript dependencies and compile the frontend assets:

```powershell
npm install
npm run build
```

Alternatively, after ensuring `database\database.sqlite` exists, the Composer setup script performs the initial application setup, including dependency installation, environment/key setup, migrations, and the frontend build:

```powershell
composer run setup
```

### Run the application

Start Laravel's development services with:

```powershell
composer run dev
```

The application is served locally by Laravel. Open the URL shown in the command output (typically `http://localhost:8000`). To work on frontend assets with Vite's hot reload in a separate terminal, use:

```powershell
npm run dev
```

## Booking request flow

The landing page is served by `GET /`. Visitors submit the form to `POST /bookings`. Laravel validates the request and writes it to `booking_requests`; accepted fields are:

| Field | Requirements |
| --- | --- |
| `full_name` | Required; up to 120 characters |
| `email` | Required; valid email address; up to 254 characters |
| `phone` | Optional; up to 32 characters |
| `room_name` | Required; one of the three displayed stay types |
| `arrival_date` | Required; today or a later date |
| `departure_date` | Required; later than the arrival date |
| `guests` | Required; integer from 1 through 8 |
| `notes` | Optional; up to 1,000 characters |

Successful requests are stored with a `pending` status. A team member must follow up with the guest to confirm availability. There is currently no staff dashboard or automated confirmation workflow.

## Project layout

- `routes/web.php` — landing page and booking request routes, sample room data, and request validation.
- `resources/views/rooms.blade.php` — resort landing page, room cards, and booking form.
- `database/migrations/` — database schema, including the booking request table.
- `app/Models/` — Eloquent models.
- `resources/css/` and `resources/js/` — frontend entry points managed by Vite.
- `tests/` — Pest unit and feature tests.

## Development and testing

Compile frontend assets for production:

```powershell
npm run build
```

Run the automated test suite:

```powershell
composer run test
```

The test environment uses an in-memory SQLite database. Add feature tests for booking validation and persistence when extending the booking flow.

## Current scope

This repository is a prototype and currently has no live inventory or availability lookup, user-facing booking management, email delivery, payment processing, or production-ready reservation confirmation. The room catalog is defined in the web route rather than maintained as database records.
