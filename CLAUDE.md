# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 11 application for Baumberg & Stegmeier architecture firm, featuring a public website with project portfolio and a CMS backend. The application uses PHP 8.2+ with a Vue.js 2.x frontend for the CMS interface.

## Development Commands

### Frontend Build Commands
- `npm run dev` - Development build using Laravel Mix
- `npm run watch` - Watch files for changes and rebuild
- `npm run hot` - Hot module replacement for development
- `npm run prod` - Production build with optimization

### PHP/Laravel Commands
- `php artisan serve` - Start local development server
- `php artisan migrate` - Run database migrations
- `php artisan db:seed` - Seed the database
- `php artisan tinker` - Interactive PHP shell
- `composer install` - Install PHP dependencies
- `composer dump-autoload` - Regenerate autoload files

### Testing
- `vendor/bin/phpunit` - Run PHPUnit tests
- `php artisan test` - Laravel's test runner

## Architecture

### Frontend Structure
The application has two distinct frontend builds:
- **Web frontend** (`resources/js/web/`, `resources/sass/web/`) - Public website
- **CMS frontend** (`resources/js/cms/`, `resources/sass/cms/`) - Admin interface using Vue.js 2.x

The CMS uses Vue.js with:
- Vue Router for navigation
- Vuex for state management
- Vue components for UI (TinyMCE editor, image cropping, drag & drop)

### Backend Structure
- **Controllers**: Standard Laravel structure with separate auth controllers
- **Models**: Located in `app/Models/`
- **Custom packages**: Uses `marceli-to/image-cache` and `marceli-to/wiretap` packages
- **Image processing**: Custom image filters in `app/Filters/Image/Template/`
- **Mail**: Email templates in `app/Mail/`

### Key Features
- Project portfolio management with states and categories
- Team/employee management
- Publications and awards sections
- Contact functionality
- Image processing and caching system
- Custom CMS interface built with Vue.js

### Database
Uses standard Laravel migrations in `database/migrations/`. Key entities include:
- Projects with states and categories
- Employees with categories
- Articles/publications
- Awards
- Contact information

### Routes
- **Frontend routes**: Standard project browsing, office info, contact (`routes/web.php`)
- **API routes**: Likely in `routes/api.php` for CMS functionality
- **Admin routes**: Protected routes for CMS access

The site structure follows German language patterns with URLs like `/projekte`, `/buero`, `/kontakt`.

## Environment Setup

Copy `.env` configuration and ensure proper database and image processing dependencies are configured. The application requires image processing capabilities via the Intervention Image package.