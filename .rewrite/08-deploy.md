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
- [x] Every user has `email_verified_at` set (production dump of
      2026-10-21: 7 users, none unverified). Email verification is
      removed with the own login (D5).
- [ ] Mail sender `noreply@baumbergerstegmeier.ch` (already the local
      `MAIL_FROM_ADDRESS`), so SPF covers Hostpoint (D11). Only the
      admin password reset sends mail (below).
- [ ] Optional: an SMTP account for that address. Without one,
      sendmail takes over.
- [ ] `/usr/sbin/sendmail` exists on the server (`ls -l /usr/sbin/sendmail`).
- [ ] Leftovers confirmed unused outside the repo (`04` #6):
      `public/js/`, root `public/mix-manifest.json`,
      `public/assets/js/{fancybox,modernizr}.js`, `composer.phar`,
      `config/cors.php`.
- [ ] Tell the editors: saving a text in the new editor (Tiptap) tidies
      its stored HTML (`04` #3). The visible text does not change.
- [ ] Tell them: login, logout and password reset look slightly different
      (own login with a background image); URLs stay the same.

## Dry run with production data (2026-10-21)

The production dump (`sisecufi_bsa`, MariaDB 10.11) and
`storage/app/public` were loaded locally. Production has run the same
migrations as the branch, so the deploy has nothing to migrate. All 94
sitemap URLs answer 200, and so do the 230 image URLs on home, a project,
team and publications. Nothing new in the log. 177 image rows have no file,
all of them soft-deleted (127 on projects), so nothing visible is missing.

## Second dry run (2026-11-27)

Fresh dump (`sisecufi_bsa`, phpMyAdmin, 27 Nov 2026 09:28) and
`storage/app/public` loaded locally, deploy order followed: 43 migrations,
all already run (nothing pending); `optimize` passes; deploy step 7 done
(old `storage/app/public/cache`, 805 MB, removed; Glide cache started empty).
137 projects, 1228 image rows, 7 users (all verified). 177 image rows have no
file, all soft-deleted, the same as in October. All 96 sitemap URLs answer
200; all 4973 `/img/…` URLs on those pages answer 200 (every size, JPEG/WebP/
AVIF), rendering ~635 MB of Glide cache from cold. Nothing new in the log;
190 tests pass. The `files` table is empty, so no publication links to
`/storage/uploads/…` yet; the server still needs the `public/storage`
symlink for when one is uploaded.

## Staging for the client (planned 2026-11-26)

Sequence: fresh production dump + `storage/app/public` locally, full
check there, then a staging site with that data for the client to go
through, then the production deploy. Staging data need not match
production exactly: production stays the live data, nothing moves from
staging to production.

- **Not indexed:** anything but `APP_ENV=production` gets `Disallow: /`
  in `robots.txt` (`php artisan seo:robots`) and `noindex, nofollow` on
  every page (`Seo`, `app()->isProduction()`). Use `APP_ENV=staging`;
  HTTP basic auth on top if it shouldn't be public at all.
- **Mail:** the users table holds the real admin addresses, so a reset
  request would mail the editors a staging link. `MAIL_MAILER=log`
  unless delivery is being tested on purpose.
- **Logins:** the client logs in with their usual accounts (the hashes
  come with the dump).
- **Edits are thrown away:** tell the client staging is a copy; nothing
  done there reaches production.
- **Server rehearsal:** on the same Hostpoint account, staging is where
  to settle the server items before the real deploy: PHP ≥ 8.3 for web
  and CLI, Imagick/GD and WebP/AVIF, upload limits (`04` #1), the `.env`
  table below (`APP_URL`, `SANCTUM_STATEFUL_DOMAINS`, …), write access to
  `storage/app/.glide-cache`, `optimize` passing, `ls -l /usr/sbin/sendmail`.
- **Local check before staging:** the "After deploy: check" list below
  (all but the mail tests), plus
  `BASE=… QA_EMAIL=… QA_PASSWORD=… node .rewrite/tools/qa/admin-tour.js`
  for every admin screen.

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

Remove: `BS_*` (`config/client.php` is gone), `MAIL_DRIVER` (dead since L7), `CACHE_DRIVER`,
`FILESYSTEM_DRIVER`, `WIRETAP_*` (D1), `BROADCAST_DRIVER`, `PUSHER_*`,
`MIX_*`, unused `REDIS_*`.

The local `.env` currently sends via a `hundekot.ch` SMTP account as
`marcel@jamon.digital`. **Don't carry that over.**

## Mail: SMTP, then sendmail (D11)

The only mail the application sends is the **admin password reset**
(`AuthController::sendResetLink`, Laravel's `ResetPassword`
notification), to the 7 admin logins. The public site sends nothing (no
contact form). The newsletter leftover `App\Mail\SubscriberVerifyEmail`
and `config/client.php` (read only by it) were removed on 2026-11-26.
Remove the `BS_*` keys from the production `.env`.

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
- `public/robots.txt` is no longer in git (SEO, 2026-10-21): the pull
  deletes it, `composer install` writes it again (`php artisan seo:robots`,
  per `APP_ENV`). Production gets the crawl rules and the `Sitemap:` line
  from `APP_URL`, every other environment `Disallow: /`

## Removed with the admin UI from oxid (2026-10-21)

`public/assets/css/cms/` (admin fonts) and `public/assets/img/cms/`
(admin icons, placeholder) are deleted from git; delete them on the server
too if the deploy doesn't (rsync without `--delete`). The admin loads
Google Sans from Google Fonts.

## Order on the server

1. Snapshot the production DB and `storage/`.
2. Prepare `.env` (above). Check the PHP version and upload limits.
3. `git pull`, `composer install --no-dev --optimize-autoloader` (also
   writes `public/robots.txt`; without composer: `php artisan seo:robots`).
   `APP_ENV=production` and `APP_URL` must be set before.
4. `php artisan migrate --force`, if the rework adds migrations.
   Check `ls -l public/storage` points at this site's `storage/app/public`
   (not in git; publication PDFs are served from `/storage/uploads/…`);
   if not, `php artisan storage:link --force`.
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
- [ ] SEO: `/robots.txt` has the production rules and the `Sitemap:` line;
      `/sitemap.xml` and `/llms.txt` list production URLs; a page's source
      shows `<meta name="robots" content="index, follow">`; a project's
      og:image (`/img/og/…`) opens at 1200 × 630. Submit the sitemap in
      Google Search Console if the site is registered there.
- [ ] Admin → System → Benutzer: list loads; deleting one's own account
      is refused. `/projekt/vorschau/{id}` as a guest redirects to the login.
- [ ] `storage/logs/laravel.log` free of new errors.
