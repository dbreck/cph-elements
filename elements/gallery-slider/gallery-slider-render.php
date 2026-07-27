<?php
/**
 * CPH Gallery Slider - Shortcode Rendering
 *
 * Renders the gallery slider element with center-mode carousel and 3D effects.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the left arrow SVG (pill shape with thin arrow pointing left).
 *
 * @since 1.0.0
 *
 * @return string SVG markup.
 */
function cph_gallery_slider_arrow_left() {
	return '<svg viewBox="0 0 140 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<rect x="0.5" y="0.5" width="139" height="49" rx="24.5" stroke="currentColor" stroke-width="1"/>
		<path d="M95 25H45M45 25L51 30M45 25L51 20" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>';
}

/**
 * Get the right arrow SVG (pill shape with thin arrow pointing right).
 *
 * @since 1.0.0
 *
 * @return string SVG markup.
 */
function cph_gallery_slider_arrow_right() {
	return '<svg viewBox="0 0 140 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<rect x="0.5" y="0.5" width="139" height="49" rx="24.5" stroke="currentColor" stroke-width="1"/>
		<path d="M45 25H95M95 25L89 20M95 25L89 30" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>';
}

/**
 * Get circle arrow SVGs.
 *
 * @since 1.0.0
 *
 * @param string $direction Either 'left' or 'right'.
 * @return string SVG markup.
 */
function cph_gallery_slider_arrow_circle( $direction = 'right' ) {
	if ( 'left' === $direction ) {
		return '<svg viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
			<circle cx="25" cy="25" r="24" stroke="currentColor" stroke-width="1"/>
			<path d="M30 17L22 25L30 33" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>';
	}
	return '<svg viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<circle cx="25" cy="25" r="24" stroke="currentColor" stroke-width="1"/>
		<path d="M20 17L28 25L20 33" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>';
}

/**
 * Get minimal arrow SVGs (no border).
 *
 * @since 1.0.0
 *
 * @param string $direction Either 'left' or 'right'.
 * @return string SVG markup.
 */
function cph_gallery_slider_arrow_minimal( $direction = 'right' ) {
	if ( 'left' === $direction ) {
		return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
			<path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>';
	}
	return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>';
}

/**
 * Get the pause icon SVG (circle outline + two bars).
 *
 * Decorative — the accessible name lives on the button's aria-label.
 *
 * @since 1.4.0
 *
 * @return string SVG markup.
 */
function cph_gallery_slider_icon_pause() {
	return '<svg class="cph-gallery-slider__playpause-icon cph-gallery-slider__playpause-icon--pause" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<circle cx="25" cy="25" r="24" stroke="currentColor" stroke-width="1"/>
		<rect x="19" y="17" width="4" height="16" rx="1"/>
		<rect x="27" y="17" width="4" height="16" rx="1"/>
	</svg>';
}

/**
 * Get the play icon SVG (circle outline + triangle).
 *
 * Decorative — the accessible name lives on the button's aria-label.
 *
 * @since 1.4.0
 *
 * @return string SVG markup.
 */
function cph_gallery_slider_icon_play() {
	return '<svg class="cph-gallery-slider__playpause-icon cph-gallery-slider__playpause-icon--play" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<circle cx="25" cy="25" r="24" stroke="currentColor" stroke-width="1"/>
		<path d="M20 16.5L34 25L20 33.5V16.5Z"/>
	</svg>';
}

/**
 * Get images from the Media Gallery.
 *
 * @since 1.0.0
 *
 * @param string $images_string Comma-separated attachment IDs.
 * @return array Array of image data.
 */
function cph_gallery_slider_get_gallery_images( $images_string ) {
	if ( empty( $images_string ) ) {
		return array();
	}

	$image_ids = explode( ',', $images_string );
	$images    = array();

	foreach ( $image_ids as $id ) {
		$id = intval( trim( $id ) );
		if ( $id <= 0 ) {
			continue;
		}

		$url     = wp_get_attachment_image_url( $id, 'full' );
		$alt     = get_post_meta( $id, '_wp_attachment_image_alt', true );
		$caption = wp_get_attachment_caption( $id );

		if ( $url ) {
			$images[] = array(
				'url'     => $url,
				'alt'     => $alt ? $alt : '',
				'caption' => $caption ? $caption : '',
			);
		}
	}

	return $images;
}

