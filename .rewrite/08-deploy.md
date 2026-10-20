# Deploy and go-live

Everything that has to happen on the server or before go-live, in one
place. Started 2026-10-20 from the plan, before any code was written. Add
to it as the work lands, as cra did ("Deploy notes" in its
`06-progress.md`, which this follows).

Hosting: **Hostpoint** (D10). Deploy as cra/oxid: SSH + `git pull`, built
assets committed (`public/build`), nothing built on the server.

## Before go-live

- [ ] The site's PHP is ≥ 8.3 for web **and** CLI (Hostpoint panel + the
      `php` on the SSH path). Record Imagick/GD, WebP/AVIF,
      `upload_max_filesize`, `post_max_size`, `memory_limit` (`04` #1).
- [ ] Every user has `email_verified_at` set. Email verification is
      removed with the own login (D5), so an unverified user would lose
      nothing, but check that nothing relied on it.
- [ ] Mail sender: a mailbox on the site's domain, e.g.
      `noreply@baumbergerstegmeier.ch`, so SPF covers Hostpoint (D11).
- [ ] SMTP account for that address, if there is one. Without one,
      sendmail takes over (below).
- [ ] `/usr/sbin/sendmail` exists on the server (`ls -l /usr/sbin/sendmail`).
- [ ] Leftovers confirmed unused outside the repo (`04` #6):
      `public/js/`, root `public/mix-manifest.json`,
      `public/assets/js/{fancybox,modernizr}.js`, `composer.phar`,
      `config/cors.php`.
- [ ] Tell the editors: saving a text in the new editor (Tiptap) tidies
      its stored HTML (`04` #3). The visible text does not change.
- [ ] Tell them: login, logout and password reset look slightly different
      (own login with a background image); URLs stay the same.

## Production `.env`

Laravel 13's config files fall back to the framework's defaults, and some
of those changed. Set explicitly:

| Key | Value | Why |
|---|---|---|
| `DB_CONNECTION` | `mysql` | default is now `sqlite` |
| `SESSION_DRIVER` | as today (`file`/`cookie`) | default is now `database` |
| `CACHE_STORE` | `file` | was `CACHE_DRIVER`; default is now `database` |
| `QUEUE_CONNECTION` | `sync` | default is now `database` |
| `FILESYSTEM_DISK` | as today | was `FILESYSTEM_DRIVER` |
| `MAIL_MAILER` | `failover` | SMTP → sendmail → log (D11, below) |
| `MAIL_FROM_ADDRESS` | address on the site's domain | spam (see above) |
| `MAIL_SENDMAIL_PATH` | only if `-bs` fails: `/usr/sbin/sendmail -t -i` | |
| `APP_URL` | exact production origin | Sanctum stateful domains |
| `SANCTUM_STATEFUL_DOMAINS` | production host(s) | admin API |
| `SESSION_SECURE_COOKIE` | `true` | |
| `LOG_STACK` / `LOG_LEVEL` | what the old `logging.php` hard-coded | |

Remove: `MAIL_DRIVER` (dead since L7), `CACHE_DRIVER`,
`FILESYSTEM_DRIVER`, `WIRETAP_*` (D1), `BROADCAST_DRIVER`, `PUSHER_*`,
`MIX_*`, unused `REDIS_*`.

The local `.env` currently sends via a `hundekot.ch` SMTP account as
`marcel@jamon.digital`. **Don't carry that over.**

## Mail: SMTP, then sendmail (D11)

Password reset stays (D11), so mail must work even if nobody sets up a
mail account. `config/mail.php`:

```php
'failover' => [
    'transport' => 'failover',
    'mailers' => ['smtp', 'sendmail', 'log'],
],
```

- With working `MAIL_HOST`/`MAIL_USERNAME`/`MAIL_PASSWORD`, SMTP is used.
- If they are missing or wrong, the SMTP connection fails and sendmail
  takes over.
- `log` is the last resort: a reset request never ends in a 500, but the
  mail only lands in `storage/logs/laravel.log`.

## What the pull changes on disk

- `public/build/` (site and admin) is committed; nothing is built on the
  server
- Gone with the pull: `public/assets/js/cms` (Vue 2 admin + TinyMCE),
  `public/assets/css/cms/app.css`, `public/assets/css/app.css`,
  `public/assets/js/app.js`, `public/mix-manifest.json`. The admin fonts in
  `public/assets/css/cms/fonts` stay (the new admin uses them)
- Still there, to decide (`04` #6): `public/js/`, `public/assets/js/
  {fancybox,modernizr}.js`, `composer.phar` — nothing in the repo uses them
- `storage/app/.glide-cache/` is created on the first image request

## Order on the server

1. Snapshot the production DB and `storage/`.
2. Prepare `.env` (above). Check the PHP version and upload limits.
3. `git pull`, `composer install --no-dev --optimize-autoloader`.
4. `php artisan migrate --force`, if the rework adds migrations.
5. `php artisan optimize:clear && php artisan optimize` (must work: both
   `config:cache` and `route:cache`).
6. No warm-up command: each image renders on its first request (JPEG
   ~0.1 s, WebP ~0.25 s, AVIF ~0.3 s at 1600 px locally), then comes from
   the cache. Browse the main pages once after deploy if wanted.
7. Delete `storage/app/public/cache/` (image-cache renditions, replaced by
   Glide in `storage/app/.glide-cache`).

## After deploy: check

- [ ] Public: home, projects (overview, landing by state/category, a
      project page), office, team, contact, a 404. Images load, WebP/AVIF
      served where supported.
- [ ] Lightbox on a project page and on a teaser column with two projects.
- [ ] Login (random project cover as background), wrong password message,
      logout from the admin sidebar, `GET /logout` → 405.
- [ ] Password reset over **SMTP**: request it for an admin address, the
      German mail arrives, the link resets and logs in.
- [ ] Password reset over **sendmail**: break the SMTP password in
      `.env` for a moment (`php artisan config:clear` afterwards), request
      again, the mail arrives (and not in spam). Put the password back.
- [ ] Admin: list, edit, save one of each entity; an image upload and
      crop; reorder a list; the editor's link dialog (page list, Icon ↗).
      `.rewrite/tools/qa/admin-tour.js` against production with a real
      account visits every screen (read-only).
- [ ] `storage/logs/laravel.log` free of new errors.
