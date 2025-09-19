# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application with authentication, user management, wallet system, and transaction tracking features. The application uses Laravel Fortify for authentication, Spatie Laravel Permission for role-based access control, and includes a basic wallet/transaction system.

## Development Commands

### Running the Application
```bash
# Start full development environment (server, queue, logs, vite)
composer dev

# Or run individually:
php artisan serve                    # Start development server
php artisan queue:listen --tries=1   # Start queue worker
php artisan pail --timeout=0        # Start log viewer
npm run dev                          # Start Vite development server
```

### Testing
```bash
# Run all tests
composer test
# or
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run with coverage
php artisan test --coverage
```

### Build Commands
```bash
# Build frontend assets
npm run build

# Code formatting
vendor/bin/pint                      # PHP code style fixer (Laravel Pint)
```

### Database Operations
```bash
# Run migrations
php artisan migrate

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Rollback migrations
php artisan migrate:rollback

# Create new migration
php artisan make:migration create_table_name
```

## Application Architecture

### Authentication & Authorization
- **Laravel Fortify**: Handles authentication including two-factor authentication
- **Spatie Laravel Permission**: Role and permission management
- **Email Verification**: Configurable via SystemSetting model
- Custom User model with wallet relationship and email verification logic

### Core Models
- **User**: Extended with wallet relationships, two-factor auth, roles/permissions
- **Wallet**: One-to-one relationship with User
- **Transaction**: Belongs to User, tracks financial transactions
- **SystemSetting**: Key-value configuration storage

### Key Directories
- `app/Actions/Fortify/`: Custom Fortify action classes
- `app/Http/Controllers/`: Standard Laravel controllers
- `app/Http/Middleware/`: Custom middleware
- `app/Models/`: Eloquent models with relationships
- `app/Console/Commands/`: Custom Artisan commands
- `config/fortify.php`: Fortify authentication configuration
- `config/permission.php`: Spatie permission configuration

### Frontend
- **Vite**: Asset bundling and development server
- **Tailwind CSS 4.0**: Utility-first CSS framework
- **Axios**: HTTP client for API requests

### Testing
- **PHPUnit**: Primary testing framework
- SQLite in-memory database for testing
- Test suites: Unit and Feature tests in `tests/` directory

## Common Development Tasks

### Creating New Features
1. Create migration: `php artisan make:migration`
2. Create model: `php artisan make:model ModelName`
3. Create controller: `php artisan make:controller ControllerName`
4. Add routes in `routes/web.php`
5. Create tests: `php artisan make:test FeatureTest`

### Working with Permissions
```bash
# Create new permission
php artisan make:permission permission-name

# Assign permission to role
php artisan permission:create-role role-name
```

### Queue Management
```bash
# Process queue jobs
php artisan queue:work

# List failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```