# ResQNet

ResQNet is a Laravel-based disaster rescue coordination platform for tracking SOS requests, agencies, resources, alerts, and live operational dashboards.

## Core Modules

- Authentication for volunteers and victims
- SOS request creation, assignment, and status tracking
- Disaster lifecycle management
- Agency registration and verification
- Resource inventory and deployment workflows
- Alert publishing and analytics dashboard

## Tech Stack

- Laravel 12 (PHP)
- Blade templates
- Vite + TailwindCSS
- Laravel Echo + Pusher client

## Local Setup

1. Install PHP and Node dependencies:

```bash
composer install
npm install
```

2. Create environment file and app key:

```bash
cp .env.example .env
php artisan key:generate
```

3. Configure database credentials in `.env`, then run migrations:

```bash
php artisan migrate
```

4. Start the app in one terminal and Vite in another:

```bash
php artisan serve
npm run dev
```

## Useful Commands

```bash
php artisan test
php artisan route:list
npm run build
```

## Default Entry Points

- Landing page: `/`
- Login: `/login`
- Dashboard: `/dashboard`

## Notes

- Most application routes require authentication.
- Resource shortages are highlighted when available quantity is at or below threshold.
- SOS updates are broadcast via the `NewSOSRequest` event.

## Changelog

- 2026-08-09: Repository housekeeping and documentation updates.

