# Progress

Branch **`rework/laravel-13-vue-3`**, cut from `37011a5` on 2026-10-05.

## Before the first commit

- [x] Hosting: Hostpoint, PHP 8.3–8.5 available (D10, 2026-10-20)
- [ ] Site's web + CLI PHP switched to ≥ 8.3; Imagick/GD, WebP/AVIF,
      upload limits recorded — `04-open-questions.md` #1
- [ ] All users have `email_verified_at` set (before dropping verification)
- [ ] Production DB + `storage/` → local, row counts match
- [ ] Baseline: route list (181), screenshots of public pages + admin,
      every `/img/` URL the public pages emit
- [ ] Access-log grep: `/img/{template}` usage (#4)

## Backend

| Step | Status | Commit |
|---|---|---|
| Delete dead code | | |
| One Composer commit: L13, Glide, drop image-cache/wiretap/dbal/debugbar/guzzle/carbon | | |
| Glide routes, URL-compatible, verified against production | | |
| `<picture>` + WebP/AVIF (if #2) | | |
| Slim skeleton | | |
| Own login, `laravel/ui` + email verification removed | | |
| Password reset kept, German mail, `failover` mailer (SMTP → sendmail → log) | | |
| Config diff vs L13, fresh Sanctum config | | |
| Validation messages (16 form requests) | | |
| Admin API + public page tests | | |
| Smoke test all routes + CRUD | | |

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

## Deploy notes

Moved to `08-deploy.md` (2026-10-20). Add to it as the work lands.
