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
| Admin API CRUD tests | ✅ 123 tests, 168 in total | `9ca304e` |
| Smoke test all routes | ✅ snapshot after every step | |

## Admin

| Step | Status | Commit |
|---|---|---|
| Public site on Vite | ✅ | `9770a2b` |
| Public JS: ES modules, dead packages out (`06`) | ✅ | `7047390` |
| Public JS: lightbox from cra, verified against the fancyBox 5 build | ✅ | `7047390` |
| `lib/`, `composables/`, `components/ui/` from cra (oxid's shape) | ✅ in `resources/js/admin` | `2befdc8` |
| Admin on Vite + Vue 3: `app.js`, router 4, `App.vue` | ✅ | `2befdc8` |
| First entity end to end (award) | ✅ | `2befdc8` |
| Remaining CRUD views | ✅ (four parallel agents, then checked) | `d4ac23b` |
| Image + file modules | ✅ `ImageManager`, `FileManager` | `2befdc8` |
| galleries / links / videos | not ported: unused | |
| Grid + teaser builders | ✅ one `TeaserBuilder` (no grid builder here) | `d4ac23b` |
| Tiptap round-trip, remove TinyMCE | round-trip ✅ 287/287; TinyMCE removed with the switch | `524d1ee` |
| Light admin refresh (`07`) | ✅ (cra's admin; login background `959b09b`) | `d4ac23b` |
| Drop dead npm packages, build committed | ✅ | `d4ac23b` |
| Click-through QA | ✅ Chromium, `.rewrite/tools/qa/admin-tour.js` | |

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
- **Temporary QA user** `qa-temp@example.com` (id 8): removed again
  2026-10-20 after the admin QA.

### 2026-10-20 — public site, admin base

- **Vite** (`9770a2b`): same steps as cra. Playwright QA in
  `.rewrite/tools/qa/` (`pages.js` list, `shots.js` + `compare.sh`,
  `styles.js` computed styles with a reference CSS, `interact.js` trace,
  `lightbox-measure.js`); run with `NODE_TLS_REJECT_UNAUTHORIZED=0` (Herd's
  certificate) and `NODE_PATH` to a playwright install. Noise between two
  runs of the same build: the random home page, the worklist's sticky
  filter (scroll timing).
- **Public JS** (`7047390`): the modules were already jQuery-free; the
  work was the lightbox. Measured against fancyBox 5 (geometry in
  `components/_lightbox.scss`). fancyBox 5 loops and never disables its
  arrows; the own lightbox does the same. WebKit's worklist click-outside
  doesn't reset the filter, before and after (not chased).
- **Admin, approach:** the Vue 3 admin is built next to the old one
  (`resources/js/admin`, `resources/sass/admin`); the committed Vue 2 build
  keeps serving the admin until the layout switches, so every commit
  works. Vue 2 and 3 can't share package.json, so Mix is gone already.
  cra's ported admin is the base: its pre-port admin was the same template
  (75 shared files, most identical). Not ported because unused here:
  `modules/grid`, `galleries`, `links`, `videos`.
- **Editor:** `.rewrite/tools/tiptap-roundtrip.mjs` (from cra, H2–H4, link
  classes): 287 values, no visible difference. Needs happy-dom and
  @tiptap/html in a scratch dir.
- **Login background** (`07`): a random published project cover
  (`<x-auth-splash>`), on login and password screens only.

### 2026-10-20 — admin

- Views ported by four agents in parallel (teasers/home/landings,
  projects, team, office pages) against a shared brief and the awards
  reference, on one Vite dev server (`public/hot`) so nobody had to build;
  a fifth wrote the API tests. Each tested its screens in Chromium with a
  temporary admin and cleaned up. One agent deleted two real rows by
  mistake (project teaser 309, landing item 180) and re-inserted them from
  the snapshot; checked afterwards: every admin API response equals the
  baseline apart from `updated_at` (`snapshot.php`).
- API tests found `POST /api/team` and `/api/employee/category` 500ing
  (`Str` not imported, already broken before the upgrade); fixed.
- Differences from the old admin: dashboards → menu groups; required
  fields marked and 422s shown (the old admin swallowed them); teasers
  with a deleted image/article are labelled; the CV form reads
  `/api/cvs/{id}` and writes `/api/cv/{id}`, so it doesn't use
  `useResourceForm`; teaser/landing order uses `{items: [{id, position}]}`
  with its own save, not `useOrder`.
- **Found, not fixed (backend, already there):** projects list sorts by year
  first, so dragging only reorders within a year; a new teaser gets
  position 999; deleting an article/profile/contact/job/publication
  leaves its images attached to the deleted id; most store/update
  endpoints 500 if `images` or `publish` is missing from the payload (the
  admin always sends them).
- Bundle: the editor chunk is 415 KB (130 gzip), loaded only by forms.

## SEO (2026-10-21)

After oxid `d70cb04`.

- **Head** (`layout/partials/head.blade.php`): pages set
  `seo_title`/`seo_description`, optionally `og_image`, `og_type`,
  `canonical`, `robots`. Inline `@section` values arrive escaped, so the
  partial decodes each once; descriptions go through `App\Support\Seo::text`
  (tags stripped, entities decoded, 160 chars at a word). Adds meta robots
  (`noindex, nofollow` outside production; `noindex, follow` on the
  worklist search `?searchTerm=`; `noindex, nofollow` on
  `/projekt/vorschau/`), `og:type`/`og:locale`/`twitter:card`. The admin's
  login screens have their own layout (`layout/auth`, already noindex).
- **Projects**: canonical is `getShowRoute()` (a project is reachable by
  state and by state/category). Description: the SEO `description` from the
  admin, else the text (only 3 of 59 detail pages have one).
- **og:image**: new `/img/og/{file}/{coords?}` (`ImageController::og`,
  `Image::ogUrl()`): the admin crop, then `fit=crop` to 1200 × 630.
  Unsigned like the other `/img` URLs; the size is fixed. The default
  `assets/img/og.jpg` existed but was 1800 × 1200: recropped to 1200 × 630
  (same rendering, centre band). Path now in `config('seo.image')`.
- **Organization JSON-LD** on the homepage (`Seo::organization()`, data in
  `config/seo.php` from the footer/contact page; Instagram as `sameAs`).
- **`/sitemap.xml`, `/llms.txt`** (`SeoController`): the fixed pages, the
  project overviews as in the menu (landing by state, by state/category with
  featured projects), the worklist by status and programme, and every
  published + featured project (the ones the worklist links).
- **robots.txt** is no longer committed: `php artisan seo:robots` writes it
  per environment (production: disallow `/administration`, `/api/`,
  `/login`, `/password/`, `/projekt/vorschau/`, plus the sitemap), run by
  composer's `post-autoload-dump`.
- **Descriptions** rewritten: default, profile, team, publications, awards,
  jobs, contact, worklist (per filter), project overviews.
- Verified: `tests/Feature/SeoTest.php`, `tests/Unit/SeoTextTest.php` (new
  Unit suite in `phpunit.xml`), 179 tests pass; curl of home, a project,
  contact, overviews; `xmllint` on the sitemap (83 URLs); og:image 1200 × 630.
- **Found, not fixed (already there):** a project page 500s when its
  landing grid has a single project (`getBrowseByState`: next id is null).
  Project routes don't check `publish`/`feature`, so unpublished projects
  are reachable by URL. `getShowRoute()` 500s for a project in a state with
  categories but without a category (none today). Three projects' SEO
  descriptions read like marketing copy ("Innovative Wohnsiedlung …
  harmonische Quartiersintegration"): DB content, for the editors.

## Deploy notes

Moved to `08-deploy.md` (2026-10-20). Add to it as the work lands.
