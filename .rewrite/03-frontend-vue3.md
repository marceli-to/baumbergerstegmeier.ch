# Admin: Vue 2.7 → Vue 3 (`<script setup>`)

Scope: `resources/js/cms/`. Target shape is oxid's
`resources/js/backend/` as it is now on `rework/laravel-13-vue-3`:

```
lib/         http.js  notify.js  images.js  utils.js
composables/ useListing  useOrder  useResourceForm  useImages  useFiles  useImageLibrary  useEscape
components/ui/  Uploader  SortableList  Lightbox  Notifications  Toggle  Card  Tabs
                ListActions  LoadingIndicator  FormFooter  LabelRequired  editor/
views/<entity>/ Index.vue  Form.vue
```

Copy these first, then port views onto them. Decided 2026-10-05: straight to
`<script setup>`, no Options API port first (oxid did both and threw the
first away).

## Vue 2 patterns — measured

| Pattern | Hits |
|---|---|
| `$listeners`, `$children`, `$scopedSlots`, `slot-scope`, `slot=` | 0 |
| `$set(` / `$delete(` / `$root` / event bus | 0 |
| `$on(` | 6 — all `window.intercepted.$on('response:…')` in `mixins/ErrorHandling.js` |
| `.sync` | 19 in 15 files — `v-bind:publish.sync` → `v-model:publish` |
| `$parent` | 21 in 9 files — all in `modules/images`, `modules/files`, `team/cvCategory/components/CategoryWidget.vue` |
| `mixins:` | 58 files → composables |
| `Vue.filter` | 1 (`truncate`) → `lib/utils.js` |
| `$store` | 3, `App.vue` only |
| `$notify` | 93 in 44 files → `notify()` from `lib/notify.js` |
| `beforeDestroy` | 1 → `onBeforeUnmount` |

## Package table

| Package | Files | Replace with |
|---|---|---|
| `vue` 2.7, `vue-template-compiler`, `vue-loader` | — | `vue` 3, `@vitejs/plugin-vue` |
| `vue-router` 3 | 10 route files | `vue-router` 4, `createWebHistory('/administration')` (check base) |
| `vuex` | `config/store.js` | delete; `user` name → small reactive module or fetched in `App.vue` |
| `vue-axios`, `vue-axios-interceptors` | `app.js`, `ErrorHandling.js` | `lib/http.js` (one axios instance, one response interceptor) |
| `vue-notification` | 44 files | `lib/notify.js` + `components/ui/Notifications.vue` |
| `vue2-dropzone` | `modules/images/components/Upload.vue`, `modules/files/components/Upload.vue` | `components/ui/Uploader.vue` (per-file progress) |
| `vuedraggable` 2 | 16 | `sortablejs` via `components/ui/SortableList.vue` |
| `@tinymce/tinymce-vue` 3 + self-hosted TinyMCE 7.2.0 | `config/tiny.js` + 16 forms | Tiptap 3, `components/ui/editor/` (see below) |
| `vue-advanced-cropper` 0.16 | `modules/images/components/Edit.vue` | `^2` |
| `vue-feather-icons` | ~55 | `@phosphor-icons/vue` |
| `vue-moment`, `moment` | `mixins/DateTime.js` | plain `Intl.DateTimeFormat` / small helper |
| `lodash` (`window._` in `cms/bootstrap.js`) | 0 uses of `_.` | delete with `cms/bootstrap.js`; CSRF/axios setup moves into `lib/http.js` |
| `jquery`, `bootstrap`, `popper.js` | 0 (public JS already vanilla) | delete (`06-frontend-js.md`) |
| `swiper` ^9, `@fancyapps/ui` 5 | 0 / public lightbox | delete; own lightbox from cra (`06-frontend-js.md`) |
| `cleave.js`, `vue-the-mask`, `vuejs-datepicker`, `nth-check`, `raw-loader` | 0 | delete |
| `laravel-mix`, `sass-loader`, `resolve-url-loader`, `postcss-loader` | | `vite`, `laravel-vite-plugin`, `sass` |

## TinyMCE → Tiptap

Current config (`config/tiny.js`): bold, bullet list, link (+ class
`icon-arrow-right-up`, link list), superscript, remove format, H2/H3/H4,
inline `span.no-word-break`, source code view, paste as text.

oxid's editor has bold, lists, link, superscript, `noWrap`, `div`, a link
dialog. Needed on top / differently here:

- headings H2–H4
- `span.no-word-break` (class, not oxid's inline `white-space` style) —
  adapt `noWrap.js`
- link class `icon-arrow-right-up` — a toggle in `LinkDialog`
- link list from `GET /api/linklist` (the TinyMCE config points at
  `/page-links`, which does not exist)

**Round-trip before switching:** export every stored rich-text value, run
oxid's `tools/tiptap-roundtrip.mjs` with this editor config, diff visible
text, links, headings, lists. Then delete `public/assets/js/cms/tinymce`
and `_tinymce` (7.6 MB) and the `<script>` in `layout/authenticated.blade.php`.

## Vite

`vite.config.js` from oxid. Four inputs:
`resources/sass/web/app.scss`, `resources/js/web/app.js`,
`resources/js/cms/app.js`, `resources/sass/cms/app.scss`.
`@` alias → `resources/js/cms`. `css.preprocessorOptions.scss.silenceDeprecations: ['import']`.
Built assets committed in `public/build/`, as in oxid (nothing built on the
server). Fonts/images referenced from Sass by absolute `/assets/...` paths
stay in `public/assets/`.

Blade: 6 `mix()` calls → `@vite([...])` in
`layout/{web,gallery,guest,authenticated}.blade.php`, `layout/partials/head.blade.php`.

The public site's JS (`resources/js/web/`): ES modules and own lightbox,
see `06-frontend-js.md` (D6). It needs no jQuery global.

Admin look: light refresh as cra, see `07-admin-ui.md` (D7).

## Step plan

1. Vite for both sides, still Vue 2 on the admin? **No** — `@vitejs/plugin-vue2`
   would be throwaway. Do the public site on Vite first (one commit), then
   the admin on Vite + Vue 3 together.
2. Copy oxid's `lib/`, `composables/`, `components/ui/` (incl. editor).
   Adapt `lib/http.js` to this API's prefixes.
3. `app.js`, `App.vue`, router 4, `PageHeader`, errors views.
4. **One entity end to end** (suggest `award`: Index with sorting, Form with
   editor and publish toggle). Review. Then replicate across the
   `views/pages/*` CRUD screens.
5. `modules/images` + `modules/files` (upload, edit, crop, actions —
   the `$parent` cluster).
6. `modules/galleries`, `links`, `videos`.
7. `modules/grid` + `modules/teaser` builders, last.
8. Tiptap round-trip, then remove TinyMCE.
9. Remove dead npm packages; `npm run build`; commit the build.
10. Click-through QA, every screen: list, create, edit, delete, reorder,
    upload, crop, editor, validation errors, publish toggle.

## Screens to QA

Homepage (articles, teasers), projects (projects, states, categories,
landings, grid), team (team, employees, employee categories, CVs, CV
categories), publications, awards, jobs (page, articles), profile, contact,
image + file libraries, login/logout, password reset.