/**
 * Get images from a Custom Post Type.
 *
 * @since 1.0.0
 *
 * @param string $post_type      Post type slug.
 * @param int    $posts_per_page Number of posts to retrieve.
 * @param string $taxonomy_slug  Optional taxonomy term slug.
 * @return array Array of image data.
 */
function cph_gallery_slider_get_cpt_images( $post_type, $posts_per_page, $taxonomy_slug = '' ) {
	$query_args = array(
		'post_type'      => $post_type,
		'posts_per_page' => intval( $posts_per_page ),
		'post_status'    => 'publish',
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
	);

	// Add taxonomy filter if provided.
	if ( ! empty( $taxonomy_slug ) ) {
		// Determine taxonomy based on post type.
		$taxonomy = 'category';
		if ( 'portfolio' === $post_type ) {
			$taxonomy = 'project-type';
		}

		$query_args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $taxonomy_slug,
			),
		);
	}

	$query  = new WP_Query( $query_args );
	$images = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$thumb_id  = get_post_thumbnail_id( get_the_ID() );
			$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
			$caption   = $thumb_id ? wp_get_attachment_caption( $thumb_id ) : '';

			if ( $thumb_url ) {
				$images[] = array(
					'url'     => $thumb_url,
					'alt'     => get_the_title(),
					'caption' => $caption ? $caption : '',
				);
			}
		}
		wp_reset_postdata();
	}

	return $images;
}

