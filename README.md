# VPT Traffic Violation Reporting System

Laravel application for reporting, reviewing, and tracking traffic violations. The project was rebuilt from a legacy PHP/MySQL codebase into a cleaner Laravel structure with migrations, Eloquent models, request validation, API controllers, and AWS S3-ready evidence storage.

## Tech Stack

- PHP 8.2
- Laravel 12
- MySQL
- Laravel Storage + AWS S3
- Laravel Sanctum
- Blade, Vite
- PHPUnit

## Main Features

- Submit traffic violation reports with optional image evidence.
- Register, login, logout, and fetch the current API user.
- Store evidence images on AWS S3 through Laravel Storage.
- Review reports with clear statuses: `pending`, `verified`, `rejected`, `resolved`.
- Manage product catalog, news articles, and customer orders.
- Track fine amounts and reviewer metadata.
- Dashboard API for report totals, status breakdown, and top violation locations.
- Optimized relational database using Laravel migrations and indexes.

## Architecture Notes

- Legacy source is preserved in `legacy-php/` for reference only.
- New application code lives in standard Laravel directories:
  - `app/Models`
  - `app/Http/Controllers`
  - `app/Http/Requests`
  - `app/Services`
  - `database/migrations`
  - `routes/api.php`
- Upload logic is isolated in `App\Services\MediaStorage`.
- Validation is handled by Form Request classes, not inside controllers.
- API authentication uses Laravel Sanctum personal access tokens.
- Admin-only actions are protected by role middleware.
- Passwords use Laravel hashing instead of plain text.
- Dates use timestamp columns instead of varchar fields.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Default local URL:

```bash
http://127.0.0.1:8000
```

Demo accounts after seeding:

```text
admin@vpt.local / password
citizen@vpt.local / password
```

## AWS S3

Configure these values in `.env`:

```env
FILESYSTEM_CLOUD=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=
AWS_URL=
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

Evidence images are uploaded via:

```php
Storage::disk('s3')
```

The database stores only the S3 object path and public URL.

Media upload behavior:

- Report evidence is stored under `evidence/`.
- Product images are stored under `products/`.
- News images are stored under `news/`.
- If database write fails after upload, the newly uploaded file is deleted from the configured cloud disk.
- Automated tests use `Storage::fake('s3')`; real AWS upload requires valid `.env` credentials.

## API

```http
GET /api/reports
GET /api/reports/{id}
POST /api/reports
PATCH /api/reports/{id}/status
GET /api/dashboard
POST /api/auth/register
POST /api/auth/login
GET /api/auth/me
POST /api/auth/logout
GET /api/categories
POST /api/categories
GET /api/products
POST /api/products
GET /api/news-articles
POST /api/news-articles
GET /api/orders
POST /api/orders
```

Admin-only endpoint:

```http
PATCH /api/reports/{id}/status
Authorization: Bearer <admin-token>
```

Order creation is server-authoritative: product price and total amount are calculated from database records, and stock is decremented inside a transaction.

Create report example:

```bash
curl -X POST http://127.0.0.1:8000/api/reports ^
  -F "license_plate=29A-12345" ^
  -F "location=Hoan Kiem, Ha Noi" ^
  -F "violation_type=Vuot den do" ^
  -F "description=Vehicle crossed red light during rush hour" ^
  -F "violated_at=2026-07-30 09:30:00" ^
  -F "evidence=@C:\path\to\evidence.jpg"
```

Update status example:

```bash
curl -X PATCH http://127.0.0.1:8000/api/reports/1/status ^
  -H "Content-Type: application/json" ^
  -d "{\"status\":\"verified\",\"fine_amount\":300000}"
```

## Database Improvements

- Replaced legacy Vietnamese column names with consistent English names.
- Replaced integer magic statuses with readable enum values.
- Replaced `varchar` date fields with indexed timestamps.
- Replaced oversized decimals with unsigned integer money fields.
- Added indexes for status, license plate, violation type, and violation time.
- Used foreign keys and cascade/null delete rules.

## Development

```bash
php artisan test
vendor/bin/pint
```
