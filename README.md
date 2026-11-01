# HNRK Cookie Consent

Cookie consent banner for WordPress with support for Google Consent Mode v2.

---

## What it does

- Shows a cookie banner with the categories **Necessary**, **Analytics / Statistics**,
  **Functional** and **Marketing**. Only the categories the site uses are shown.
- Stores the visitor's choice in the `hnrk-consent` cookie (lifetime configurable).
- Updates Google Consent Mode v2 (`analytics_storage`, `ad_storage`, `ad_user_data`,
  `ad_personalization`) and removes Google Analytics cookies when analytics is declined.
- A floating **Manage cookies** button lets visitors change their choice.
- Settings under **Settings → Cookie Consent**: banner texts, cookie policy page,
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
  "hnrk75/hnrk-cookie-consent": "1.0.4"
}
```

```bash
composer update hnrk75/hnrk-cookie-consent
```

It is installed in `web/app/plugins/hnrk-cookie-consent/`. Activate it under **Plugins**.

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
