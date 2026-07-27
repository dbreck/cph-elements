# CPH Elements

Custom WPBakery Page Builder elements with an auto-discovery loader. Drop elements into the `elements/` directory and they register themselves — no manual hookup required.

**Version:** 1.13.0
**Requires:** WordPress 6.0+ / PHP 7.4+ / WPBakery Page Builder
**Optional:** Salient theme (enhanced admin UI components)

## Elements

| Element | Shortcode | Description |
|---------|-----------|-------------|
| Portfolio Grid | `cph_portfolio_grid` | Bento or grid layout for portfolio projects with overlay/animation controls |
| Horizontal Scroller | `cph_horizontal_scroller` | Infinite drag/swipe carousel with GSAP-powered physics |
| Team Grid | `cph_team_grid` | Responsive team member grid |
| News Grid | `cph_news_grid` | Blog posts with featured article, category filters, and AJAX load-more |
| Icon Grid | `cph_icon_grid` | Configurable icon grid with 16 built-in presets + custom upload |
| Stats Grid | `cph_stats_grid` | Statistics in a grid with optional dividers |
| Stats Bubbles | `cph_stats_bubbles` | Floating bubble stats with scroll-triggered GSAP animations |
| Stats Photos | `cph_stats_photos` | Statistics paired with photos in a grid |
| Gallery Slider | `cph_gallery_slider` | Center-mode carousel with 3D effects (coverflow, carousel, etc.) |
| File Download Card | `file_download_card` | Downloadable file card with preview image (lightbox/download/video play), metadata display, and crop-position control |
| Testimonials | `cph_testimonials` | Quote slider with dot navigation and auto-rotate |
| Animated Vert Line | `cph_animated_vert_line` | Decorative vertical line with scroll-triggered grow animation |
| Expandable Grid | `cph_expandable_grid` | Accordion grid backed by `cph_grid_item` CPT with per-item borders and expand/collapse detail panels |
| Arrow Button | `cph_arrow_button` | Pill-shaped outlined arrow link with color/hover controls and Salient-native responsive Spacing & Transform (Padding, Margin, Transform) |

### File Download Card parameters

| Param | Values | Default | Notes |
|-------|--------|---------|-------|
| `title` | text | filename | Auto-populates from the selected file's name |
| `image` | attachment ID | — | Preview image |
| `file` | attachment ID | — | The downloadable file (custom `fdc_file_picker` param) |
| `button_text` | text | `Download` | |
| `image_click` | `lightbox` \| `download` | `lightbox` | Video files (MP4/MOV/WEBM/OGG/AVI) always open a FancyBox video player |
| `aspect_ratio` | `4-3` \| `16-9` \| `3-4` | `4-3` | |
| `image_position` | `center-center`, `top-left`, `top-center`, `top-right`, `center-left`, `center-right`, `bottom-left`, `bottom-center`, `bottom-right` | `center-center` | `object-position` crop focus for the cover-fit preview image |
| `title_tag` | `h2`–`h6` | `h3` | |
| `show_meta` | `yes` \| `` | `yes` | File type + size line |
| `border_radius` | `none` \| `sm` \| `md` \| `lg` | `md` | |
| `shadow` / `shadow_strength` / `shadow_color` | — | enabled/medium | |
| `color_scheme` | `light`, `dark`, `accent`, `extra-1/2/3`, `custom` | `light` | Custom scheme exposes bg/text/button color pickers |

## Installation

1. Upload `cph-elements/` to `wp-content/plugins/`
2. Activate through the WordPress admin
3. WPBakery Page Builder must be active — elements appear under the **Clear pH Elements** category

## Configuration

By default all elements are loaded. To limit which elements are active on a given site, copy the sample config to **`wp-content/cph-config.php`** (preferred — site-specific, survives plugin updates and symlinked installs):

```bash
cp wp-content/plugins/cph-elements/cph-config-sample.php wp-content/cph-config.php
```

Then edit it and keep only the element slugs you need:

```php
return array(
    'elements' => array(
        'portfolio-grid',
        'news-grid',
        'gallery-slider',
    ),
);
```

A `cph-config.php` inside the plugin directory also works as a fallback, but the `wp-content/` copy wins when both exist. If neither exists, every element in `elements/` loads automatically.

Only allow-listed elements register their shortcodes **and** enqueue their CSS/JS — on sites that use one or two elements, the allow-list keeps the asset footprint minimal.

## Project Structure

