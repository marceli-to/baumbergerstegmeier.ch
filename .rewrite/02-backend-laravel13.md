# Backend: Laravel 11 → 13

## Why this is not optional

`composer audit` on 2026-10-05: **42 advisories across 12 packages**. Every
11.x release is blocked by Composer's advisory policy, so nothing resolves
while `composer.json` requires `laravel/framework ^11`. Fixes land in
≥ 12.69 / ≥ 13.30 (see oxid `02-backend-laravel13.md` for the list).

Target: `laravel/framework ^13.0`, PHP `^8.3` (Composer platform pinned to
8.3.0, as in oxid). Local CLI is 8.4.22. Go straight to 13.

## Dependency audit — Packagist, 2026-10-05

| Package | Locked | Latest | L13 | Action |
|---|---|---|---|---|
| `laravel/framework` | v11.44.2 | 13.x | ✅ | bump |
| `laravel/sanctum` | v4.0.8 | v4.3.3 | ✅ | bump |
| `laravel/ui` | v4.6.1 | v4.6.3 | ✅ | bump in the Composer commit, **remove** in the own-login step (D5) |
| `laravel/tinker` | 2.x | 3.x | ✅ | bump |
| `spatie/laravel-model-flags` | 1.4.1 | 1.5.0 | ✅ | bump |
| `intervention/image` | 3.x | 4.x | ✅ | bump (Glide needs it) |
| `intervention/image-laravel` | 1.x | 4.1.1 | ✅ | **drop** if nothing reads `config/image.php` after Glide (oxid dropped it) |
| `nesbot/carbon` | ^2.66 | 3.x | ✅ | remove the explicit pin; framework brings 3 |
| `guzzlehttp/guzzle` | ^7.5 | | ✅ | **drop** (D9): nothing calls it, only mentioned in `config/app.php`; the framework requires it |
| `doctrine/dbal` | ^3.6 | | n/a | **drop** — unused |
| **`marceli-to/image-cache`** | v1.4.3 | v1.4.5 | ❌ `^10\|^11` | **replace with `league/glide ^4.1`** |
| **`marceli-to/wiretap`** | v1.4.0 | v1.4.0 | ❌ `^9\|^10\|^11` | **remove** (decided 2026-10-05) |
| `barryvdh/laravel-debugbar` | 3.x | v4.4.4 | ✅ | **remove** (D8): unused, not in oxid/cra |
| `phpunit/phpunit` | ^10 | 12 | ✅ | 12, not 13 (13 needs PHP 8.4.1) |
| `nunomaduro/collision`, `mockery`, `faker`, `spatie/laravel-ignition` | | | ✅ | bump (dev) |

## Wiretap removal

Used in exactly one place: `app/Exceptions/Handler.php`
(`Wiretap::exception($e, [...])` in `report()`). The handler is deleted in
the skeleton step anyway. Remove:

- `marceli-to/wiretap` from `composer.json`
- `config/wiretap.php`
- `WIRETAP_*` from `.env`

Exceptions then go to `laravel.log` only. If error notification is wanted
later, add it in `withExceptions()` in `bootstrap/app.php`.

## Own login instead of `laravel/ui` (D5)

Port cra's `App\Http\Controllers\AuthController` (cra `06-progress.md`,
"Step 4: own login"):

- login: 5 failed attempts per e-mail + IP → one-minute lockout (what
  `ThrottlesLogins` did), logout **POST only** (admin header submits a
  hidden form with the CSRF token)
- forgot password (`throttle:6,1`) and reset (min. 8 chars, confirmed;
  logs in, goes to `/administration`). Kept (D11). Reset mail in German
  (`lang/de.json`: subject, lines, button, greeting, footer, as cra)
- same URLs, route names and Blade views (`auth/login`,
  `auth/passwords/{email,reset}`) as today; login page with a random
  published image as background (`07-admin-ui.md`)

Remove:

- `laravel/ui`, `Auth::routes(...)`, `app/Http/Controllers/Auth/*`
- `GET /logout` → `'Auth\LoginController@logout'` (`routes/web.php:20`)
- email verification: `MustVerifyEmail` on `User`, the `verified`
  middleware on the admin group (`routes/web.php:57`),
  `auth/verify.blade.php`. **Check first that every user has
  `email_verified_at` set.**
- password confirmation (`auth/passwords/confirm.blade.php`), unused
- `phpunit.xml`: `CACHE_DRIVER` → `CACHE_STORE`, otherwise the login
  throttle leaks between tests (cra)

Tests: cra's `AuthTest` (12): wrong password, lockout, login → admin →
logout, GET `/logout` 405, forgot → mail → reset → login.

### Mail (D11)

The reset mail must arrive even if nobody sets up a mail account. Use
Laravel's `failover` mailer (`config/mail.php` in the L13 skeleton
already has it):

```php
'failover' => [
    'transport' => 'failover',
    'mailers' => ['smtp', 'sendmail', 'log'],
],
```

- Production `.env`: `MAIL_MAILER=failover`. With working `MAIL_*` SMTP
  details, SMTP is used. If they are missing or wrong, the connection
  fails and sendmail takes over. `log` is the last resort, so a reset
  request never 500s.
- Sendmail on Hostpoint: check `/usr/sbin/sendmail` exists, and switch the
  path to `-t -i` (`MAIL_SENDMAIL_PATH`) if `-bs` doesn't work there.
