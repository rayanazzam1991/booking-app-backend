# Appointment Booking API

A minimal Laravel 12 API for booking appointments with health professionals. The API validates requests, prevents double-bookings for the same professional and time window, persists appointments, and logs a simulated confirmation email instead of sending externally.

## Prerequisites
- PHP 8.2+
- Composer
- SQLite (recommended for quick start) or another database supported by Laravel

## Setup
1. Install PHP dependencies:
   ```bash
   composer install
   ```
2. Create an environment file and application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Configure the database (SQLite example):
   ```bash
   touch database/database.sqlite
   # In .env
   DB_CONNECTION=sqlite
   DB_DATABASE=./database/database.sqlite
   QUEUE_CONNECTION=sync
   MAIL_MAILER=log
   ```
4. Run database migrations:
   ```bash
   php artisan migrate
   ```

## Running the API
Start the HTTP server:
```bash
php artisan serve
```
The booking endpoint will be available at `http://localhost:8000/api/appointment`.

## Booking an Appointment
Send a POST request with JSON including a service, health professional, scheduled date-time, and customer email. The date-time must follow `Y-m-d H:i:s`.

Example `curl` request:
```bash
curl -X POST http://localhost:8000/api/appointment \
  -H "Content-Type: application/json" \
  -d '{
    "service_id": 1,
    "health_professional_id": 1,
    "date": "2025-01-15 10:30:00",
    "customer_email": "patient@example.com"
  }'
```

A successful response returns HTTP 201 with the saved appointment payload and message:
```json
{
  "success": true,
  "message": "Appointment created successfully",
  "data": {
    "id": 1,
    "service_id": 1,
    "health_professional_id": 1,
    "scheduled_at": "2025-01-15 10:30:00",
    "customer_email": "patient@example.com",
    "created_at": "...",
    "updated_at": "..."
  },
  "pagination": null
}
```

## Confirmation Notification
Confirmation emails are **simulated only**. Each booking dispatches a queued job that logs a structured message with the prefix `appointment_confirmation_prepared`. Check the Laravel log to verify the notification:
```bash
cat storage/logs/laravel.log | grep appointment_confirmation_prepared
```
The log entry includes the appointment ID, recipient email, service, professional, and scheduled time. No external email is sent.

## Tests
Run the automated test suite (uses an in-memory SQLite database):
```bash
php artisan test
```
