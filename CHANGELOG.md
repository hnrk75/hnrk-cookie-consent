# Changelog

All notable changes to HNRK Cookie Consent are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the
plugin uses [Semantic Versioning](https://semver.org/):

- **MAJOR** — changes that can break a site (removed settings, changed cookie format, removed hooks).
- **MINOR** — new features.
- **PATCH** — bug fixes.

## [Unreleased]

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
