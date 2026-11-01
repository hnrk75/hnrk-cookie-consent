# Changelog

All notable changes to HNRK Cookie Consent are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the
plugin uses [Semantic Versioning](https://semver.org/):

- **MAJOR** — changes that can break a site (removed settings, changed cookie format, removed hooks).
- **MINOR** — new features.
- **PATCH** — bug fixes.

## [Unreleased]

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
