# Estimate

## In scope

- Laravel 11 → 13, PHP `^8.2` → `^8.3`, Carbon 2 → 3
- Legacy L8 skeleton → slim `bootstrap/app.php`
- `marceli-to/image-cache` → `league/glide`
- `marceli-to/wiretap` removed (decided 2026-10-05)
- Vue 2.7 → Vue 3, `<script setup>` + composables (decided 2026-10-05)
- vue-router 3 → 4, Vuex deleted
- Laravel Mix → Vite
- Every Vue 2-only package replaced
- TinyMCE → Tiptap (default, see `04-open-questions.md` #3)
- Admin API feature tests, as in oxid
- Own login instead of `laravel/ui` (D5, 2026-10-20)
- Public site JS: ES modules, own lightbox from cra, dead packages out (D6)
- Light admin refresh, as cra (D7)

## Out of scope

- Sass `@import` → `@use` (9.2k LOC). Silenced in Vite, as in oxid.
- Any public-site design change.

## Numbers

| Phase | Days |
|---|---|
| Backend | **4 – 4.5** |
| Admin (Vue 3, `<script setup>`, Vite, Tiptap, light refresh) | **7.5 – 8.75** |
| Public site JS | **1** |
| Tests + QA | **1 – 1.5** |
| **Total** | **13.5 – 15.75** |

Updated 2026-10-20 (was 11.5–13.5): +0.5 own login, +0.5–0.75 admin
refresh, +1 public site JS. The JS is smaller than first thought: the
modules already use no jQuery and the lightbox comes from cra.

### Backend

| Task | Days |
|---|---|
| Delete dead code (filters, orphan configs, dbal) | 0.25 |
| One Composer commit: L13, drop image-cache + wiretap, add Glide | 0.5 |
| Glide routes + `ImageSupport` from oxid, `/img/crop/...` compat, verify against production | 0.75 – 1 |
| `<picture>` + WebP/AVIF in `components/image.blade.php` (if Q2 = yes) | 0.25 |
| Slim skeleton, providers, handler, middleware | 0.5 |
| Own login from cra, drop `laravel/ui` + email verification, `AuthTest` | 0.5 |
| Config diff vs L13, `.env` renames | 0.25 |
| Validation messages (16 form requests) | 0.25 |
| Smoke test every route (181) + CRUD path | 0.5 |

### Admin

| Task | Days |
|---|---|
| Vite: 4 bundles, `@vite` in 5 layouts, alias | 0.5 |
| `app.js`, router 4, `lib/http.js` (replaces vue-axios + interceptors + `ErrorHandling` mixin), `lib/notify.js`, drop Vuex | 0.5 |
| `components/ui/` from oxid: Uploader, SortableList, Lightbox, Notifications, Toggle, Card, ListActions | 0.75 |
| Tiptap: editor from oxid, round-trip check, 16 forms | 1 – 1.25 |
| Image + file modules (upload, crop, edit, actions) | 0.75 |
| Grid + teaser page builders | 1 – 1.25 |
| ~30 CRUD views: one entity end to end, then replicate | 1.75 – 2 |
| Feather → Phosphor (~55 files; mostly falls out of the above) | 0.25 |
| Light refresh as cra (`07-admin-ui.md`) | 0.5 – 0.75 |
| Click-through QA of every admin screen | 0.5 |

### Public site JS

| Task | Days |
|---|---|
| ES modules, drop jQuery/Bootstrap/popper/lodash/swiper/vendor files | 0.25 |
| Lightbox from cra: per-gallery groups, legend caption, fancyBox 5 look | 0.5 |
| QA scripts from cra: styles, screenshots, interactions | 0.25 |

## Why it differs from oxid (14.25–16.25 days there)

| Item | vs oxid | Why |
|---|---|---|
| JWT → Sanctum | −1 | already Sanctum + sessions |
| Own search | −1.25 | no search here |
| Dropzone | −0.5 | 2 files vs 6 |
| `<script setup>` | −0.5 | done directly, no Options API port first |
| TinyMCE forms | +0.25 | 16 vs 7, but the editor itself is copied |
| Admin size | +1 | 65 files / 11.6k LOC vs 59 / 7k |
| Grid + teaser builders | +0.25 | two builders, oxid had one |
| Copying from oxid | − | Glide, Vite, `components/ui`, composables, editor, tests all exist |
