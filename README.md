# HNRK Cookie Consent

Cookie consent banner for WordPress with support for Google Consent Mode v2.

---

## What it does

- Shows a cookie banner with the categories **Necessary**, **Analytics / Statistics**,
  **Functional** and **Marketing**. Only the categories the site uses are shown.
- Stores the visitor's choice in the `hnrk-consent` cookie (lifetime configurable,
  `Secure` on HTTPS). Visitors are asked again when the site turns a category on or off.
- Google Consent Mode v2: everything is denied by default in `<head>` before any Google
  tag loads, and updated when the visitor chooses (`analytics_storage`, `ad_storage`,
  `ad_user_data`, `ad_personalization`, `functionality_storage`,
  `personalization_storage`). Turn the default off with the `hnrk_cookie_consent_mode`
  filter if Consent Mode is set up in Google Tag Manager instead.
- Removes analytics and marketing cookies (`_ga*`, `_fbp`, `_gcl_*` …) when declined.
- **Manage cookies** lets visitors change or withdraw their choice at any time: a floating
  button, or a link in the theme's footer (see below).
- Settings in the **Cookie Consent** menu in WP admin: banner texts, cookie policy page,
  cookie lifetime, banner position, categories and third-party services.
- Translated to Swedish (`languages/`). The default texts follow the site's language as
  long as they are not changed; a text you edit in the settings is kept as written.

---

## Installation (Bedrock)

Add the repository and require an exact version in the project's `composer.json`:

```json
"repositories": [
  { "type": "vcs", "url": "https://github.com/hnrk75/hnrk-cookie-consent" }
],
"require": {
  "hnrk75/hnrk-cookie-consent": "1.3.0"
}
```

```bash
composer update hnrk75/hnrk-cookie-consent
```

It is installed in `web/app/plugins/hnrk-cookie-consent/`. Activate it under **Plugins**.

---

## "Manage cookies" in a theme's footer

A theme that prints the link itself declares support, and the option **In the footer**
becomes available in the settings:

```php
add_theme_support( 'hnrk-cookie-consent-footer' );

// In footer.php:
if ( function_exists( 'hnrk_cookie_manage_link' ) && 'footer' === hnrk_cookie_manage_trigger() ) {
	echo hnrk_cookie_manage_link();
}
```

Without that support the floating button is always used, so a site is never left without
a way to reopen the banner.

---

## Using consent in a theme or plugin

The banner only manages the choice. Scripts and embeds that need consent must ask for it:

**Block a script until its category is accepted** — set `type="text/plain"` and
`data-consent` to the category (`analytics`, `functional`, `marketing`, `thirdparty`):

```html
<script type="text/plain" data-consent="marketing" src="https://example.com/pixel.js"></script>
```

It runs as soon as the category is accepted, on this page load or later.

**React in JavaScript** — the `hnrk:consent` event fires on page load (when a choice
exists) and every time the visitor changes it:

```js
document.addEventListener('hnrk:consent', (e) => {
  if (e.detail.functional) loadMap();
});

window.hnrkConsent.has('analytics'); // true / false
window.hnrkConsent.get();            // { necessary, analytics, functional, … } or null
window.hnrkConsent.open();           // open the banner, e.g. from your own link
```

---

## Colors and dark mode

The plugin has no dark mode of its own — it cannot know how a theme switches modes.
Instead the banner reads these variables, which a theme maps to its own colors. Each
falls back to a generic token, so sites that set none of them look as before.

| Variable | Used for | Fallback |
|----------|----------|----------|
| `--hnrk-cookie-bg` | Banner background | `--color-white` |
| `--hnrk-cookie-text` | Banner text and title | `--color-dark` |
| `--hnrk-cookie-border` | Banner border and dividers | `--color-gray-light` |
| `--hnrk-cookie-reopen-bg` | "Manage cookies" button background | `--color-dark` |
| `--hnrk-cookie-reopen-text` | "Manage cookies" button text | `--color-white` |
| `--hnrk-cookie-color-scheme` | Native controls (checkboxes, scrollbar): `light`, `dark` or `normal` | `normal` |

Buttons and links use `--color-primary`, and category descriptions are the text color at
75 % opacity, so they follow automatically.

Example — a theme whose own variables already switch in dark mode:

```scss
:root {
  --hnrk-cookie-bg: var(--color-bg);
  --hnrk-cookie-text: var(--color-text);
}

html[data-theme='dark'] {
  --hnrk-cookie-color-scheme: dark;
}
```

---

## Development

```bash
composer install   # PHPCS (dev only)
npm install
npm run dev        # watch SCSS/JS
npm run build      # production build — the built CSS/JS are committed
npm run lint       # CSS, JS and PHP
```

---

## Releasing a new version

Tags are created automatically by a GitHub Action from the version in the plugin header.

1. Work on `dev`. Add a line under **Unreleased** in `CHANGELOG.md`.
2. Run `npm run build` so the committed CSS/JS are up to date.
3. Update `Version:` in the header of `hnrk-cookie-consent.php`.
4. In `CHANGELOG.md`, rename **Unreleased** to `[x.y.z] - YYYY-MM-DD` and add a new empty
   **Unreleased** above it.
5. Commit on `dev` and push, then PR `dev` → `main` with **Create a merge commit**.
6. The action tags `vx.y.z` on `main`. Never create tags by hand — the tag must match the header.
7. On each site: change the version in `composer.json` and run
   `composer update hnrk75/hnrk-cookie-consent`.

---

## License

GPL-2.0-or-later.
