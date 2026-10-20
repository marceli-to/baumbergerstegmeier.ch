# Progress

Branch **`rework/laravel-13-vue-3`**, cut from `37011a5` on 2026-10-05.

## Before the first commit

- [x] Hosting: Hostpoint, PHP 8.3–8.5 available (D10, 2026-10-20)
- [ ] Site's web + CLI PHP switched to ≥ 8.3; Imagick/GD, WebP/AVIF,
      upload limits recorded — `04-open-questions.md` #1
- [x] All users have `email_verified_at` set — local DB, all 7 (2026-10-20);
      check production before deploy (`08-deploy.md`)
- [ ] Production DB + `storage/` → local, row counts match — **not done**:
      the baseline is the local DB (995 images, 858 uploads, 124 projects),
      which is a recent copy. Production was not touched.
- [x] Baseline (2026-10-20, local data): route list
      (`.rewrite/data/routes-before.json`), `snapshot.php` of 2,116
      requests (117 public pages crawled from the home page, every project
      preview, every read-only admin API GET), the 1,204 `/img/` URLs the
      pages emit, rendered by image-cache (`.rewrite/data/img-0/`)
- [ ] Access-log grep: `/img/{template}` usage (#4) — moot: the site and
      admin only use `crop`, `thumbnail` and `original`; the other
      templates now 404

## Backend

| Step | Status | Commit |
|---|---|---|
| Delete dead code | ✅ | `dd547a0` |
| One Composer commit: L13, Glide, drop image-cache/wiretap/dbal/debugbar/guzzle/carbon | ✅ L13.38, `composer audit` 0 | `62824c8` |
| Glide routes, URL-compatible, verified | ✅ against local image-cache renders | `62824c8` |
| `<picture>` + WebP/AVIF (#2) | ✅ | `b526da9` |
| Slim skeleton + config diff, Sanctum package defaults | ✅ | `8c2a6bc` |
| Own login, `laravel/ui` + email verification removed | ✅ | `987db82`, `5bfb1c1` |
| Password reset kept, German mail, `failover` mailer (SMTP → sendmail → log) | ✅ | `987db82` (mailer in `8c2a6bc`) |
| Validation messages (16 form requests) | ✅ | `845eb2f` |
| Public page tests, API validation tests | ✅ 44 tests | `4a66081` |
| Admin API CRUD tests | with the admin port | |
| Smoke test all routes | ✅ snapshot after every step | |

## Admin

| Step | Status | Commit |
|---|---|---|
| Public site on Vite | | |
| Public JS: ES modules, dead packages out (`06`) | | |
| Public JS: lightbox from cra, verified against the fancyBox 5 build | | |
| `lib/`, `composables/`, `components/ui/` from oxid | | |
| Admin on Vite + Vue 3: `app.js`, router 4, `App.vue` | | |
| First entity end to end (award) | | |
| Remaining CRUD views | | |
| Image + file modules | | |
| galleries / links / videos | | |
| Grid + teaser builders | | |
| Tiptap round-trip, remove TinyMCE | | |
| Light admin refresh (`07`) | | |
| Drop dead npm packages, build committed | | |
| Click-through QA | | |

## Log

### 2026-10-20 — backend

Tools: `.rewrite/tools/snapshot.php` (after cra; crawls the public site
from `/`, all project previews, all read-only admin API GETs; run with
`APP_DEBUG=false CACHE_DRIVER=array CACHE_STORE=array`, else the api
throttle 429s). Snapshots and image renders in `.rewrite/data/`
(gitignored). The home page shows a random project, so `guest_.txt`
differs on every run; leave it out of the diff.

- **Images.** cra's first Glide controller (`c7cca7c` there), which kept
  image-cache's URLs, rather than its later signed URLs: here the crop
  coords are in the URL already. Max size 2400 (image-cache's default;
  cra had 2600). All 1,204 URLs the site emitted render with the same
  dimensions as image-cache; RMSE median 0.007, max 0.038.
  - **CMYK:** about 1 % of uploads are CMYK. image-cache rendered with GD,
    which wrote sRGB; Glide on Imagick kept CMYK (RMSE 0.69 on one image,
    and WebP/AVIF can't hold CMYK). `App\Support\SrgbManipulator` runs
    first in the Glide pipeline. Test: `testCmykBecomesSrgb`.
  - Admin crop previews send decimal coords; image-cache 400'd them, Glide
    accepts them (as cra).
  - `/img/{xsmall…huge}/…` are gone (404); nothing used them.
- **`<x-image>`** (bug that was already there): the `<source>`s had `data-srcset` but
  no script loads them, so every screen got the biggest size through
  `<img src>`. Now real `srcset`, widest breakpoint first, AVIF and WebP
  per breakpoint, `<img src>` the smallest breakpoint's size. Sample of
  15: WebP 78 %, AVIF 75 % of the JPEG bytes; AVIF encodes in ~0.3 s.
- **Skeleton.** Route middleware identical apart from class names. The
  api throttle was the bare `ThrottleRequests` class (60/min), not the
  200/min limiter `RouteServiceProvider` defined: now `throttle:200,1`.
  Config 22 → 13 files, each only what differs from the framework (they
  merge; `mailers`, `channels`, `disks` are merged one level deeper).
  Local `.env`: `CACHE_DRIVER` → `CACHE_STORE`.
- **Login.** cra's `AuthController`. Logout is POST; `GET /logout`
  stays until the Vue 3 admin header replaces the link
  (`PageHeader.vue`). Reset mail verified over HTTP through the failover
  chain with SMTP and sendmail made to fail (lands in the log), link →
  reset → admin → login with the new password.
- **Validation.** Laravel 13 500s on array messages (`{field, error}`,
  read by the Vue 2 admin's `ErrorHandling` mixin). Now strings, standard
  422 shape. Until the admin port the old admin no longer marks fields.
- **Found, not fixed (already there):** `/projekte` routes to
  `ProjectController::index`, which doesn't exist (500; nothing links
  to it). Award 22 has malformed UTF-8 in the DB, so `/api/award/22`
  500s (JSON encode). `StorageCleanup` task is never scheduled.
- **Temporary QA user** `qa-temp@example.com` (id 8) in the local DB for
  the HTTP checks; delete when the admin is done.

## Deploy notes

Moved to `08-deploy.md` (2026-10-20). Add to it as the work lands.