/**
 * Render the gallery slider shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_gallery_slider_shortcode( $atts ) {
	// Extract attributes with defaults.
	$atts = shortcode_atts(
		array(
			// Content.
			'source'                       => 'gallery',
			'images'                       => '',
			'post_type'                    => 'portfolio',
			'posts_per_page'               => '6',
			'taxonomy_slug'                => '',
			// Captions.
			'show_caption'                 => '',
			'caption_placement'            => 'below',
			'caption_align'                => 'left',
			'caption_tag'                  => 'p',
			'caption_color'                => '',
			'caption_font_size'            => '',
			'caption_offset'               => '',
			// Layout.
			'height'                       => '600px',
			'height_tablet'                => '',
			'height_phone'                 => '',
			'slide_width'                  => '60%',
			'slide_width_tablet'           => '',
			'slide_width_phone'            => '',
			'slide_radius'                 => '25px',
			'slide_radius_tablet'          => '',
			'slide_radius_phone'           => '',
			'image_position'               => 'center center',
			'slide_gap'                    => '40px',
			'slide_gap_tablet'             => '',
			'slide_gap_phone'              => '',
			// Animation.
			'effect_style'                 => 'classic',
			'easing'                       => 'power2.out',
			'perspective'                  => '1000px',
			'rotate_y'                     => '45',
			'z_offset'                     => '-200',
			'side_scale'                   => '0.75',
			'side_opacity'                 => '0.6',
			'enable_shadow'                => '',
			'enable_blur'                  => '',
			'blur_amount'                  => '4px',
			// Behavior.
			'start_slide'                  => '1',
			'infinite'                     => 'yes',
			'infinite_rewind'              => '',
			'draggable'                    => 'yes',
			'autoplay'                     => '',
			'autoplay_speed'               => '5',
			'pause_on_hover'               => 'yes',
			'transition_duration'          => '600',
			// Navigation.
			'nav_type'                     => 'arrows',
			'show_arrows'                  => 'yes', // Legacy support.
			'arrow_style'                  => 'pill',
			'arrow_layout'                 => 'overlay',
			'arrow_gap'                    => '16px',
			'arrow_size'                   => '',
			'arrow_size_tablet'            => '',
			'arrow_size_phone'             => '',
			'arrow_offset'                 => '30px',
			'arrow_offset_tablet'          => '',
			'arrow_offset_phone'           => '',
			// Arrow default state.
			'arrow_outline_color'          => '#ffffff',
			'arrow_outline_thickness'      => '1px',
			'arrow_bg_color'               => '',
			'arrow_color'                  => '#ffffff',
			'arrow_thickness'              => '1px',
			// Arrow hover state.
			'arrow_hover_outline_color'    => '',
			'arrow_hover_outline_thickness' => '',
			'arrow_hover_bg_color'         => '',
			'arrow_hover_color'            => '',
			'arrow_hover_thickness'        => '',
			// Pagination.
			'dot_size'                     => '12px',
			'dot_size_tablet'              => '',
			'dot_size_phone'               => '',
			'dot_gap'                      => '10px',
			'dot_gap_tablet'               => '',
			'dot_gap_phone'                => '',
			'dot_outline_color'            => '#ffffff',
			'dot_fill_color'               => '#ffffff',
			'pagination_offset'            => '20px',
			'pagination_offset_tablet'     => '',
			'pagination_offset_phone'      => '',
			// Accessibility.
			'aria_label'                   => '',
			// Extra.
			'el_class'                     => '',
		),
		$atts,
		'cph_gallery_slider'
	);

	// Get images based on source.
	$images = array();
	if ( 'gallery' === $atts['source'] ) {
		$images = cph_gallery_slider_get_gallery_images( $atts['images'] );
	} else {
		$images = cph_gallery_slider_get_cpt_images(
			$atts['post_type'],
			$atts['posts_per_page'],
			$atts['taxonomy_slug']
		);
	}

	// Return early if no images.
	if ( empty( $images ) || count( $images ) < 2 ) {
		return '<p class="cph-gallery-slider-empty">' . esc_html__( 'Please add at least 2 images to the gallery slider.', 'cph-elements' ) . '</p>';
	}

	// Generate unique ID.
	static $instance_id = 0;
	++$instance_id;
	$slider_id = 'cph-gallery-slider-' . $instance_id;

	// Build wrapper classes.
	$wrapper_classes = array( 'cph-gallery-slider' );
	$effect_style    = ! empty( $atts['effect_style'] ) ? $atts['effect_style'] : 'classic';
	if ( 'flat' !== $effect_style ) {
		$wrapper_classes[] = 'cph-gallery-slider--3d';
	}
	$wrapper_classes[] = 'cph-gallery-slider--effect-' . esc_attr( $effect_style );
	if ( ! empty( $atts['el_class'] ) ) {
		$wrapper_classes[] = esc_attr( $atts['el_class'] );
	}
	$wrapper_classes[] = 'cph-gallery-slider--arrows-' . esc_attr( $atts['arrow_style'] );
	$arrow_layout      = ( 'below' === $atts['arrow_layout'] ) ? 'below' : 'overlay';
	$wrapper_classes[] = 'cph-gallery-slider--nav-' . $arrow_layout;

	// Captions.
	$show_caption      = ( 'yes' === $atts['show_caption'] );
	$caption_placement = ( 'overlay' === $atts['caption_placement'] ) ? 'overlay' : 'below';
	$caption_align     = in_array( $atts['caption_align'], array( 'left', 'center', 'right' ), true ) ? $atts['caption_align'] : 'left';
	$caption_tag       = in_array( $atts['caption_tag'], array( 'p', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ? $atts['caption_tag'] : 'p';
	if ( $show_caption ) {
		$wrapper_classes[] = 'cph-gallery-slider--captions';
		$wrapper_classes[] = 'cph-gallery-slider--caption-' . $caption_placement;
	}

	// Build data attributes for JS.
	$start_slide = max( 0, intval( $atts['start_slide'] ) - 1 ); // Convert 1-based to 0-based index.
	$easing      = ! empty( $atts['easing'] ) ? $atts['easing'] : 'power2.out';
	$data_attrs  = array(
		'start-slide'         => $start_slide,
		'infinite'            => 'yes' === $atts['infinite'] ? 'true' : 'false',
		'rewind'              => 'yes' === $atts['infinite_rewind'] ? 'true' : 'false',
		'autoplay'            => 'yes' === $atts['autoplay'] ? 'true' : 'false',
		'autoplay-speed'      => intval( $atts['autoplay_speed'] ) * 1000,
		'pause-on-hover'      => 'yes' === $atts['pause_on_hover'] ? 'true' : 'false',
		'transition'          => intval( $atts['transition_duration'] ),
		'draggable'           => 'yes' === $atts['draggable'] ? 'true' : 'false',
		'effect-style'        => $effect_style,
		'easing'              => $easing,
		'side-scale'          => floatval( $atts['side_scale'] ),
		'side-opacity'        => floatval( $atts['side_opacity'] ),
		'rotate-y'            => floatval( $atts['rotate_y'] ),
		'z-offset'            => floatval( $atts['z_offset'] ),
		'enable-shadow'       => 'yes' === $atts['enable_shadow'] ? 'true' : 'false',
		'enable-blur'         => 'yes' === $atts['enable_blur'] ? 'true' : 'false',
		'blur-amount'         => floatval( str_replace( 'px', '', $atts['blur_amount'] ) ),
	);

	// Sanitize values with fallbacks for empty strings.
	$height              = ! empty( $atts['height'] ) ? sanitize_text_field( $atts['height'] ) : '600px';
	$height_tablet       = sanitize_text_field( $atts['height_tablet'] );
	$height_phone        = sanitize_text_field( $atts['height_phone'] );
	$slide_width         = ! empty( $atts['slide_width'] ) ? sanitize_text_field( $atts['slide_width'] ) : '60%';
	$slide_width_tablet  = sanitize_text_field( $atts['slide_width_tablet'] );
	$slide_width_phone   = sanitize_text_field( $atts['slide_width_phone'] );
	$slide_radius        = ! empty( $atts['slide_radius'] ) ? sanitize_text_field( $atts['slide_radius'] ) : '25px';
	$image_position      = ! empty( $atts['image_position'] ) ? sanitize_text_field( $atts['image_position'] ) : 'center center';
	$slide_gap           = ! empty( $atts['slide_gap'] ) ? sanitize_text_field( $atts['slide_gap'] ) : '40px';
	$slide_gap_tablet    = sanitize_text_field( $atts['slide_gap_tablet'] );
	$slide_gap_phone     = sanitize_text_field( $atts['slide_gap_phone'] );
	$side_scale          = ! empty( $atts['side_scale'] ) ? floatval( $atts['side_scale'] ) : 0.75;
	$side_opacity        = ! empty( $atts['side_opacity'] ) ? floatval( $atts['side_opacity'] ) : 0.6;
	$arrow_offset        = ! empty( $atts['arrow_offset'] ) ? sanitize_text_field( $atts['arrow_offset'] ) : '30px';
	$arrow_gap           = ! empty( $atts['arrow_gap'] ) ? sanitize_text_field( $atts['arrow_gap'] ) : '16px';
	$arrow_size          = sanitize_text_field( $atts['arrow_size'] );
	$dot_size            = ! empty( $atts['dot_size'] ) ? sanitize_text_field( $atts['dot_size'] ) : '12px';
	$dot_gap             = ! empty( $atts['dot_gap'] ) ? sanitize_text_field( $atts['dot_gap'] ) : '10px';
	$pagination_offset   = ! empty( $atts['pagination_offset'] ) ? sanitize_text_field( $atts['pagination_offset'] ) : '20px';

	// Arrow styling.
	$arrow_outline_color     = ! empty( $atts['arrow_outline_color'] ) ? sanitize_text_field( $atts['arrow_outline_color'] ) : '#ffffff';
	$arrow_outline_thickness = ! empty( $atts['arrow_outline_thickness'] ) ? sanitize_text_field( $atts['arrow_outline_thickness'] ) : '1px';
	$arrow_bg_color          = sanitize_text_field( $atts['arrow_bg_color'] );
	$arrow_color             = ! empty( $atts['arrow_color'] ) ? sanitize_text_field( $atts['arrow_color'] ) : '#ffffff';
	$arrow_thickness         = ! empty( $atts['arrow_thickness'] ) ? sanitize_text_field( $atts['arrow_thickness'] ) : '1px';

	// Arrow hover styling (use defaults if empty).
	$arrow_hover_outline_color     = sanitize_text_field( $atts['arrow_hover_outline_color'] );
	$arrow_hover_outline_thickness = sanitize_text_field( $atts['arrow_hover_outline_thickness'] );
	$arrow_hover_bg_color          = sanitize_text_field( $atts['arrow_hover_bg_color'] );
	$arrow_hover_color             = sanitize_text_field( $atts['arrow_hover_color'] );
	$arrow_hover_thickness         = sanitize_text_field( $atts['arrow_hover_thickness'] );

	// Animation settings.
	$perspective = ! empty( $atts['perspective'] ) ? sanitize_text_field( $atts['perspective'] ) : '1000px';

	// Build desktop CSS custom properties.
	$desktop_vars = array(
		'--slider-height: ' . esc_attr( $height ),
		'--slide-width: ' . esc_attr( $slide_width ),
		'--slide-radius: ' . esc_attr( $slide_radius ),
		'--slide-gap: ' . esc_attr( $slide_gap ),
		'--side-scale: ' . esc_attr( $side_scale ),
		'--side-opacity: ' . esc_attr( $side_opacity ),
		'--arrow-offset: ' . esc_attr( $arrow_offset ),
		'--arrow-gap: ' . esc_attr( $arrow_gap ),
		// Exposed so the pause/play button can inherit the arrow color scheme.
		// The arrows themselves are still coloured by the explicit rules below.
		'--arrow-color: ' . esc_attr( $arrow_color ),
		'--arrow-outline-color: ' . esc_attr( $arrow_outline_color ),
		'--perspective: ' . esc_attr( $perspective ),
		'--dot-size: ' . esc_attr( $dot_size ),
		'--dot-gap: ' . esc_attr( $dot_gap ),
		'--pagination-offset: ' . esc_attr( $pagination_offset ),
	);

	// Only emit --arrow-size when set so each shape's CSS default (and the
	// responsive defaults) apply when left blank.
	if ( '' !== $arrow_size ) {
		$desktop_vars[] = '--arrow-size: ' . esc_attr( $arrow_size );
	}

	// Only emit --arrow-bg-color when set so the pause/play button's circle
	// stays transparent by default (matching the arrows).
	if ( '' !== $arrow_bg_color ) {
		$desktop_vars[] = '--arrow-bg-color: ' . esc_attr( $arrow_bg_color );
	}

	// Caption vars/rules — only emitted when captions are on and a value is set,
	// so the CSS defaults (10px below / 20px overlay, inherited color) apply.
	if ( $show_caption ) {
		$caption_offset = sanitize_text_field( $atts['caption_offset'] );
		if ( '' !== $caption_offset ) {
			$desktop_vars[] = '--caption-offset: ' . esc_attr( $caption_offset );
		}
	}
	$element_css = '#' . esc_attr( $slider_id ) . ' { ' . implode( '; ', $desktop_vars ) . '; }';

	if ( $show_caption ) {
		$caption_color     = sanitize_text_field( $atts['caption_color'] );
		$caption_font_size = sanitize_text_field( $atts['caption_font_size'] );
		$caption_rules     = array();
		if ( '' !== $caption_color ) {
			$caption_rules[] = 'color: ' . esc_attr( $caption_color );
		}
		if ( '' !== $caption_font_size ) {
			$caption_rules[] = 'font-size: ' . esc_attr( $caption_font_size );
		}
		if ( ! empty( $caption_rules ) ) {
			$element_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__caption { ' . implode( '; ', $caption_rules ) . '; }';
		}
	}

	// Image position CSS.
	$element_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__slide img { ';
	$element_css .= 'object-position: ' . esc_attr( $image_position ) . '; ';
	$element_css .= '}';

	// Arrow default state CSS.
	$arrow_css = '#' . esc_attr( $slider_id ) . ' .cph-gallery-slider__arrow { ';
	$arrow_css .= 'background: ' . ( ! empty( $arrow_bg_color ) ? esc_attr( $arrow_bg_color ) : 'transparent' ) . '; ';
	$arrow_css .= '}';

	$arrow_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__arrow svg rect,';
	$arrow_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__arrow svg circle { ';
	$arrow_css .= 'stroke: ' . esc_attr( $arrow_outline_color ) . '; ';
	$arrow_css .= 'stroke-width: ' . esc_attr( str_replace( 'px', '', $arrow_outline_thickness ) ) . '; ';
	if ( ! empty( $arrow_bg_color ) ) {
		$arrow_css .= 'fill: ' . esc_attr( $arrow_bg_color ) . '; ';
	}
	$arrow_css .= '}';

	$arrow_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__arrow svg path { ';
	$arrow_css .= 'stroke: ' . esc_attr( $arrow_color ) . '; ';
	$arrow_css .= 'stroke-width: ' . esc_attr( str_replace( 'px', '', $arrow_thickness ) ) . '; ';
	$arrow_css .= '}';

	// Arrow hover state CSS.
	$hover_outline_color     = ! empty( $arrow_hover_outline_color ) ? $arrow_hover_outline_color : $arrow_outline_color;
	$hover_outline_thickness = ! empty( $arrow_hover_outline_thickness ) ? $arrow_hover_outline_thickness : $arrow_outline_thickness;
	$hover_bg_color          = ! empty( $arrow_hover_bg_color ) ? $arrow_hover_bg_color : $arrow_bg_color;
	$hover_arrow_color       = ! empty( $arrow_hover_color ) ? $arrow_hover_color : $arrow_color;
	$hover_arrow_thickness   = ! empty( $arrow_hover_thickness ) ? $arrow_hover_thickness : $arrow_thickness;

	$arrow_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__arrow:hover { ';
	$arrow_css .= 'background: ' . ( ! empty( $hover_bg_color ) ? esc_attr( $hover_bg_color ) : 'transparent' ) . '; ';
	$arrow_css .= '}';

	$arrow_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__arrow:hover svg rect,';
	$arrow_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__arrow:hover svg circle { ';
	$arrow_css .= 'stroke: ' . esc_attr( $hover_outline_color ) . '; ';
	$arrow_css .= 'stroke-width: ' . esc_attr( str_replace( 'px', '', $hover_outline_thickness ) ) . '; ';
	if ( ! empty( $hover_bg_color ) ) {
		$arrow_css .= 'fill: ' . esc_attr( $hover_bg_color ) . '; ';
	}
	$arrow_css .= '}';

	$arrow_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__arrow:hover svg path { ';
	$arrow_css .= 'stroke: ' . esc_attr( $hover_arrow_color ) . '; ';
	$arrow_css .= 'stroke-width: ' . esc_attr( str_replace( 'px', '', $hover_arrow_thickness ) ) . '; ';
	$arrow_css .= '}';

	$element_css .= ' ' . $arrow_css;

	// Responsive overrides — tablet (≤999px) and phone (≤690px).
	$responsive_map = array(
		'height'            => '--slider-height',
		'slide_width'       => '--slide-width',
		'slide_gap'         => '--slide-gap',
		'slide_radius'      => '--slide-radius',
		'arrow_offset'      => '--arrow-offset',
		'arrow_size'        => '--arrow-size',
		'dot_size'          => '--dot-size',
		'dot_gap'           => '--dot-gap',
		'pagination_offset' => '--pagination-offset',
	);

	$selector = '#' . esc_attr( $slider_id );

	foreach ( array( 'tablet' => '999px', 'phone' => '690px' ) as $device => $breakpoint ) {
		$vars = array();
		foreach ( $responsive_map as $param => $css_var ) {
			$val = sanitize_text_field( $atts[ $param . '_' . $device ] );
			if ( '' !== $val ) {
				$vars[] = $css_var . ': ' . esc_attr( $val );
			}
		}
		if ( ! empty( $vars ) ) {
			$element_css .= ' @media only screen and (max-width: ' . $breakpoint . ') { ' . $selector . ' { ' . implode( '; ', $vars ) . '; } }';
		}
	}

	// Determine what navigation to show.
	$nav_type    = ! empty( $atts['nav_type'] ) ? $atts['nav_type'] : 'arrows';
	$show_arrows = in_array( $nav_type, array( 'arrows', 'both' ), true );
	$show_pagination = in_array( $nav_type, array( 'pagination', 'both' ), true );

	// Autoplaying content must offer a pause control (WCAG 2.2.2, Level A), so
	// the button is tied to the autoplay setting rather than the nav type. With
	// autoplay off nothing extra renders at all.
	$show_playpause = ( 'yes' === $atts['autoplay'] );

	// Accessible name for the carousel region. Blank falls back to a generic
	// label so the region is never nameless.
	$aria_label = trim( sanitize_text_field( $atts['aria_label'] ) );
	if ( '' === $aria_label ) {
		$aria_label = __( 'Image gallery', 'cph-elements' );
	}

	$total_slides = count( $images );

	// Get arrow SVGs based on style.
	$arrow_left  = '';
	$arrow_right = '';
	if ( $show_arrows ) {
		switch ( $atts['arrow_style'] ) {
			case 'circle':
				$arrow_left  = cph_gallery_slider_arrow_circle( 'left' );
				$arrow_right = cph_gallery_slider_arrow_circle( 'right' );
				break;
			case 'minimal':
				$arrow_left  = cph_gallery_slider_arrow_minimal( 'left' );
				$arrow_right = cph_gallery_slider_arrow_minimal( 'right' );
				break;
			default:
				$arrow_left  = cph_gallery_slider_arrow_left();
				$arrow_right = cph_gallery_slider_arrow_right();
				break;
		}
	}

	// Pagination CSS — colors only (sizes handled via CSS custom properties).
	if ( $show_pagination ) {
		$dot_outline_color = ! empty( $atts['dot_outline_color'] ) ? sanitize_text_field( $atts['dot_outline_color'] ) : '#ffffff';
		$dot_fill_color    = ! empty( $atts['dot_fill_color'] ) ? sanitize_text_field( $atts['dot_fill_color'] ) : '#ffffff';

		$element_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__dot { ';
		$element_css .= 'border-color: ' . esc_attr( $dot_outline_color ) . '; ';
		$element_css .= '}';

		$element_css .= ' #' . esc_attr( $slider_id ) . ' .cph-gallery-slider__dot.is-active { ';
		$element_css .= 'background-color: ' . esc_attr( $dot_fill_color ) . '; ';
		$element_css .= 'border-color: ' . esc_attr( $dot_fill_color ) . '; ';
		$element_css .= '}';
	}

	// Build data attributes string.
	$data_string = '';
	foreach ( $data_attrs as $key => $value ) {
		$data_string .= ' data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
	}

	ob_start();
	?>
	<style><?php echo $element_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<div id="<?php echo esc_attr( $slider_id ); ?>"
		 class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
		 role="region"
		 aria-roledescription="carousel"
		 aria-label="<?php echo esc_attr( $aria_label ); ?>"
		 <?php echo $data_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

		<div class="cph-gallery-slider__viewport">
			<div class="cph-gallery-slider__track">
				<?php foreach ( $images as $index => $image ) : ?>
					<div class="cph-gallery-slider__slide" data-index="<?php echo esc_attr( $index ); ?>"
						 role="group"
						 aria-roledescription="slide"
						 aria-label="<?php echo esc_attr( sprintf( /* translators: 1: current slide number, 2: total slides */ __( '%1$d of %2$d', 'cph-elements' ), $index + 1, $total_slides ) ); ?>">
						<img src="<?php echo esc_url( $image['url'] ); ?>"
							 alt="<?php echo esc_attr( $image['alt'] ); ?>"
							 loading="lazy" />
						<?php if ( $show_caption && '' !== $image['caption'] ) : ?>
							<<?php echo $caption_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- whitelisted above. ?> class="cph-gallery-slider__caption cph-gallery-slider__caption--<?php echo esc_attr( $caption_align ); ?>"><?php echo wp_kses_post( $image['caption'] ); ?></<?php echo $caption_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( $show_arrows || $show_playpause ) : ?>
			<div class="cph-gallery-slider__nav">
				<?php if ( $show_arrows ) : ?>
				<button class="cph-gallery-slider__arrow cph-gallery-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'cph-elements' ); ?>">
					<?php echo $arrow_left; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<?php endif; ?>
				<?php if ( $show_playpause ) : ?>
					<button type="button"
							class="cph-gallery-slider__playpause is-playing"
							aria-pressed="false"
							aria-label="<?php esc_attr_e( 'Pause slideshow', 'cph-elements' ); ?>"
							data-label-pause="<?php esc_attr_e( 'Pause slideshow', 'cph-elements' ); ?>"
							data-label-play="<?php esc_attr_e( 'Play slideshow', 'cph-elements' ); ?>">
						<?php
						echo cph_gallery_slider_icon_pause(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo cph_gallery_slider_icon_play();  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</button>
				<?php endif; ?>
				<?php if ( $show_arrows ) : ?>
				<button class="cph-gallery-slider__arrow cph-gallery-slider__arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'cph-elements' ); ?>">
					<?php echo $arrow_right; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $show_pagination ) : ?>
			<div class="cph-gallery-slider__pagination" role="group" aria-label="<?php esc_attr_e( 'Slide navigation', 'cph-elements' ); ?>">
				<?php foreach ( $images as $index => $image ) : ?>
					<button type="button" class="cph-gallery-slider__dot<?php echo 0 === $index ? ' is-active' : ''; ?>"
							data-index="<?php echo esc_attr( $index ); ?>"
							aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>"
							aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number */ __( 'Go to slide %d', 'cph-elements' ), $index + 1 ) ); ?>">
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
	<?php
	return ob_get_clean();
}

// Register the shortcode.
add_shortcode( 'cph_gallery_slider', 'cph_gallery_slider_shortcode' );
