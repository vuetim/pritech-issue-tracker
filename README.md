# PRITECH Issue Tracker

A Laravel issue-tracking application for small teams to manage projects, issues, tags, comments, and issue assignments.

The application was built as a technical task using Laravel conventions, server-rendered Blade views, Eloquent relationships, Form Requests, and lightweight AJAX interactions.

## Features

### Projects

- List, create, view, edit, and delete projects
- Display each project together with its paginated issues
- Track project start dates and deadlines
- Associate every project with an owner
- Allow only the project owner to edit or delete the project

### Issues

- List, create, view, edit, and delete issues
- Filter issues by status, priority, and tag
- Search issue titles and descriptions with debounced AJAX requests
- Use PHP-backed enums for issue status and priority
- Avoid N+1 queries through eager loading

### Tags and comments

- Create and list uniquely named tags
- Attach and detach tags from an issue without reloading the page
- Load paginated comments through AJAX
- Add comments through AJAX with inline validation feedback
- Prepend newly added comments and clear the form after submission

### Authentication and assignments

- Log in and log out using Laravel's session authentication
- Assign multiple users to an issue through the `issue_user` pivot table
- Assign and remove issue members through AJAX
- Seed demo users, projects, issues, tags, assignments, and comments

## Technology

- PHP 8.3+
- Laravel 13
- MySQL 8
- Blade
- Tailwind CSS 4
- Vanilla JavaScript and Fetch API
- Vite
- PHPUnit

## Requirements

Install the following tools before setting up the application:

- PHP 8.3 or newer with the MySQL PDO extension
- Composer
- Node.js and npm
- MySQL Server
- Git

Laravel Herd can be used for local development, but it is optional.

## Installation

Clone the repository and enter the project directory:

```bash
git clone https://github.com/vuetim/pritech-issue-tracker.git
cd pritech-issue-tracker
```

Install PHP dependencies:

```bash
composer install
```

Create the local environment file:

```powershell
Copy-Item .env.example .env
```

On macOS or Linux, use:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Create a MySQL database:

```sql
CREATE DATABASE pritech_issue_tracker
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

Update the database settings in `.env` for your local MySQL installation:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pritech_issue_tracker
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

If MySQL uses a different port, update `DB_PORT`. The MySQL service must be running before migrations or the application can connect to it.

Run the migrations and seed the demo data:

```bash
php artisan migrate --seed
```

Install and build the frontend assets:

```bash
npm install
npm run build
```

Start the application on port 8000:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

If port 8000 is unavailable, select another port, for example:

```bash
php artisan serve --port=8001
```

When using Laravel Herd from a parked directory, the project is also available at `http://pritech-issue-tracker.test`.

## Demo accounts

All seeded demo accounts use the password `password`.

| Name        | Email                     |
| ----------- | ------------------------- |
| Alex Morgan | `alexmorgan@pritech.test` |
| Jamie Lee   | `jamielee@pritech.test`   |
| Taylor Kim  | `taylorkim@pritech.test`  |

The seeded projects belong to Alex Morgan. Other authenticated users can collaborate on issues, tags, comments, and assignments, but they cannot edit or delete Alex's projects. A user who creates a new project becomes that project's owner.

## Development

For frontend development with automatic asset rebuilding, run:

```bash
npm run dev
```

Run the Laravel server in another terminal:

```bash
php artisan serve
```

To recreate the local database and demo data from scratch:

```bash
php artisan migrate:fresh --seed
```

This command deletes all existing data and must not be used against a production database.

## Verification

Run the automated tests:

```bash
php artisan test
```

Check and fix PHP code style:

```bash
vendor/bin/pint
```

Create a production frontend build:

```bash
npm run build
```

## Data model

- A project belongs to an owner and has many issues.
- An issue belongs to a project and has many comments.
- Issues and tags have a many-to-many relationship through `issue_tag`.
- Issues and users have a many-to-many relationship through `issue_user`.
- A comment belongs to an issue.

Deleting a project cascades to its issues. Deleting an issue removes its related comments and pivot-table records through database foreign-key constraints.

## Implementation notes

- Resource controllers are used for project and issue CRUD operations.
- Form Request classes contain validation rules.
- Eloquent relationships and eager loading are used to keep queries clear and avoid N+1 problems.
- Issue status and priority are stored as strings and cast to PHP-backed enums.
- Blade layouts and partials keep the server-rendered UI reusable.
- AJAX endpoints return JSON while Blade partials render reusable comment markup.
- `ProjectPolicy` enforces project ownership on both the server and the user interface.
- CSRF tokens protect all state changing form and AJAX requests.

## Security

The `.env` file is intentionally excluded from Git. Do not commit database passwords, application keys, or other local credentials.
