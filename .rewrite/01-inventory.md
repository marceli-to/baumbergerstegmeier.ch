# Inventory (measured 2026-10-05, commit 37011a5)

## Size

| Area | Measured | oxid, for scale |
|---|---|---|
| `app/` PHP | 7,792 LOC | 6,494 |
| Vue components | **65 `.vue`, 11,628 LOC** | 59 / 6,978 |
| Blade | 65 files | 53 |
| Sass | 9,230 LOC (web 3,957, cms 5,273) | 12,250 |
| Routes | **181** (136 api) | 144 |
| Form requests | 16 | 10 |
| Migrations | 43 | 51 |
| Tests | 2 example stubs | 4 stubs |

Models: Article, Award, Base, Category, CategoryProject, Contact, Cv,
CvCategory, Employee, EmployeeCategory, File, Image, Job, JobArticle,
Profile, Project, ProjectLanding, Publication, State, Team, Teaser, User.

`app/Models/User.php` is already in `App\Models`.

## Auth — already where oxid ended up

- `laravel/ui` session login (`Auth::routes(['verify' => true, 'register' => false])`,
  Blade views in `resources/views/auth/`).
- Admin SPA at `/administration/{any?}` behind `auth:sanctum`, `verified`,
  `role:admin` (`App\Http\Middleware\CheckRole`).
- `api` group already has `EnsureFrontendRequestsAreStateful`; API routes
  use `auth:sanctum`. SPA sends `withCredentials`.

Nothing to migrate; carry the `role` alias and the `verified` middleware into
`bootstrap/app.php`.

## Legacy skeleton artefacts

- `bootstrap/app.php` — L8 style, binds the three kernels/handler
- `app/Http/Kernel.php` — 7 global, 2 groups, 12 aliases (`role` is custom)
- `app/Console/Kernel.php` — empty `schedule()`
- `app/Exceptions/Handler.php` — only job is `Wiretap::exception()`; goes
- `app/Providers/` — App, Auth, Broadcast, Event, Route
- `app/Http/Middleware/` — 11 classes, incl. both `CheckForMaintenanceMode`
  and `PreventRequestsDuringMaintenance`, own `HandleCors`, `TrustHosts`
  (commented out in the kernel)
- `routes/web.php` line 20 uses the string syntax `'Auth\LoginController@logout'`
  — depends on `RouteServiceProvider`'s namespace; fix during the skeleton move

## Dead code and orphans

| Item | Why |
|---|---|
| `app/Filters/Image/Template/*` (7 classes) | Intervention **v2** `FilterInterface`; referenced only by `config/imagecache.php` |
| `config/imagecache.php` | config of the old `intervention/imagecache` package, not installed |
| `doctrine/dbal` | no `->change()`, no Doctrine use; not needed since L11 |
| `config/wiretap.php` | goes with wiretap |
| `config/broadcasting.php`, `routes/channels.php`, `BroadcastServiceProvider` | no broadcasting |
| `config/cors.php` + own `HandleCors` | same-origin admin; check, then drop to the framework's |
| `public/assets/js/cms/tinymce` (4.1 MB) + `_tinymce` (3.5 MB) | goes with TinyMCE |
| `public/js/`, root `public/mix-manifest.json` | check: Mix output lives in `public/assets/` |
| `composer.phar` in the repo root | 1.9 MB; drop unless the server needs it |

Kept, in use: `client.php` (`Mail/SubscriberVerifyEmail`), `pages.php`
(menu + `AppHelper`), `linklist.php` (`Api/SettingsController`), `seo.php`.

**Unused npm packages:** `cleave.js`, `vue-the-mask`, `vuejs-datepicker`,
`nth-check` (transitive pin), `swiper` (no import), `raw-loader`.

## Image pipeline

`marceli-to/image-cache` v1.4.3 serves `/img/{template}/{name}/{maxSize?}/{coords?}/{ratio?}`
from `storage/app/public/uploads` into `storage/app/public/cache`, templates
`xsmall … huge`, `thumbnail`, `crop` (`config/image-cache.php`).

Emitted by:

- `resources/views/components/image.blade.php` — `<picture>` with
  `data-srcset="/img/crop/{name}/{maxSize}/{coords}/{ratio}"` (lazy) — ~100
  `<x-…>` usages across views
- `components/teasers/column.blade.php`, `pages/project/show.blade.php`
  (lightbox href + `og:image`, size 2000)
- Admin: `mixins/Helpers.js`, `modules/images/mixins/utils.js`

Crop coordinates are **in the URL**, so the Glide controller needs no DB
lookup (oxid's `/img/home/` did).

## Admin (`resources/js/cms/`)

- `app.js` — Vue 2 bootstrap, vue-axios, `vue-axios-interceptors`, global
  `LoadingIndicator`/`Separator`, Vuex, 10 route files merged
- `config/store.js` — Vuex, one field: `user` (display name in the header)
- `mixins/` — `Filters.js` (`Vue.filter('truncate')`), `ErrorHandling.js`
  (6× `window.intercepted.$on('response:4xx')`), `DateTime.js` (moment),
  `Helpers.js`
- `components/ui/` — 15 shared components
- `modules/` — `images`, `files` (upload/edit/crop/actions), `grid`,
  `teaser` (page builders), `galleries`, `links`, `videos`
- `views/pages/` — award, contact, homepage (article, teaser), job (page,
  article), profile, project (project, state, category, landing),
  publication, team (team, employee, employeeCategory, cv, cvCategory)
- `views/layout/PageHeader.vue`, `views/errors/`

Mounted from `resources/views/layout/authenticated.blade.php`.

## Public site (`resources/js/web/`)

jQuery + Bootstrap 4 in package.json, but **unused** (checked 2026-10-20:
`bootstrap.js` is empty, no `$(` in any module, see `06-frontend-js.md`).
9 modules (`contact`, `cv`, `fancybox`, `menu`,
`project`, `team`, `touch`, `vhcheck`, `worklist`), vendored `debounce`,
`fancybox`, `scrollTo`, `vhcheck`. Also `@fancyapps/ui` 5 from npm.
`public/assets/js/{fancybox,modernizr}.js` committed separately.

## Build

`webpack.mix.js`, 4 bundles, all `.version()`-ed:

| Source | Output |
|---|---|
| `resources/sass/web/app.scss` | `public/assets/css/app.css` |
| `resources/js/web/app.js` | `public/assets/js/app.js` |
| `resources/js/cms/app.js` | `public/assets/js/cms/app.js` |
| `resources/sass/cms/app.scss` | `public/assets/css/cms/app.css` |

`@` → `resources/js/cms/`, `processCssUrls: false`. 6 `mix()` calls in
`layout/{web,gallery,guest,authenticated}.blade.php` and `layout/partials/head.blade.php`;
plus the TinyMCE `<script>` in `authenticated.blade.php`.

## Found during the survey (pre-existing)

- `config/tiny.js`: `link_list: '/page-links'` — no such route; the endpoint
  is `GET /api/linklist`. TinyMCE's link list has probably never loaded.
