# Appointment Booking API (Laravel)

This project is a minimal appointment-booking backend built with Laravel. It exposes an unauthenticated API for clients to browse services/health professionals and book a timeslot while logging a simulated confirmation email. The API meets the provided backend brief:

> Build a minimal API to book an appointment. The endpoint must accept a `service_id`, `health_professional_id`, `date`, and customer `email`; persist the booking; and trigger a confirmation notification without actually sending an external email.

## Table of contents
- [Requirements](#requirements)
- [Local setup with Composer](#local-setup-with-composer)
- [Running with Laravel Sail (Docker)](#running-with-laravel-sail-docker)
- [Environment and database](#environment-and-database)
- [Queued mail + log verification](#queued-mail--log-verification)
- [API usage](#api-usage)
- [Testing](#testing)
- [Project structure highlights](#project-structure-highlights)

## Requirements
- PHP 8.2+
- Composer
- MySQL 8 (or run the included Docker services via Sail)

## Local setup with Composer
1. **Install PHP dependencies**
   ```bash
   composer install
   ```
2. **Create your environment file**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. **Configure your database credentials** in `.env` (`DB_*` variables) and create the database.
4. **Run migrations and seeders** (seeds services and demo health professionals with pivot data):
   ```bash
   php artisan migrate --seed
   ```
5. **Run the app locally**:
   ```bash
   php artisan serve --port=8000
   php artisan queue:work   # in a separate terminal to process confirmation jobs
   ```

## Running with Laravel Sail (Docker)
Laravel Sail is preconfigured via `compose.yaml` for PHP, MySQL, and Redis.

1. **Copy environment and set Sail values** (e.g., `DB_PASSWORD`, `APP_PORT`, `FORWARD_DB_PORT`).
2. **Install dependencies inside the container** (first run only):
   ```bash
   composer install
   ```
3. **Start the stack**:
   ```bash
   ./vendor/bin/sail up -d
   ```
4. **Run migrations and seeders** in the container:
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```
5. **Queue worker** (needed for confirmation emails):
   ```bash
   ./vendor/bin/sail artisan queue:work
   ```

## Environment and database
- `MAIL_MAILER` defaults to `log`, so outgoing mail is written to `storage/logs/laravel.log`.
- `QUEUE_CONNECTION` defaults to `database`; migrations create the queue tables automatically.
- Seed data includes:
  - Four base services with durations/prices.
  - Three health professionals linked to one or more services with pivot overrides for price/duration/notes.

## Queued mail + log verification
Confirmation notifications are dispatched to the queue (`SendAppointmentConfirmationEmailJob`) and sent through the log mailer. After booking:
- Ensure a queue worker is running.
- Inspect `storage/logs/laravel.log` for entries like `"Simulated email for appointment: <id>"` including recipient, service, professional, and schedule details.

## API usage
### Create an appointment
`POST /api/appointment`

Payload fields:
- `service_id` (required, must exist)
- `health_professional_id` (required, must exist)
- `customer_email` (required, valid email)
- `date` (required, ISO date/time string; must be today or later)

Example `curl` (adjust host/port as needed):
```bash
curl -X POST "http://127.0.0.1:8000/api/appointment" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "service_id": 1,
    "health_professional_id": 2,
    "customer_email": "test@test.com",
    "date": "2025-12-17T12:42"
  }'
```
A successful response returns `201` with the appointment payload and message `"Appointment created successfully"`. If the timeslot for that professional overlaps with an existing appointment, the API raises a conflict-style error.

### Browse supporting data
- `GET /api/services` — list services.
- `GET /api/services/{service}/health_professionals` — professionals for a service.
- `GET /api/health_professionals` — list professionals.

## Testing
- **Application tests via Composer script (uses Sail test runner):**
  ```bash
  composer test
  ```
  This runs `./vendor/bin/sail artisan test --env=testing`; ensure the Sail containers are up.
- **Direct Laravel test runner (non-Docker):**
  ```bash
  php artisan test --env=testing
  ```
- **Test Api endpoint** `httpRequests/bookAppointment.http` — ready-to-use HTTP request example for local testing.

## Project structure highlights
- `routes/api.php` — API endpoints for services, professionals, and appointment booking.
- `app/Http/Requests/CreateAppointmentRequest.php` — validation rules for booking.
- `app/Services/AppointmentService.php` — availability check, persistence, and notification dispatch.
- `app/Jobs/SendAppointmentConfirmationEmailJob.php` — logs + mails the confirmation.
- `database/seeders/HealthProfessionalServiceSeeder.php` — seeds sample services and professionals.
- `httpRequests/bookAppointment.http` — ready-to-use HTTP request example for local testing.
