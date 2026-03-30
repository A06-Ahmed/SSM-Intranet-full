# SMM Intranet API

Production-ready Laravel 11 API backend with JWT authentication and RBAC.

## Setup

1. Configure `.env` for MySQL (`DB_DATABASE=smm_intranet_db`).
2. Generate JWT secret: `php artisan jwt:secret`
3. Run migrations and seeders: `php artisan migrate --seed`
4. Create storage link: `php artisan storage:link`
5. Serve: `php artisan serve`

## API Base

`http://localhost:8000/api`

## Swagger

Generate docs: `php artisan l5-swagger:generate`  
View docs: `http://localhost:8000/api/documentation`

## Default Admin

Email: `admin@smm.com`  
Password: `Admin123!`
