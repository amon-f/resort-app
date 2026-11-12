# Demo links and local setup

This guide is separate from the project overview in `README.md`. It covers the public demo URLs and how to start the app on another Windows 11 computer.

## Demo links

Render assigns the public hostname when the web service is created. Replace `<your-render-service>` with that hostname:

- Guest booking page: `https://<your-render-service>.onrender.com/`
- Staff sign-in: `https://<your-render-service>.onrender.com/staff/login`
- Staff bookings: `https://<your-render-service>.onrender.com/staff/bookings` (sign in first)
- Staff calendar: `https://<your-render-service>.onrender.com/staff/calendar` (sign in first)

The live hostname is not known until Render creates the service. Copy the actual URL from the Render dashboard and replace the placeholder above before sharing these links. The staff login email and password are the values entered as `STAFF_EMAIL` and `STAFF_PASSWORD` during the initial Render Blueprint setup. Do not put those credentials in this README.

## Run locally on Windows 11

### Requirements

- Git
- PHP 8.3 or later, with SQLite/PDO SQLite enabled
- Composer
- Node.js 22 or later and npm

### Get the project and install dependencies

Open PowerShell and run:

```powershell
git clone https://github.com/amon-f/resort-app.git
cd resort-app
composer install
npm ci
```

If the repository URL changes, use the current clone URL from its GitHub page.

### Configure SQLite and the app key

Create a local `.env` from the example, then generate an application key and create the SQLite database file:

```powershell
if (-not (Test-Path .env)) {
    Copy-Item .env.example .env
}
php artisan key:generate
if (-not (Test-Path database\database.sqlite)) {
    New-Item -ItemType File database\database.sqlite | Out-Null
}
```

The example environment uses SQLite and Laravel's `log` mailer. Keep `.env` local; it contains machine-specific settings and must not be committed.

### Create the tables and sample staff login

```powershell
php artisan migrate
php artisan db:seed
```

The local seeder creates the demo staff account:

- Email: `test@example.com`
- Password: `password`

Use this account only for local development. The hosted Render staff account uses the separate credentials configured in Render.

### Build assets and start Laravel

```powershell
npm run build
composer run dev
```

Open the local URL printed by the command, usually `http://localhost:8000/`. The staff sign-in page is `http://localhost:8000/staff/login`.

For frontend hot reload while editing CSS or JavaScript, open another PowerShell window in the project folder and run:

```powershell
npm run dev
```

## Notes

- The demo accepts booking requests; it does not take payment or automatically confirm availability.
- Locally, the default `log` mailer records messages in Laravel's log instead of delivering them. Render's free web services also block Gmail's SMTP ports, so the hosted demo uses the log mailer.
- Render's free service may sleep after inactivity. The first visit after it sleeps can take about a minute.
