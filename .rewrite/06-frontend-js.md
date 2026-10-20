# Public site JS: vanilla ES modules, own lightbox

Decided 2026-10-20 (D6). Modelled on cra.ch (`07-frontend-js.md` and
"Public site JS" in `06-progress.md` on `rework/laravel-13-vue-3`), which
did the same on 2026-10-07.

## Today — measured 2026-10-20

The modules already use no jQuery. `web/bootstrap.js` is empty,
`window.$` is commented out in `app.js`, and no module calls `$(`.
`jquery`, `bootstrap`, `popper.js` and `lodash` in package.json are dead
weight, not a migration. That makes this smaller than in cra.

`web/app.js` `require()`s 8 modules (562 lines in total, vendor included):

| Module | Lines | Notes |
|---|---|---|
| `fancybox.js` | 35 | `@fancyapps/ui` 5 from npm → own lightbox |
| `project.js` | 98 | |
| `menu.js` | 101 | |
| `worklist.js` | 116 | |
| `contact.js` | 46 | |
| `cv.js` | 40 | |
| `team.js` | 36 | |
| `touch.js` | 26 | check whether its selector matches anything (in cra it didn't) |
| `vhcheck.js` | 1 | already commented out in `app.js` → delete |

Vendored in `web/vendor/`: `fancybox.js` (v3 stub), `scrollTo.js`,
`debounce.js`, `vhcheck.js`. Committed separately:
`public/assets/js/{fancybox,modernizr}.js`. Sass: `web/vendor/_fancybox.scss`
(95 lines of overrides on top of the v5 CSS).

`swiper` ^9 is in package.json but **imported nowhere** (`resources/`,
`app/`, the built `app.js`). Delete it rather than upgrading. If a slider
comes back later, take `swiper ^14` as in oxid.

## Target

- ES modules with `export function init()`, `app.js` imports and calls
  them (as oxid and cra). `data-` hooks.
- Out of package.json: `jquery`, `bootstrap`, `popper.js`, `lodash`,
  `swiper`, `@fancyapps/ui`.
- Delete: `web/bootstrap.js`, `web/vendor/*` (`scrollTo` →
  `scrollIntoView`, `debounce` → small local helper, if still used),
  `modules/vhcheck.js`, `_fancybox.scss`,
  `public/assets/js/{fancybox,modernizr}.js` (after `04` #6).
- Google tag only in production, if present (oxid `cbe136c`).

## Lightbox — reuse cra's

Copy `resources/js/web/modules/lightbox.js` (151 lines) and
`resources/sass/web/components/_lightbox.scss` from cra. It has: Esc and
arrow keys, swipe, scroll lock, focus kept in the dialog and returned to
the thumbnail, German button labels, no loop, no CDN script, no licence
question (fancyBox 3 and 5 are both GPLv3/commercial).

What has to change here:

| | cra | here |
|---|---|---|
| Groups | one group per page, all `[data-lightbox]` in document order | **one group per value**: `data-fancybox="gallery-{slug}"`. The teaser column (`components/teasers/column.blade.php`) can show several projects on one page, and each must stay its own gallery |
| Caption | `data-caption` + "N/M" | the trigger's hidden `<legend>` (innerHTML) if there is one, else `data-caption`; "N/M" **above** the caption, as in today's `caption()` callback |
| Look | fancyBox 3 (white overlay, the site's icons) | fancyBox **5** as configured today: zoom off, no thumbs, toolbar has only close, no hidden scrollbar. Match `_fancybox.scss`, measure against the current build |

Markup: `data-fancybox="gallery-…"` → `data-lightbox="gallery-…"` in
`pages/project/show.blade.php` and `components/teasers/column.blade.php`.

## Verify

As cra (`.rewrite/tools/qa/` there: `shots.js`, `styles.js`, `interact.js`,
`lightbox-measure.js`). Copy the scripts and adapt the URLs:

- computed styles and screenshots (1440 / 390 px) identical to the Mix
  build on prod data
- menu, project page, worklist, team, CV, contact form, lightbox (open,
  next/prev, caption, counter, Esc, swipe, two galleries on one page)
- Chromium and WebKit, no console errors
- bundle size before/after
