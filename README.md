<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## About This Application

This application, built on the Laravel framework, serves as a portal for managing users and potentially network devices, tailored for environments like festivals or events requiring managed Wi-Fi access. Key features include:

*   **User Authentication:** Secure login system supporting standard email/password registration and login, email verification, password resets, and OAuth integration (e.g., Google Sign-In).
*   **User Portal:** Allows authenticated users to manage their profile information, view login history, and potentially manage associated devices or services.
*   **Admin Dashboard:** Provides administrators with tools for user management, viewing system overview metrics, and monitoring device information (e.g., battery status).
*   **Network Integration:** Includes components and database structures (RADIUS tables) suggesting integration with network authentication systems for controlling user access.
*   **API Endpoints:** Exposes data, such as battery information, via an API, likely for consumption by other services or devices.
*   **System Monitoring:** Features command-line tools for network connectivity testing (UDP).

This portal aims to streamline user onboarding, access control, and system administration for managed network environments.

## Project Structure

- `.DS_Store`: Store folder custom attributes.
- `.cursor`: Tool-specific configuration files used possibly by the Cursor editor.
- `.editorconfig`: Standardizes editor configuration across different IDEs.
- `.env`, `.env.example`: Environment configuration files.
- `.ftpquota`: FTP quota configuration.
- `.gitattributes`, `.gitignore`: Git-specific configuration files.
- `.vscode`: Contains VS Code editor configuration.
- `Portal App (Laravel).code-workspace`: VS Code workspace configuration.
- `README.md`: Project documentation.
- `Users/andrewwerling/Documents/FestivalWifiGuys`: Path reference to a user's directory (likely part of some local config or log entry).
- `app`: Application source code.
  - `Console/Commands/`: Custom Artisan CLI commands for network testing.
    - `NetworkTestCommand.php`
    - `TestUdpConnection.php`
  - `Http/`: HTTP handling components such as controllers, middleware, and the kernel.
    - `Controllers/`: Handles route requests.
      - `Api/BatteryController.php`: Manages battery information via API routes.
      - `Auth/`: Handles auth controllers such as OAuth.
        - `OAuthController.php`: Manages OAuth authentication.
        - `VerifyEmailController.php`: Handles email verification.
      - `Controller.php`: Base controller class.
    - `Kernel.php`: Middleware defined for HTTP requests.
    - `Middleware/`: Custom middleware such as checking account level and restricting IP addresses.
      - `CheckAccountLevel.php`: Checks user account level.
      - `RestrictToIpAddresses.php`: Restricts access to specific IP addresses.
  - `Listeners/`: Handles events such as login attempts.
    - `RecordFailedLoginAttempt.php`: Logs failed login tries.
    - `RecordLoginAttempt.php`: Logs successful login attempts.
  - `Livewire/`: A full-stack framework for Laravel to make dynamic reactive components.
    - `Actions/Logout.php`: Handles logout functionality.
    - `ActiveDevices.php`: Displays active Wi-Fi devices.
    - `Admin/`: Contains admin-specific Livewire components.
      - `BatteryInfo.php`: Displays battery information for admin users.
      - `UserManagement.php`: Manages users from an admin perspective.
    - `Forms/LoginForm.php`: Login form component.
    - `GuestInfo.php`: Displays guest user information.
    - `LoginHistory.php`: Shows login history.
    - `Profile/DeleteUserForm.php`: A form for deleting a user profile.
    - `PurchaseHistory.php`: Tracks purchase history (might be relevant for paid Wi-Fi plans).
    - `SystemOverview.php`: Provides an overview of the system status.
    - `UserManagement.php`: Handles user management tasks.
    - `UserSessions.php`: Displays active user sessions.
  - `Models/`: Database models.
    - `ActivityLog.php`: Handles activity logs for users.
    - `BatteryInformation.php`: Manages battery information data stored in the database.
    - `User.php`: User model.
  - `Policies/UserPolicy.php`: Defines authorization logic for users.
  - `Providers/`: Service providers that bootstrap application services such as authentication, blade templates, and Livewire.
    - `AppServiceProvider.php`
    - `AuthServiceProvider.php`
    - `BladeServiceProvider.php`
    - `EventServiceProvider.php`
    - `LivewireServiceProvider.php`
    - `VoltServiceProvider.php`
  - `Services/`: Custom services used within the application.
  - `View/Components/`: Blade components such as layouts.
    - `AppLayout.php`: Main application layout.
    - `Card.php`: Reusable card component.
    - `GuestLayout.php`: Layout for guest views.
- `artisan`: CLI application entry point for Laravel.
- `bootstrap/`: Bootstraps the application.
  - `app.php`: Main bootstrap file.
  - `cache/`: Cached configuration.
  - `providers.php`: Service providers bootstrap file.
- `client_secret_513666758332-ngh50huia7t30k0d9i6g0k9jguvri1fi.apps.googleusercontent.com.json`: Google OAuth client secret file.
- `composer.json`: PHP dependencies.
- `composer.lock`: Specific versions of installed PHP dependencies.
- `config/`: Configuration files for various Laravel services such as app, auth, cache, database, filesystems, logging, mail, queue, services, and session.
- `database/`: Database configurations and migrations.
  - `database.sqlite`: SQLite database file.
  - `factories/`: Model factories.
    - `UserFactory.php`: Factory for creating user models.
  - `migrations/`: Database migration files for creating tables such as radcheck, radreply, radusergroup, radacct, radgroupcheck, radgroupreply, radpostauth, and nas.
  - `schema/`: MySQL schema file.
    - `mysql-schema.sql`: Initial MySQL schema script.
  - `seeders/`: Database seeders.
    - `DatabaseSeeder.php`: Main database seeder.
    - `UserSeeder.php`: Seeder for populating users table.
- `error_log`: PHP error log file.
- `package.json`: JavaScript dependencies.
- `phpunit.xml`: PHPUnit configuration.
- `postcss.config.js`: PostCSS configuration file.
- `public/`: Publicly accessible files such as `index.php`, `.htaccess`, and resources such as `favicon.ico`.
  - `.well-known/`: Typically used for well-known URIs like `/.well-known/`.
  - `checklogin.php`: A script presumably for checking user login status.
- `resources/`: Contains views, assets such as CSS, and translation files (structure not fully specified here).
- `routes/`: Contains route definitions such as `api.php`, `web.php`, `console.php`.
    - `api.php`: API route definitions.
    - `auth.php`: Authentication route definitions defined separately.
    - `console.php`: Console commands route definitions.
    - `web.php`: Web route definitions.
- `storage/`: Storage for logs, compiled views, caches, and file uploads.
  - `app/`: Application-specific stored files such as private and public storage.
  - `framework/`: Caches and session files.
    - `cache/`: Cached configuration files.
    - `sessions/`: PHP session files.
    - `testing/`: Files used during testing.
    - `views/`: Compiled blade templates.
  - `logs/`: Application log stdout files.
- `tailwind.config.js`: TailwindCSS configuration.
- `terminal`: Possibly a log or command output file.
- `tests/`: Automated tests.
  - `Feature/`: Feature testing files.
    - `Auth/`: Tests for authentication features such as email verification and password reset.
    - `ExampleTest.php`: Example test case.
    - `ProfileTest.php`: Tests for user profile updates.
  - `TestCase.php`: Base test case class.
  - `Unit/`: Unit tests.
    - `ExampleTest.php`: Example unit test case.
- `vendor`: Composer dependencies.
- `vite.config.js`: V

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development/)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