```
cph-elements/
├── cph-elements.php              Main plugin file
├── cph-config-sample.php         Config template
├── elements/                     Auto-discovered elements
│   └── {element-slug}/
│       ├── element.php           Element config (name, shortcode, assets, deps)
│       ├── {slug}-map.php        WPBakery parameter definitions
│       ├── {slug}-render.php     Shortcode render callback
│       ├── {slug}-setup.php      Optional setup (AJAX handlers, CPT registration)
│       ├── assets/
│       │   ├── css/{slug}.css
│       │   └── js/{slug}.js
│       └── partials/             Optional template fragments
└── shared/                       Core framework
    ├── class-cph-element-loader.php   Auto-discovery & registration
    ├── class-cph-gsap.php             GSAP CDN dependency manager
    ├── class-cph-admin.php            WPBakery admin panel assets
    └── assets/
        ├── css/cph-wpbakery-admin.css
        └── js/cph-wpbakery-admin.js
```

## How the Loader Works

1. **Discovery** — scans `elements/` for subdirectories containing an `element.php` config file
2. **Validation** — checks each config for a `shortcode` key and verifies dependencies (WPBakery required, Salient optional)
3. **Allow-list** — if `cph-config.php` exists, only listed element slugs proceed
4. **Registration** — maps shortcodes via `vc_lean_map()`, requires setup/render files, and enqueues CSS/JS assets
5. **GSAP** — collects GSAP module needs across all active elements and enqueues only what's required from CDN (v3.13.0)

Assets load in order: GSAP modules (priority 5) → element JS (priority 60) → element CSS (priority 100).

## Adding a New Element

Create a new directory under `elements/` with an `element.php` that returns a config array:

```php
<?php
// elements/my-element/element.php
return array(
    'name'      => 'My Element',
    'shortcode' => 'cph_my_element',
    'version'   => '1.0.0',
    'category'  => 'Clear pH Elements',

    'files' => array(
        'map'    => __DIR__ . '/my-element-map.php',
        'render' => __DIR__ . '/my-element-render.php',
        // 'setup' => __DIR__ . '/my-element-setup.php', // optional
    ),

    'assets' => array(
        'css' => array(
            array(
                'handle' => 'cph-my-element',
                'file'   => 'assets/css/my-element.css',
            ),
        ),
        'js' => array(
            array(
                'handle' => 'cph-my-element',
                'file'   => 'assets/js/my-element.js',
                'deps'   => array(),
            ),
        ),
    ),

    'gsap' => array(), // e.g. array('core', 'scrolltrigger')

    'requires' => array(
        'wpbakery' => true,
        'salient'  => false,
    ),
);
```

Add the corresponding map, render, and asset files. The loader picks it up automatically on next page load.

### Map file

Define WPBakery parameters in the map file:

```php
<?php
// elements/my-element/my-element-map.php
return array(
    'name'     => 'My Element',
    'base'     => 'cph_my_element',
    'category' => 'Clear pH Elements',
    'icon'     => cph_element_url( 'my-element' ) . '/icon.png',
    'params'   => array(
        array(
            'type'       => 'textfield',
            'heading'    => 'Title',
            'param_name' => 'title',
        ),
    ),
);
```

### Render file

Output the shortcode HTML:

```php
<?php
// elements/my-element/my-element-render.php
function cph_my_element_render( $atts ) {
    $atts = shortcode_atts( array( 'title' => '' ), $atts );
    ob_start();
    ?>
    <div class="cph-my-element">
        <h2><?php echo esc_html( $atts['title'] ); ?></h2>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'cph_my_element', 'cph_my_element_render' );
```

## Responsive Device Groups

Elements can define device-group parameters that provide per-breakpoint controls (desktop / tablet / phone) in the WPBakery editor. The admin JS handles the grouped UI automatically. Declare groups in your element config under `admin_device_groups`.

## Helper Functions

| Function | Returns |
|----------|---------|
| `cph_element_url( $slug )` | URL to an element's directory |
| `cph_element_path( $slug )` | Filesystem path to an element's directory |

## Dependencies

- **WPBakery Page Builder** — required for all elements
- **Salient theme** — optional; provides enhanced param types (`nectar_group_header`, `nectar_range_slider`). Fallback implementations are included when Salient is not active.
- **GSAP 3.13.0** — loaded from CDN only when elements need it. Available modules: `core`, `scrolltrigger`, `draggable`, `inertia`
