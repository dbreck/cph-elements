# Changelog

All notable changes to CPH Elements are documented here. Versions correspond to GitHub releases (used by the in-plugin updater).

## [1.9.0] — 2026-06-21

### Gallery Slider (element 1.2.0)
- **True infinite loop is now the default.** Previously, advancing past the last slide rewound the track all the way back to the first slide (a visible reverse sweep). The loop now continues seamlessly in the same direction — the first slide enters from the trailing edge — by cloning a full set of slides on each side and silently recentering after a move crosses into the clone buffer. Applies to arrows, autoplay, keyboard, pagination, and drag/swipe.
- **New: Infinite Loop Rewind** (`infinite_rewind`) — checkbox in the Behavior tab (shown when Infinite Loop is on, off by default). Restores the legacy rewind behavior for anyone who prefers it.
- Clones are marked `aria-hidden` and stripped of `data-index`; pagination dots still map 1:1 to the real slides.
- No change when Infinite Loop is off (non-looping clamp behavior is unchanged).

## [1.8.0] — 2026-06-21

### Gallery Slider (element 1.1.0)
- **New: Arrow Layout** (`arrow_layout`) — dropdown in the Navigation tab (shown when Navigation Style is Arrows or Both). **Overlay (Sides)** is the default and keeps the existing behavior (arrows over the left/right edges of the images). **Below Slider (Centered)** places the two arrow buttons in a centered row beneath the carousel.
- **New: Arrow Gap** (`arrow_gap`, default `16px`) — spacing between the two arrow buttons, shown only when Arrow Layout = Below.
- In Below layout, the existing **Arrow Position** value (responsive) controls the space between the slider and the arrow row (margin-top); in Overlay it still controls distance from the edge.
- Implementation: arrows are now wrapped in a `.cph-gallery-slider__nav` container (`display: contents` in overlay mode, so overlay positioning is unchanged); below mode moves clipping/height onto the viewport so the wrapper can grow. Fully backward-compatible — existing sliders default to Overlay.

## [1.7.2] — 2026-06-20

### File Download Card (element 1.2.2)
- **New: Action icon displays on hover** (`icon_on_hover`) — checkbox at the bottom of the Display Options tab, checked by default. When checked, the preview action icon (play / download / search / link arrow) fades in only on hover (existing behavior). Uncheck to keep the icon visible at all times — useful for video cards so visitors can distinguish videos from files at a glance.

## [1.7.1] — 2026-06-17

### File Download Card (element 1.2.1)
- **New: Extra Text** (`extra_text`) — optional text field for Link cards, rendered in the slot where file type/size appears on download cards so link cards line up with file cards in a row. Link mode only.

## [1.7.0] — 2026-06-17

### File Download Card (element 1.2.0)
- **New: Card Type** — `card_type` param toggles the card between **File Download** (existing behavior) and **Link** (links the user to another page or website)
- **New: Link mode fields** (shown only when Card Type = Link):
  - `link_url` text field — replaces the file picker; accepts relative paths (`/residences/`) or full URLs
  - `link_target` — Same Tab / New Tab; applies to both the button and the preview image
  - `is_external` checkbox — adds an outbound (↗) arrow icon to the button and preview-image overlay to signal the link leaves the site (internal links use a → arrow)
- New-tab links get `rel="noopener noreferrer"`; external same-tab links get `rel="noopener"`
- File picker, Preview Image Click Action, and file meta (type • size) now hide in Link mode; FancyBox is no longer enqueued for link cards
- File Download mode is unchanged

## [1.6.0] — 2026-06-08

### Arrow Button (new element 1.0.0)
- **New element** `cph_arrow_button` — pill-shaped outlined arrow link button, rebuilt as a standalone element (self-contained, no longer dependent on Portfolio Grid's `.cph-card__arrow` CSS)
- **Controls:** Link (`vc_link`), accessible label, alignment, pill width, stroke width, color, hover color, and hover effect (slide / grow / none)
- **Spacing & Transform** — reuses Salient's native Row controls (`SalientWPbakeryParamGroups::spacing_group()` + the Row Transform block) for responsive Padding, Margin, and Transform (Translate X/Y, Scale, Rotate) with the device toggle and constrain locks
- **Admin:** dedicated `arrow-button-admin.js` reproduces Salient's `createDeviceGroup()` wrap-and-toggle for this element's panel, since Salient only wires those groups for `vc_row`/`vc_section`/`vc_column`
- Hover color applied via explicit SVG `stroke` (immune to theme `a:hover` rules); responsive spacing emitted as per-instance scoped CSS at Salient's 999px/690px breakpoints

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
