# Changelog

All notable changes to CPH Elements are documented here. Versions correspond to GitHub releases (used by the in-plugin updater).

## [1.5.0] — 2026-06-05

### File Download Card (element 1.1.0)
- **New: Image Position** — 9-way `object-position` control (`image_position` param) for the cover-fit preview image; Center Center default
- **New: Preview Image Click Action** — `image_click` param: `lightbox` (default) or `download` (ported from the Mira Mar salient-child theme version)
- **New: Video preview support** — MP4/MOV/WEBM/OGG/AVI files get a play-icon overlay and open in a FancyBox video player (ported from theme version)
- **New: Title auto-populate** — admin JS that fills the Title field from the selected filename was missing from the plugin port; restored as `assets/js/file-download-card-admin.js`
- **Fix:** meta line separator rendered as literal `&bull;` text (double-escaped entity); now a literal `•`

### Core
- **New: site-level config** — `wp-content/cph-config.php` is now checked before the plugin-dir copy for the element allow-list. Site-specific, survives plugin updates, and works with symlinked dev installs
- `cph-config-sample.php` element list updated to all 13 current elements

## [1.4.0] — 2026-06-03

### Features
- **Team Grid + Horizontal Scroller:** per-member "Don't link this profile" checkbox; members with no Biography automatically non-clickable
- **Portfolio Grid:** Card Label feature (per-post meta box, per-slot toggles, custom location text); category and status filters now multi-select
- **Stats Bubbles:** Auto Bubble Size mode

### Fixes
- **Stats Grid:** dividers now render correctly for 2, 3, and 4 columns
- **Team popup:** removed doubled paragraph spacing in bio content

## [1.3.2] — 2026-03-27

### Fixes
- Portfolio grid filters: cards with GSAP entrance animations stuck at opacity 0 after filtering
- Loading spinner visibility on card image containers

### New
- Loading spinner overlay on masonry cards and zigzag image panels
- GitHub updater prefers attached .zip assets over zipball for correct directory naming

## [1.0.0] — 2026-01-27

Initial release with 10 WPBakery elements and auto-discovery loader.
