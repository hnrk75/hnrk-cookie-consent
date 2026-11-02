# Changelog

All notable changes to HNRK Cookie Consent are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the
plugin uses [Semantic Versioning](https://semver.org/):

- **MAJOR** — changes that can break a site (removed settings, changed cookie format, removed hooks).
- **MINOR** — new features.
- **PATCH** — bug fixes.

## [Unreleased]

## [1.3.0] - 2026-11-02

### Added
- "Manage cookies" in the footer: a theme that declares `add_theme_support( 'hnrk-cookie-consent-footer' )` prints the link itself with `hnrk_cookie_manage_link()`. `hnrk_cookie_manage_trigger()` tells the theme which option is in effect.
- Links that reopen the banner (`#manage-cookies`, the shortcode, `hnrk_cookie_manage_link()`) get `role="button"` and open with the Space key too.

### Changed
- The reopen options are **Floating button** and **In the footer**. The option "None" is removed, so visitors can always change or withdraw their consent (GDPR). Without theme support, "In the footer" is disabled and the floating button is used. Sites set to "None" are moved once to the footer link if the theme supports it, otherwise to the floating button.
- The label setting is shared by the floating button and the footer link.

## [1.2.0] - 2026-11-01

### Added
- Cookie policy page picker in the settings, with an optional custom URL instead.
- `hnrk_cookie_page_url()` returns the URL the banner uses (the selected page, else the custom URL, then the `hnrk_cookie_page_url` filter).

### Changed
- When a theme sets the link through the `hnrk_cookie_page_url` filter (e.g. WP Starter Theme's cookie policy page), the settings show that link as "Set by the theme" with a link to edit the page, and the field cannot be changed.
- No default URL any more: without a page the banner leaves out the link, and the settings show a warning. A saved old default `/cookies/` is ignored unless a page actually lives there.

## [1.1.0] - 2026-11-01

Fixes from the first client site built on the starter template (svenssonsmetropol).

### Added
- Google Consent Mode v2 default: everything is denied in `<head>` (priority 1) before any Google tag loads, with `wait_for_update`; a returning visitor's choice is applied right away. `functionality_storage` and `personalization_storage` follow the Functional category. Filter `hnrk_cookie_consent_mode` turns the default off (e.g. when Consent Mode is set up in Google Tag Manager).
- JavaScript API: the `hnrk:consent` event on `document` (on load and on every change) and `window.hnrkConsent` with `get()`, `has( category )` and `open()`.
- Script blocking: `<script type="text/plain" data-consent="marketing">` runs only once its category is accepted.
- Consent version: when the site turns a category on or off, visitors with an older choice are asked again.

### Changed
- The floating "Manage cookies" button is on by default, so visitors can always change or withdraw their consent (GDPR). Sites that saved the old default (`none`) get it turned on once, the first time an admin opens wp-admin after the update — unless they already offer another way to reopen the banner (the `[hnrk_manage_cookies]` shortcode or a `#manage-cookies` link in content or a menu).
- The Analytics category is off by default.
- The fallback `--color-primary` is `#2a8084` (4.65:1 against the white button text) instead of `#34a0a4` (3.1:1). Only affects sites whose theme does not set `--color-primary`.
- The bottom bar banner is a `region`, not a modal dialog; reopened, the banner is a modal dialog.
- The consent cookie is `Secure` on HTTPS. Declining removes marketing cookies (`_fbp`, `_fbc`, `_gcl_*`, `_uet*` …) as well as analytics cookies.
- The admin script's texts ("Close", "… days") are translatable, and the "Edit info" buttons keep their label after being clicked.
- README: settings are in their own **Cookie Consent** admin menu; new section on using consent in a theme.

### Fixed
- A `%` in the banner text (e.g. "100%") caused a fatal error on every page and on the settings page, because the text was used as a `printf()` format string.

## [1.0.6] - 2026-11-01

### Fixed
- The default banner texts (title, text, category descriptions, "Manage cookies" label) did not follow the site's language: they were not translatable, and saving the settings stored them in one language. Defaults are now translated, and a text that is left as the default is stored empty so it follows the language. Texts an admin has changed are kept as written.
- Sites saved with an earlier version are fixed automatically the next time an admin opens WP admin: stored texts that equal the default (in English or the admin's language) are emptied, so they follow the language again.
- The `[hnrk_manage_cookies]` shortcode and the floating button used a hard-coded Swedish label as fallback. Both now use the "Manage cookies" label from the settings.

## [1.0.5] - 2026-11-01

### Added
- Theme variables for the banner colors: `--hnrk-cookie-bg`, `--hnrk-cookie-text`, `--hnrk-cookie-border`, `--hnrk-cookie-reopen-bg`, `--hnrk-cookie-reopen-text` and `--hnrk-cookie-color-scheme`. A theme maps them to its own colors, so the banner can follow the theme's dark mode. See README.

### Fixed
- The banner background and text did not follow a theme's dark mode (only the buttons did), because they used fixed generic tokens. Without the new variables the banner looks exactly as before.

### Changed
- The committed CSS is a production (minified) build.

## [1.0.4] - 2026-11-01

### Changed
- The plugin has no runtime Composer dependencies any more and no longer loads `vendor/autoload.php`. `vendor/` is not part of the repo or the package.
- Development files (SCSS, build and lint config) are left out of the package Composer installs.
- License header is `GPL-2.0-or-later`, the same as `composer.json`.

### Removed
- The unused `yahnis-elsts/plugin-update-checker` dependency. Sites install and update the plugin with Composer.

### Fixed
- The version in the plugin header matches the git tag again (tag `v1.0.3` had header version 1.0.2).

## [1.0.3]

- Swedish translations.

## [1.0.2]

- Plugin name and package name fixes.

## [1.0.1]

- Version bump.

## [1.0.0]

- Initial version: cookie banner with the categories Necessary, Analytics, Functional and Marketing, and Google Consent Mode v2 support.