- `MAIL_FROM_ADDRESS` must be a mailbox on a domain whose SPF allows
  Hostpoint (e.g. `noreply@baumbergerstegmeier.ch`), otherwise sendmail
  mail lands in spam. The local `.env` today sends via a `hundekot.ch`
  account as `marcel@jamon.digital`: don't carry that over.
- `MAIL_DRIVER` is dead since L7: remove it, `MAIL_MAILER` stays.
- Verify on production after deploy: request a reset for an admin address,
  check it arrives (SMTP and with SMTP deliberately off → sendmail).
- Tests use `MAIL_MAILER=array` (phpunit.xml), as cra.

## Image pipeline: image-cache → Glide

Port from oxid: `app/Http/Controllers/ImageController.php`,
`app/Support/Glide.php`, `app/Support/ImageSupport.php` (runtime
Imagick/GD + WebP/AVIF detection). Read oxid `05-image-pipeline.md` and the
"Broken AVIF renders" note in its `06-progress.md` first.

What differs here:

- URL shape: `/img/{template}/{name}/{maxSize?}/{coords?}/{ratio?}`, coords
  in the URL. No DB lookup needed. Keep the URLs working as they are, at
  least for `crop`, `thumbnail` and whatever the admin requests (check
  `mixins/Helpers.js`, `modules/images/mixins/utils.js`).
- Templates in `config/image-cache.php`: `xsmall`, `small`, `medium`,
  `large`, `xlarge`, `xxlarge`, `huge`, `thumbnail`, `crop`. Read their sizes
  out of `vendor/marceli-to/image-cache/src/Templates/` **before** removing
  the package, and grep the access logs for which are actually requested.
- `crop_filter_type: 'maxSize'` — `maxSize` is the longer side.
- Verify against production: crawl every `/img/...` URL the public pages
  emit (oxid `tools/image-crawl.py`, adapt `BASE` and the regex), render
  through the new controller, compare dimensions + PSNR
  (`tools/image-compare.sh`).
- Optional (Q2): `<source type="image/avif|webp">` in
  `components/image.blade.php`. One file, since every public image goes
  through `<x-image>`.
- Afterwards: `storage/app/public/cache/` can go; Glide cache in
  `storage/app/.glide-cache`.

## Step plan

1. Snapshot production DB + `storage/` → local, as baseline. Record route
   list (181), screenshots, the emitted `/img/` URLs.
2. **Delete dead code** (works on L11): `app/Filters/`, `config/imagecache.php`,
   unused configs/middleware listed in `01-inventory.md` once verified.
3. **One Composer commit:** PHP `^8.3`, `laravel/framework ^13.0`, drop
   `image-cache`, `wiretap`, `doctrine/dbal`, `laravel-debugbar`,
   `guzzle`, `nesbot/carbon`; add `league/glide ^4.1`,
   `intervention/image ^4`; bump the rest. Remove the `Wiretap` call from
   `Handler.php`. Image routes are down until step 4 — fine on a branch.
   (Must be one commit: see oxid "Why step 4 must be one commit".)
4. **Glide routes** + `ImageSupport`, URL-compatible with today. Verify
   against production.
5. **Slim skeleton:** `bootstrap/app.php` + `bootstrap/providers.php`.
   - `withRouting(web, api, commands, health)`
   - `withMiddleware`: `statefulApi()`, alias `role` → `CheckRole`, guest
     redirect, trusted proxies as configured today
   - `withExceptions`: empty for now
   - delete `Http/Kernel.php`, `Console/Kernel.php`, `Exceptions/Handler.php`,
     Auth/Broadcast/Event/Route providers, the middleware the framework now
     provides
   - check `RouteServiceProvider` for rate limiters / `HOME` used by
     `laravel/ui` controllers (`RedirectIfAuthenticated`, `LoginController::$redirectTo`)
6. **Own login** (see above): `AuthController`, remove `laravel/ui`,
   email verification, `GET /logout`.
7. **Config diff** against a fresh L13 skeleton. Keep: `client`, `pages`,
   `linklist`, `seo`, `sanctum` (**regenerate fresh**, don't port — oxid/luvo
   warning: a stale one referencing deleted middleware 500s every API call).
   Confirm `config:cache` **and** `route:cache` work.
8. **Validation messages:** 16 form requests; L12+ wants strings, not arrays.
   Keep the 422 shape identical.
9. Carbon 3: check `DateTime` usage in models/views (diffs, `format`).
10. `lang/de/validation.php` if not present (oxid `29edfc0`).
11. **Tests:** admin API feature tests on in-memory SQLite, public-page
    smoke tests (oxid `f4ba489`, `c195334`), `AuthTest` (cra).
12. Smoke test all routes, login, logout, password reset, every CRUD path.

## `.env` on deploy

Full list and go-live checks: `08-deploy.md`.

Same list as oxid's deploy notes: `DB_CONNECTION=mysql` explicitly,
`QUEUE_CONNECTION=sync`, `CACHE_DRIVER` → `CACHE_STORE`,
`FILESYSTEM_DRIVER` → `FILESYSTEM_DISK`, `MAIL_MAILER=failover` and
`MAIL_FROM_ADDRESS` on the site's domain (see "Mail"); remove `MAIL_DRIVER`, `WIRETAP_*` and
unused `PUSHER_*`/`MIX_*`/`REDIS_*`. `APP_URL` = exact production origin
(Sanctum stateful). `SESSION_SECURE_COOKIE=true`.
