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
- Translated to Swedish (`languages/`).

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
