# Free demo deployment

This project is set up for a Render free web service with a Neon PostgreSQL database.

## 1. Create the database

Create a Neon project in Frankfurt, then copy its **pooled** connection string. Keep the `sslmode=require` part of the URL. You will enter it as `DB_URL` in Render; do not put it in this repository.

## 2. Deploy the Blueprint

In Render, choose **New → Blueprint**, connect the GitHub repository, and select the branch containing `render.yaml`. During setup, provide:

- `DB_URL`: the Neon pooled connection string
- `STAFF_EMAIL`: the email address for the staff dashboard login
- `STAFF_PASSWORD`: a strong, unique password for that login

Render generates `APP_KEY` and sets `APP_URL` from the service's assigned URL. On startup, Laravel runs migrations. After the first successful deploy, Render runs the one-time database seed to create the staff login.

The staff sign-in page is `/staff/login`.

## Free-plan notes

- Render sleeps after 15 minutes without traffic; the first request after sleep can take about a minute.
- The web service filesystem is temporary, so booking data is stored in Neon rather than a local SQLite file.
- Render free blocks SMTP ports used by Gmail. The hosted app uses Laravel's `log` mailer, which records the message in application logs instead of delivering it. The local Gmail SMTP settings remain in your ignored `.env` file.
- Free Render Postgres expires after 30 days, which is why this setup uses Neon for the database.
