# Changelog

## [3.9.0] - 2026-10-02

### Added
- GitHub Releases automatic updater system with stable theme package selection.
- Redesigned admin dashboard with tab-based and card-based UI.
- Official VETRA color palette tokens and ten-step neutral ramp.
- CI/CD pipeline with GitHub Actions.
- PHP 8.1, 8.2, and 8.3 matrix coverage.

### Changed
- Admin panel now uses VETRA brand colors: `#C67D34`, `#B3803B`, and `#12161A`.
- Frontend CSS uses the shared design-token system.
- Improved RTL-aware layout and interaction states.
- Canonical admin stylesheet is now `assets/css/vetra-admin.css`.

### Removed
- Unused third-party integration branches; supported optional integrations are Elementor and Gravity Forms.

### Fixed
- Corrected GitHub ZIP extraction so updates install into the stable `vetra-portal-theme/` directory.
- Renamed the admin page to `vetra-portal-settings` and removed the Customizer entry points.
- Added the opt-in Private Portal gate and dashboard toggle with explicit off-state handling.
- Theme update recognition in WordPress admin.
- Theme slug consistency across all files and release packages.
