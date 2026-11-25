# WireJets

WireJets is a Laravel application starter kit. It gives a new application a ready-to-use authentication and account-management foundation built with Laravel 12, Jetstream 5, Livewire 3, and Tailwind CSS 3.

## What it includes

- Registration, login, password reset, and profile and password updates through Laravel Fortify.
- Jetstream's Livewire account profile and dashboard experience.
- Two-factor authentication with confirmation and recovery codes.
- Passkey registration and management.
- Account deletion and browser session management.
- Sanctum, Vite, and a Tailwind CSS 3 stylesheet setup.

The welcome page is the public landing page. The dashboard route uses Laravel's authenticated and verified middleware. Profile management is available at `/user/profile`. Routes and authentication behavior are provided by Laravel, Fortify, and Jetstream; application-specific pages and business logic can be added in the usual Laravel locations such as `routes/web.php`, `app/`, and `resources/views/`.

## Requirements

- PHP 8.2 or later with Laravel 12's required PHP extensions
- Composer 2
- Node.js and npm
- SQLite or another database supported by Laravel

## Create an application

Install the [Laravel installer](https://laravel.com/docs/12.x/installation), then create a project with WireJets:

```bash
laravel new my-app --using=valentinechong/wirejets --phpunit --no-boost
```

If your installed Laravel installer reports that `--using` is unsupported, update the global installer and its dependencies:

```bash
composer global require laravel/installer:^5.33 -W
```

The Laravel project creation process installs the Composer dependencies, creates the application key, prepares the default SQLite database, and runs the migrations. From the new application directory, configure `.env` for your application name, URL, mail, and database connection as needed.

## Configure features

Authentication features are configured in `config/fortify.php`. Registration, password resets, profile and password updates, two-factor authentication, and passkeys are enabled by default. Email verification is disabled by default.

Jetstream options are configured in `config/jetstream.php`. Account deletion is enabled. Jetstream API token management and teams are disabled by default. Sanctum is included, and applications can add API routes and enable Jetstream features as their requirements evolve.

Database structure is defined in `database/migrations/`. After changing the database connection in `.env`, run the migrations against that database:

```bash
php artisan migrate
```

## Development commands

Run these commands from the application directory.

| Command | Purpose |
| --- | --- |
| `composer run setup` | Install PHP and JavaScript dependencies, prepare `.env`, generate the app key, migrate the database, and build frontend assets. |
| `composer run dev` | Run the Laravel server, queue listener, log viewer, and Vite development server together. |
| `composer run test` | Clear cached configuration and run the Laravel test suite. |
| `php artisan test` | Run the Laravel test suite directly. |
| `php artisan migrate` | Apply pending database migrations. |
| `php artisan route:list` | List registered application and package routes. |
| `npm run dev` | Run Vite in frontend development mode. |
| `npm run build` | Compile frontend assets for production. |

`composer run dev` serves the application at [http://localhost:8000](http://localhost:8000). The feature tests are in `tests/Feature/`, including Livewire component tests in `tests/Feature/Livewire/`.

## Lando development environment

The included `.lando.yml` defines a PHP 8.3 Laravel appserver, a MySQL 8 database, and a Node.js 22 service for Vite. Lando assigns an available host port to the database service; the Node service exposes Vite on port `5173`.

Create the local environment file if it does not exist, then start Lando and inspect the application URL and database connection details:

```bash
if [ ! -f .env ]; then cp .env.example .env; fi
```

On Windows PowerShell, use:

```powershell
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
```

Then start the environment and inspect its settings:

```bash
lando start
lando info
```

Set `DB_CONNECTION=mysql`, `DB_HOST=database`, and `DB_PORT=3306` in `.env`. Use the database name, username, and password reported by `lando info`. The host port shown as `external_connection` is for database clients running on your computer; Laravel should use the internal `database:3306` connection.

Install dependencies, initialize the app, migrate the database, and start Vite:

```bash
lando composer install
lando php artisan key:generate
lando php artisan migrate
lando npm install
lando npm run dev
```

Open the application URL reported by `lando info`. After changing `.lando.yml`, apply the service configuration with:

```bash
lando rebuild -y
```

Run Artisan commands inside the appserver with `lando php artisan <command>`. For example, to run the test suite, use `lando php artisan test`.

## Update a user's password

The `user:update-password` command targets an account by email address. Password updates must be enabled in `config/fortify.php` using `Features::updatePasswords()`.

```bash
php artisan user:update-password user@example.com
```

Without `--force`, the command prompts for the new password twice and applies the same rules used by Fortify: the password must be present, be a string, satisfy Laravel's configured `Password::default()` rule, and match its confirmation. To skip those password validation rules and the confirmation prompt, pass `--force`:

```bash
php artisan user:update-password user@example.com --force
```

The password is entered through a hidden prompt. The command reports an error if password updates are disabled or no account matches the email. In Lando, run the command through the appserver:

```bash
lando php artisan user:update-password user@example.com
```

## Project checks

GitHub Actions validates the Composer configuration, installs the PHP and npm dependencies, builds the frontend, migrates an SQLite database, and runs the Laravel test suite on pushes and pull requests targeting `main`.

## Contributing

Issues and pull requests are welcome in the [WireJets GitHub repository](https://github.com/valentinechong/wirejets).

## License

WireJets is open-sourced under the MIT License. See [LICENSE](LICENSE).
