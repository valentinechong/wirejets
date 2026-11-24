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
laravel new my-app --using=valentinechong/wirejets
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

## Project checks

GitHub Actions validates the Composer configuration, installs the PHP and npm dependencies, builds the frontend, migrates an SQLite database, and runs the Laravel test suite on pushes and pull requests targeting `main`.

## Contributing

Issues and pull requests are welcome in the [WireJets GitHub repository](https://github.com/valentinechong/wirejets).

## License

WireJets is open-sourced under the MIT License. See [LICENSE](LICENSE).
