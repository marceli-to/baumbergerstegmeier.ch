# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 13 application for Baumberger Stegmeier Architektur, featuring a public website with project portfolio and a CMS backend. The admin is a Vue 3 SPA built with Vite and styled with Tailwind 4 (the UI of the sister project oxid.ch). The 2026 rework is logged in `.rewrite/` (`05-progress.md`, deploy notes in `08-deploy.md`).

## Development Commands

### Frontend Build Commands
- `npm run dev` - Vite dev server (writes `public/hot`; remove it if the server dies)
- `npm run build` - Production build into `public/build` (committed: build and commit the output with the change)

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
- **Admin** (`resources/js/admin/`, `resources/css/admin.css`) - Vue 3 SPA under `/administration`; the login and password screens are Blade (`resources/views/auth`, `layout/auth.blade.php`) on the same Tailwind CSS

The admin uses vue-router 4, Tiptap (editor), vue-advanced-cropper, vuedraggable and Phosphor icons. Lists are `components/resource/ResourceIndex.vue`, forms `ResourceForm.vue` with `components/ui/form/FormField.vue`, images `components/media/ImageField.vue`; the data layer is in `composables/` (`useListing`, `useResourceForm`, `useOrder`, `useImages`). Tailwind uses a pixel spacing scale (`resources/css/spacing.css`: `p-24` = 24px) and `dark:` variants with the `warm-*` palette.

### Backend Structure
- **Controllers**: Standard Laravel structure with separate auth controllers
- **Models**: Located in `app/Models/`
- **Images**: served through Glide (`ImageController`, `<x-image>`, `config/images.php`)
- **SEO**: `App\Support\Seo`, `SeoController` (`/sitemap.xml`, `/llms.txt`), `php artisan seo:robots` writes `public/robots.txt` per environment
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