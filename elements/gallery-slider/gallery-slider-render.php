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
	return '<svg viewBox="0 0 140 50" fill="none" xmlns="http://www.w3.org/2000/svg">
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
	return '<svg viewBox="0 0 140 50" fill="none" xmlns="http://www.w3.org/2000/svg">
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
		return '<svg viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
			<circle cx="25" cy="25" r="24" stroke="currentColor" stroke-width="1"/>
			<path d="M30 17L22 25L30 33" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>';
	}
	return '<svg viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
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
		return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>';
	}
	return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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

		$url = wp_get_attachment_image_url( $id, 'full' );
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );

		if ( $url ) {
			$images[] = array(
				'url' => $url,
				'alt' => $alt ? $alt : '',
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
			$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );

			if ( $thumb_url ) {
				$images[] = array(
					'url' => $thumb_url,
					'alt' => get_the_title(),
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
			'draggable'                    => 'yes',
			'autoplay'                     => '',
			'autoplay_speed'               => '5',
			'pause_on_hover'               => 'yes',
			'transition_duration'          => '600',
			// Navigation.
			'nav_type'                     => 'arrows',
			'show_arrows'                  => 'yes', // Legacy support.
			'arrow_style'                  => 'pill',
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

	// Build data attributes for JS.
	$start_slide = max( 0, intval( $atts['start_slide'] ) - 1 ); // Convert 1-based to 0-based index.
	$easing      = ! empty( $atts['easing'] ) ? $atts['easing'] : 'power2.out';
	$data_attrs  = array(
		'start-slide'         => $start_slide,
		'infinite'            => 'yes' === $atts['infinite'] ? 'true' : 'false',
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
		'--perspective: ' . esc_attr( $perspective ),
		'--dot-size: ' . esc_attr( $dot_size ),
		'--dot-gap: ' . esc_attr( $dot_gap ),
		'--pagination-offset: ' . esc_attr( $pagination_offset ),
	);
	$element_css = '#' . esc_attr( $slider_id ) . ' { ' . implode( '; ', $desktop_vars ) . '; }';

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
		 <?php echo $data_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

		<div class="cph-gallery-slider__viewport">
			<div class="cph-gallery-slider__track">
				<?php foreach ( $images as $index => $image ) : ?>
					<div class="cph-gallery-slider__slide" data-index="<?php echo esc_attr( $index ); ?>">
						<img src="<?php echo esc_url( $image['url'] ); ?>"
							 alt="<?php echo esc_attr( $image['alt'] ); ?>"
							 loading="lazy" />
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( $show_arrows ) : ?>
			<button class="cph-gallery-slider__arrow cph-gallery-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'cph-elements' ); ?>">
				<?php echo $arrow_left; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
			<button class="cph-gallery-slider__arrow cph-gallery-slider__arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'cph-elements' ); ?>">
				<?php echo $arrow_right; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		<?php endif; ?>

		<?php if ( $show_pagination ) : ?>
			<div class="cph-gallery-slider__pagination" role="tablist" aria-label="<?php esc_attr_e( 'Slide navigation', 'cph-elements' ); ?>">
				<?php foreach ( $images as $index => $image ) : ?>
					<button class="cph-gallery-slider__dot<?php echo 0 === $index ? ' is-active' : ''; ?>"
							data-index="<?php echo esc_attr( $index ); ?>"
							role="tab"
							aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
							aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'cph-elements' ), $index + 1 ) ); ?>">
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
