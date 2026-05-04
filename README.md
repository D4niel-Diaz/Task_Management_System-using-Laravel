# Task Management System

Group 2 project portfolio system built with Laravel. It follows the sample Student Management System concepts and adapts them to task records, authentication, role-based access, file handling, email notification, and RESTful API routes.

## Features

- Authentication with Admin and User roles.
- Admin task CRUD: create, list, edit, update, and delete tasks.
- User task access: users can view assigned tasks and update their own task status.
- Form validation for login, registration, task forms, status updates, and file uploads.
- Task attachments stored on the public filesystem disk with filename, type, size, and uploader metadata.
- Email notifications when a task is assigned during creation or reassignment.
- RESTful API endpoints for tasks and attachments using `GET`, `POST`, `PUT/PATCH`, and `DELETE`.

## Local Setup

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```



## API Usage

API routes are under `/api` and use HTTP Basic authentication with the same email and password accounts.

- `GET /api/tasks`
- `POST /api/tasks`
- `GET /api/tasks/{task}`
- `PUT /api/tasks/{task}`
- `PATCH /api/tasks/{task}`
- `DELETE /api/tasks/{task}`
- `POST /api/tasks/{task}/files`
- `GET /api/tasks/{task}/files/{file}`
- `DELETE /api/tasks/{task}/files/{file}`

## Deployment Notes

- Set production database, mail, and `APP_URL` values in `.env`.
- Run `php artisan migrate --force` during deployment.
- Run `php artisan storage:link` so uploaded files can be downloaded.
- Use `MAIL_MAILER=log` for local testing or SMTP/Mailtrap credentials for real email delivery.
- Keep `APP_DEBUG=false` in production.
