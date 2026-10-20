# Decisions and open questions

## Decided

| # | Question | Answer | Date |
|---|---|---|---|
| D1 | `marceli-to/wiretap` (no L13 support) | **Remove.** Only use is `Handler.php`. | 2026-10-05 |
| D2 | Admin code style | **`<script setup>` + composables** from the start, oxid's shape | 2026-10-05 |
| D3 | `marceli-to/image-cache` (no L13 support) | Replace with Glide, as oxid/luvo | 2026-10-05 |
| D4 | 11 → 12 → 13 or direct | Direct to 13 | 2026-10-05 |
| D5 | Keep `laravel/ui`? | **Replace** with an own `AuthController`, as oxid/cra. Drop email verification and password confirmation (see `02`, "Own login") | 2026-10-20 |
| D6 | Public site JS | **In scope.** Vanilla ES modules, own lightbox reused from cra instead of `@fancyapps/ui` 5. `swiper` was to be upgraded, but nothing imports it: removed instead. See `06-frontend-js.md` | 2026-10-20 |
| D7 | Admin visual refresh | **Light refresh, as cra** (`07-admin-ui.md`) | 2026-10-20 |
| D8 | `barryvdh/laravel-debugbar` | **Remove.** Unused, and neither oxid nor cra has it | 2026-10-20 |
| D9 | `guzzlehttp/guzzle`, `nesbot/carbon` pins | **Drop both.** The framework brings them | 2026-10-20 |
| D10 | Hosting | **Hostpoint**, same as cra: PHP 8.3–8.5 available, so the PHP gate (#1) is cleared | 2026-10-20 |
| D11 | Password reset by mail | **Keep** (usage unknown). Mailer `failover`: SMTP, then sendmail, then log | 2026-10-20 |

## Open

### 1. Image driver on production — PHP gate cleared (D10)

Hostpoint has PHP 8.3–8.5, as in cra. Still: make sure the site's web
*and* CLI PHP are actually switched to ≥ 8.3. The driver does not block
anything (`ImageSupport` probes formats at runtime), but record it. SSH
access is in the (gitignored) `INSTALL.txt`. Run on the server:

```
php -r 'echo PHP_VERSION, PHP_EOL;
        echo class_exists("Imagick") ? "imagick: yes" : "imagick: NO", PHP_EOL;
        if (class_exists("Imagick")) {
          echo "webp: ", Imagick::queryFormats("WEBP") ? "yes":"no", PHP_EOL;
          echo "avif: ", Imagick::queryFormats("AVIF") ? "yes":"no", PHP_EOL;
        }
        echo extension_loaded("gd") ? "gd: yes" : "gd: NO", PHP_EOL;'
```

Also `upload_max_filesize`, `post_max_size`, `memory_limit`.

### 2. Serve requested sizes + WebP/AVIF?

oxid/luvo: yes, big page-weight win. Here it is one Blade component
(`components/image.blade.php`). Recommended: yes.

### 3. TinyMCE → Tiptap, or TinyMCE 7 → 8 with `@tinymce/tinymce-vue` 6?

Unlike oxid (TinyMCE 5, EOL), this is **7.2.0 (June 2024)**, self-hosted
and behind on patches. Upgrading is possible. Tiptap is recommended
anyway: the editor already exists in oxid, drops 7.6 MB of vendored files
and the patch treadmill, and the feature set used here is small (see
`03-frontend-vue3.md`). Side effect: saving normalises stored HTML;
editors should be told before go-live.

### 4. Which `image-cache` templates are actually requested?

Grep the production access log for `/img/` and count by template. Only
port what is used.

### 5. Deployment

Hostpoint, as cra (D10): SSH + `git pull`, `composer install --no-dev`,
built assets committed, nothing built on the server.

### 6. Leftovers to confirm before deleting

`public/js/`, `public/mix-manifest.json` (root), `public/assets/js/{fancybox,modernizr}.js`,
`composer.phar`, `config/cors.php`. Check nothing outside the repo (or the
server) uses them.

### 7. Password reset by mail — ANSWERED 2026-10-20 (D11)

Whether anyone uses it is unknown, so **keep it**: forgot/reset, as in cra,
with the reset mail in German (`lang/de.json`, as cra). Mail falls back to
sendmail when no mail account is set up. See `02`, "Mail".

### 8. Snapshot first

Production DB + `storage/` → local before step 3 of the backend plan.
