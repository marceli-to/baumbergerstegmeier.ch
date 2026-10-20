# Rewrite notes — Laravel 11→13, Vue 2→3

Survey done **2026-10-05** against commit `37011a5` (branch `master`, clean
tree). Branch: **`rework/laravel-13-vue-3`**, cut from `37011a5`.

Modelled on **oxid.ch** (`.rewrite/` on `rework/laravel-13-vue-3` in
`github.com/jamon-marcel/oxid.ch`), which went 11→13 + Vue 2→3 on
2026-10-04, itself modelled on **luvo.ch**. Where a decision was made and
verified there, this set reuses it instead of re-deriving it. oxid's
`06-progress.md` is the most useful single file to have open beside this one.

Checked 2026-10-20 against **cra.ch** (`github.com/marceli-to/cra.ch`,
branch `rework/laravel-13-vue-3`, done 2026-10-07). The target stack is the
same as both. Since then this plan also follows cra on the own login,
public JS, the light admin refresh and hosting (Hostpoint).

## Files here

| File | Contents |
|---|---|
| `00-estimate.md` | Scope and numbers |
| `01-inventory.md` | What's in the codebase, measured |
| `02-backend-laravel13.md` | Dependency audit, blockers, step plan |
| `03-frontend-vue3.md` | Admin: package table, `<script setup>` target, step plan |
| `04-open-questions.md` | Decisions taken and what is still open |
| `05-progress.md` | Logbook: what is done, verified, left to do |
| `06-frontend-js.md` | Public site JS: ES modules, own lightbox from cra |
| `07-admin-ui.md` | Light admin refresh, as cra |
| `08-deploy.md` | Deploy and go-live: server checks, production `.env`, mail, order, checks after deploy |

## The 30-second version

- **Laravel 11 is EOL with unpatched CVEs.** `composer audit`: 42 advisories
  across 12 packages. Composer will not resolve the 11.x line any more.
- **13.5–15.75 working days** (`00-estimate.md`).
- **Smaller than oxid on the backend.** Auth is already session + Sanctum
  (admin at `/administration`), so there is no JWT migration. `laravel/ui`
  is replaced by an own login, as oxid/cra. No search engine to replace.
- **Hostpoint**, as cra: PHP 8.3–8.5 available.
- **Two blockers, both own packages:** `marceli-to/image-cache` (→ Glide,
  as in oxid) and `marceli-to/wiretap` (→ removed, decided 2026-10-05).
- **Bigger than oxid in the admin.** 65 `.vue` files / 11.6k LOC (oxid: 59 /
  7k). TinyMCE in 16 forms (oxid: 7), vuedraggable in 16 files, Feather
  icons in ~55.
- **The Vue 2 code is clean** where it matters: no `$listeners`, `$children`,
  `slot-scope`, `$set`/`$delete`, event bus. 21 `$parent` hits in 9 files.

## Ground rules

- Admin goes straight to **`<script setup>` + composables**, in oxid's
  current shape (`lib/http.js`, `lib/notify.js`, `components/ui/`,
  `composables/`). Decided 2026-10-05. No Options API intermediate step.
- Copy from oxid/cra wherever the shape matches; adapt, don't re-invent.
- Backend first, sequenced. Each step leaves the app working.
- No public-site design changes. Public site JS goes to ES modules with
  cra's lightbox (`06`); the admin gets cra's light refresh (`07`).
