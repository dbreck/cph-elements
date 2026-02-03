<?php
/**
 * CPH Portfolio Grid - Shortcode Rendering
 *
 * Renders the portfolio grid element HTML output.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get card data from a Portfolio post.
 *
 * @since 1.0.0
 *
 * @param int $post_id Portfolio post ID.
 * @return array|null Card data array or null if post doesn't exist.
 */
function cph_get_card_data( $post_id ) {
	if ( empty( $post_id ) ) {
		return null;
	}

	$post = get_post( $post_id );
	if ( ! $post || 'portfolio' !== $post->post_type ) {
		return null;
	}

	$external_url = get_post_meta( $post_id, '_nectar_external_project_url', true );
	$custom_thumb = get_post_meta( $post_id, '_nectar_portfolio_custom_thumbnail', true );
	$excerpt      = get_post_meta( $post_id, '_nectar_project_excerpt', true );
	$video_mp4    = get_post_meta( $post_id, '_nectar_video_m4v', true );

	// Handle custom_thumb - could be URL or attachment ID.
	$logo_url = null;
	if ( ! empty( $custom_thumb ) ) {
		if ( is_numeric( $custom_thumb ) ) {
			$logo_url = wp_get_attachment_image_url( $custom_thumb, 'medium' );
		} else {
			// Already a URL.
			$logo_url = $custom_thumb;
		}
	}

	// Determine if post has content.
	// Salient stores portfolio content in _nectar_portfolio_extra_content meta, not post_content.
	$extra_content = get_post_meta( $post_id, '_nectar_portfolio_extra_content', true );
	$post_content  = trim( $post->post_content );
	$has_content   = ! empty( $extra_content ) || ! empty( $post_content ) || ! empty( $external_url );

	return array(
		'id'          => $post_id,
		'title'       => $post->post_title,
		'permalink'   => ! empty( $external_url ) ? esc_url( $external_url ) : get_permalink( $post_id ),
		'image'       => get_the_post_thumbnail_url( $post_id, 'full' ),
		'logo'        => $logo_url,
		'excerpt'     => ! empty( $excerpt ) ? $excerpt : null,
		'video'       => ! empty( $video_mp4 ) ? $video_mp4 : null,
		'has_content' => $has_content,
	);
}

/**
 * Render a single card.
 *
 * @since 1.0.0
 *
 * @param array $card          Card data from cph_get_card_data().
 * @param int   $slot          Slot number.
 * @param array $settings      Element settings.
 * @param bool  $show_logo     Whether to show the logo on this card.
 * @param bool  $show_excerpt  Whether to show the excerpt on this card.
 * @param bool  $is_full_width Whether this card should be full width.
 * @param bool  $show_video    Whether to show video if available.
 * @return string Card HTML.
 */
function cph_render_card( $card, $slot, $settings, $show_logo = false, $show_excerpt = false, $is_full_width = false, $show_video = false ) {
	if ( empty( $card ) ) {
		return '';
	}

	// Determine what to display.
	$display_logo    = $show_logo && ! empty( $card['logo'] );
	$display_excerpt = $show_excerpt && ! empty( $card['excerpt'] );
	$display_video   = $show_video && ! empty( $card['video'] );
	$has_extra       = $display_logo || $display_excerpt;
	$has_content     = ! empty( $card['has_content'] );
	$animation       = 'none' !== $settings['animation'] ? $settings['animation'] : '';
	$stagger_delay   = (float) $settings['animation_stagger'] * ( $slot - 1 );

	// Build classes.
	$classes = array(
		'cph-card',
		'cph-card--slot-' . $slot,
	);

	if ( $has_extra ) {
		$classes[] = 'cph-card--has-extra';
	}

	if ( $is_full_width ) {
		$classes[] = 'cph-card--full-width';
	}

	if ( $display_video ) {
		$classes[] = 'cph-card--has-video';
	}

	if ( ! $has_content ) {
		$classes[] = 'cph-card--no-content';
	}

	// Animation classes.
	if ( ! empty( $animation ) ) {
		if ( 'fade-up' === $animation ) {
			$classes[] = 'fx-up';
			$classes[] = 'fx-up--delay-' . (int) ( $stagger_delay * 1000 );
		} elseif ( 'curtain-wipe' === $animation ) {
			$classes[] = 'curtain-wipe';
			$classes[] = 'curtain-wipe--delay-' . (int) ( $stagger_delay * 1000 );
		}
	}

	// Determine wrapper element (link vs div).
	$tag        = $has_content ? 'a' : 'div';
	$href_attr  = $has_content ? ' href="' . esc_url( $card['permalink'] ) . '"' : '';
	$show_arrow = $has_content && 'yes' === $settings['show_arrow'];

	ob_start();
	?>
	<<?php echo $tag . $href_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	   class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

		<div class="cph-card__image">
			<?php if ( $display_video ) : ?>
				<video autoplay muted loop playsinline
				       poster="<?php echo esc_url( $card['image'] ); ?>">
					<source src="<?php echo esc_url( $card['video'] ); ?>" type="video/mp4">
				</video>
			<?php elseif ( ! empty( $card['image'] ) ) : ?>
				<img src="<?php echo esc_url( $card['image'] ); ?>"
				     alt="<?php echo esc_attr( $card['title'] ); ?>"
				     loading="lazy" />
			<?php endif; ?>
		</div>

		<div class="cph-card__overlay"></div>

		<?php if ( $has_extra ) : ?>
			<div class="cph-card__extra">
				<?php if ( $display_logo ) : ?>
					<img class="cph-card__logo"
					     src="<?php echo esc_url( $card['logo'] ); ?>"
					     alt="<?php echo esc_attr( $card['title'] ); ?> logo" />
				<?php endif; ?>
				<?php if ( $display_excerpt ) : ?>
					<p class="cph-card__excerpt"><?php echo wp_kses_post( $card['excerpt'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="cph-card__content">
			<span class="cph-card__location"><?php echo esc_html( $card['title'] ); ?></span>
			<?php if ( $show_arrow ) : ?>
				<span class="cph-card__arrow">
					<?php echo cph_get_arrow_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			<?php endif; ?>
		</div>
	</<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php
	return ob_get_clean();
}

/**
 * Get the arrow button SVG (pill shape with thin arrow).
 *
 * @since 1.0.0
 *
 * @return string SVG markup.
 */
function cph_get_arrow_svg() {
	return '<svg viewBox="0 0 140 50" fill="none" xmlns="http://www.w3.org/2000/svg">
		<rect x="0.5" y="0.5" width="139" height="49" rx="24.5" stroke="currentColor" stroke-width="1"/>
		<path d="M45 25H95M95 25L89 20M95 25L89 30" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>';
}

/**
 * Get slot IDs based on layout (for homepage-bento only).
 *
 * @since 1.0.0
 *
 * @param string $layout Layout preset name.
 * @param array  $atts   Shortcode attributes.
 * @return array Array of post IDs keyed by slot number.
 */
function cph_get_slot_ids( $layout, $atts ) {
	$slots = array();

	if ( 'homepage-bento' === $layout ) {
		$slots[1] = isset( $atts['slot_1'] ) ? (int) $atts['slot_1'] : 0;
		$slots[2] = isset( $atts['slot_2'] ) ? (int) $atts['slot_2'] : 0;
		$slots[3] = isset( $atts['slot_3'] ) ? (int) $atts['slot_3'] : 0;
	}

	return $slots;
}

/**
 * Get show_logo settings for homepage-bento.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return array Array of booleans keyed by slot number.
 */
function cph_get_bento_show_logo_settings( $atts ) {
	return array(
		1 => isset( $atts['slot_1_show_logo'] ) && 'yes' === $atts['slot_1_show_logo'],
		2 => isset( $atts['slot_2_show_logo'] ) && 'yes' === $atts['slot_2_show_logo'],
		3 => isset( $atts['slot_3_show_logo'] ) && 'yes' === $atts['slot_3_show_logo'],
	);
}

/**
 * Get show_video settings for homepage-bento.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return array Array of booleans keyed by slot number.
 */
function cph_get_bento_show_video_settings( $atts ) {
	return array(
		1 => isset( $atts['slot_1_show_video'] ) && 'yes' === $atts['slot_1_show_video'],
		2 => isset( $atts['slot_2_show_video'] ) && 'yes' === $atts['slot_2_show_video'],
		3 => isset( $atts['slot_3_show_video'] ) && 'yes' === $atts['slot_3_show_video'],
	);
}

/**
 * Render the portfolio grid shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_portfolio_grid_shortcode( $atts ) {
	// Extract attributes with defaults.
	$atts = shortcode_atts(
		array(
			'layout'                    => 'homepage-bento',
			'gap'                       => '20',
			// Overlay - Default.
			'overlay_color'             => '#000000',
			'overlay_opacity'           => '20',
			'overlay_blend_mode'        => 'normal',
			// Overlay - Hover.
			'overlay_color_hover'       => '',
			'overlay_opacity_hover'     => '30',
			'overlay_blend_mode_hover'  => 'normal',
			// Lower Third (title hover effect).
			'lower_third_enabled'       => '',
			'lower_third_bar_color'     => '#ffffff',
			'lower_third_text_color'    => '',
			// Logo styling.
			'logo_max_width'            => '70%',
			'logo_blend_mode'           => 'normal',
			'logo_brightness'           => '100',
			// Other styling.
			'text_color'                => '#ffffff',
			'show_arrow'                => 'yes',
			'card_height'               => '525px',
			'card_height_tablet'        => '',
			'card_height_phone'         => '',
			'location_font_size'        => '30px',
			'location_font_size_tablet' => '',
			'location_font_size_phone'  => '',
			'location_letter_spacing'   => '1.5px',
			'animation'                 => 'none',
			'animation_stagger'         => '0.15',
			// Homepage Bento slots.
			'slot_1'                    => '',
			'slot_1_show_logo'          => '',
			'slot_1_show_video'         => '',
			'slot_2'                    => '',
			'slot_2_show_logo'          => '',
			'slot_2_show_video'         => '',
			'slot_3'                    => '',
			'slot_3_show_logo'          => '',
			'slot_3_show_video'         => '',
			// Featured-2col settings.
			'category'                  => '',
			'status'                    => '',
			'posts_per_page'            => '',
			'show_logo'                 => '',
			'show_excerpt'              => '',
			'show_video'                => '',
		),
		$atts,
		'cph_portfolio_grid'
	);

	$layout = sanitize_text_field( $atts['layout'] );
	$gap    = (int) $atts['gap'];

	// Generate unique ID for this instance.
	static $instance_id = 0;
	++$instance_id;
	$grid_id = 'cph-portfolio-grid-' . $instance_id;

	// Build settings array for card rendering.
	$settings = array(
		// Overlay - Default.
		'overlay_color'            => $atts['overlay_color'],
		'overlay_opacity'          => $atts['overlay_opacity'],
		'overlay_blend_mode'       => $atts['overlay_blend_mode'],
		// Overlay - Hover.
		'overlay_color_hover'      => $atts['overlay_color_hover'],
		'overlay_opacity_hover'    => $atts['overlay_opacity_hover'],
		'overlay_blend_mode_hover' => $atts['overlay_blend_mode_hover'],
		// Lower Third.
		'lower_third_enabled'      => $atts['lower_third_enabled'],
		'lower_third_bar_color'    => $atts['lower_third_bar_color'],
		'lower_third_text_color'   => $atts['lower_third_text_color'],
		// Other.
		'text_color'               => $atts['text_color'],
		'show_arrow'               => $atts['show_arrow'],
		'card_height'              => $atts['card_height'],
		'location_font_size'       => $atts['location_font_size'],
		'location_letter_spacing'  => $atts['location_letter_spacing'],
		'animation'                => $atts['animation'],
		'animation_stagger'        => $atts['animation_stagger'],
	);

	// Build responsive CSS using style block (not inline) for proper media query support.
	$desktop_vars = array(
		'--grid-gap: ' . $gap . 'px',
		'--card-height: ' . esc_attr( $atts['card_height'] ),
		'--text-color: ' . esc_attr( $atts['text_color'] ),
		'--location-font-size: ' . esc_attr( $atts['location_font_size'] ),
		'--location-letter-spacing: ' . esc_attr( $atts['location_letter_spacing'] ),
		// Logo.
		'--logo-max-width: ' . esc_attr( $atts['logo_max_width'] ),
		'--logo-blend-mode: ' . esc_attr( $atts['logo_blend_mode'] ),
		'--logo-brightness: ' . ( (int) $atts['logo_brightness'] / 100 ),
		// Overlay - Default.
		'--overlay-color: ' . esc_attr( $atts['overlay_color'] ),
		'--overlay-opacity: ' . ( (int) $atts['overlay_opacity'] / 100 ),
		'--overlay-blend-mode: ' . esc_attr( $atts['overlay_blend_mode'] ),
		// Overlay - Hover.
		'--overlay-color-hover: ' . esc_attr( ! empty( $atts['overlay_color_hover'] ) ? $atts['overlay_color_hover'] : $atts['overlay_color'] ),
		'--overlay-opacity-hover: ' . ( (int) $atts['overlay_opacity_hover'] / 100 ),
		'--overlay-blend-mode-hover: ' . esc_attr( $atts['overlay_blend_mode_hover'] ),
		// Lower Third.
		'--lower-third-bar-color: ' . esc_attr( $atts['lower_third_bar_color'] ),
		'--lower-third-text-color: ' . esc_attr( ! empty( $atts['lower_third_text_color'] ) ? $atts['lower_third_text_color'] : $atts['text_color'] ),
	);
	$element_css = '#' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $desktop_vars ) . '; }';

	// Tablet styles (max-width: 999px).
	$tablet_vars = array();
	if ( ! empty( $atts['card_height_tablet'] ) ) {
		$tablet_vars[] = '--card-height: ' . esc_attr( $atts['card_height_tablet'] );
	}
	if ( ! empty( $atts['location_font_size_tablet'] ) ) {
		$tablet_vars[] = '--location-font-size: ' . esc_attr( $atts['location_font_size_tablet'] );
	}
	if ( ! empty( $tablet_vars ) ) {
		$element_css .= ' @media only screen and (max-width: 999px) { #' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $tablet_vars ) . '; } }';
	}

	// Phone styles (max-width: 690px).
	$phone_vars = array();
	if ( ! empty( $atts['card_height_phone'] ) ) {
		$phone_vars[] = '--card-height: ' . esc_attr( $atts['card_height_phone'] );
	}
	if ( ! empty( $atts['location_font_size_phone'] ) ) {
		$phone_vars[] = '--location-font-size: ' . esc_attr( $atts['location_font_size_phone'] );
	}
	if ( ! empty( $phone_vars ) ) {
		$element_css .= ' @media only screen and (max-width: 690px) { #' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $phone_vars ) . '; } }';
	}

	// Build grid classes.
	$grid_classes = array(
		'cph-portfolio-grid',
		'cph-portfolio-grid--' . $layout,
	);

	if ( 'yes' === $atts['lower_third_enabled'] ) {
		$grid_classes[] = 'cph-portfolio-grid--lower-third';
	}

	ob_start();
	?>
	<style><?php echo $element_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<div id="<?php echo esc_attr( $grid_id ); ?>" class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
		<?php
		if ( 'homepage-bento' === $layout ) {
			// Homepage Bento: manual slot selection.
			$slots       = cph_get_slot_ids( $layout, $atts );
			$show_logos  = cph_get_bento_show_logo_settings( $atts );
			$show_videos = cph_get_bento_show_video_settings( $atts );

			foreach ( $slots as $slot_num => $post_id ) {
				$card       = cph_get_card_data( $post_id );
				$show_logo  = isset( $show_logos[ $slot_num ] ) ? $show_logos[ $slot_num ] : false;
				$show_video = isset( $show_videos[ $slot_num ] ) ? $show_videos[ $slot_num ] : false;
				$size_class = ( 1 === $slot_num );

				echo cph_render_card( $card, $slot_num, $settings, $show_logo, $show_logo, $size_class, $show_video ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		} elseif ( 'featured-2col' === $layout ) {
			// Projects Grid: auto-populate from post order with featured support.
			echo cph_render_featured_2col_grid( $atts, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render the featured-2col grid layout.
 *
 * Uses a buffer system to pair non-featured posts into 2-column rows,
 * while featured posts span full width.
 *
 * @since 1.0.0
 *
 * @param array $atts     Shortcode attributes.
 * @param array $settings Card rendering settings.
 * @return string HTML output.
 */
function cph_render_featured_2col_grid( $atts, $settings ) {
	// Build query args.
	$posts_per_page = ! empty( $atts['posts_per_page'] ) ? (int) $atts['posts_per_page'] : -1;

	$query_args = array(
		'post_type'      => 'portfolio',
		'posts_per_page' => $posts_per_page,
		'post_status'    => 'publish',
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
	);

	// Build tax_query for category and status filters.
	$category = sanitize_text_field( $atts['category'] );
	$status   = sanitize_text_field( $atts['status'] );

	$tax_query = array();

	if ( ! empty( $category ) ) {
		$tax_query[] = array(
			'taxonomy' => 'project-type',
			'field'    => 'slug',
			'terms'    => $category,
		);
	}

	if ( ! empty( $status ) ) {
		$tax_query[] = array(
			'taxonomy' => 'project-status',
			'field'    => 'slug',
			'terms'    => $status,
		);
	}

	if ( ! empty( $tax_query ) ) {
		if ( count( $tax_query ) > 1 ) {
			$tax_query['relation'] = 'AND';
		}
		$query_args['tax_query'] = $tax_query;
	}

	$query = new WP_Query( $query_args );

	if ( ! $query->have_posts() ) {
		return '<p class="cph-portfolio-grid-empty">' . esc_html__( 'No projects found.', 'cph-elements' ) . '</p>';
	}

	// Global show settings.
	$show_logo    = 'yes' === $atts['show_logo'];
	$show_excerpt = 'yes' === $atts['show_excerpt'];
	$show_video   = 'yes' === $atts['show_video'];

	$output      = '';
	$buffer      = array(); // Hold non-featured posts for pairing.
	$slot_number = 1;

	while ( $query->have_posts() ) {
		$query->the_post();
		$post_id     = get_the_ID();
		$is_featured = '1' === get_post_meta( $post_id, '_cph_portfolio_featured', true );

		if ( $is_featured ) {
			// Flush buffer first (render any accumulated pairs).
			if ( ! empty( $buffer ) ) {
				$output     .= cph_flush_card_buffer( $buffer, $slot_number, $settings, $show_logo, $show_excerpt, $show_video );
				$slot_number += count( $buffer );
				$buffer      = array();
			}

			// Render featured card full-width.
			$card    = cph_get_card_data( $post_id );
			$output .= cph_render_card( $card, $slot_number, $settings, $show_logo, $show_excerpt, true, $show_video );
			$slot_number++;
		} else {
			// Add to buffer.
			$buffer[] = $post_id;

			// When buffer has 2, render the pair.
			if ( 2 === count( $buffer ) ) {
				$output     .= cph_flush_card_buffer( $buffer, $slot_number, $settings, $show_logo, $show_excerpt, $show_video );
				$slot_number += 2;
				$buffer      = array();
			}
		}
	}

	// Handle remaining buffer (orphan handling).
	if ( ! empty( $buffer ) ) {
		$is_orphan = ( 1 === count( $buffer ) );

		foreach ( $buffer as $post_id ) {
			$card = cph_get_card_data( $post_id );
			// If only 1 post left, render it full-width.
			$output .= cph_render_card( $card, $slot_number, $settings, $show_logo, $show_excerpt, $is_orphan, $show_video );
			$slot_number++;
		}
	}

	wp_reset_postdata();

	return $output;
}

/**
 * Flush buffer of non-featured cards (render them).
 *
 * @since 1.0.0
 *
 * @param array $buffer       Array of post IDs to render.
 * @param int   $slot_number  Starting slot number.
 * @param array $settings     Card rendering settings.
 * @param bool  $show_logo    Whether to show logos.
 * @param bool  $show_excerpt Whether to show excerpts.
 * @param bool  $show_video   Whether to show videos.
 * @return string HTML output for the cards.
 */
function cph_flush_card_buffer( $buffer, $slot_number, $settings, $show_logo, $show_excerpt, $show_video = false ) {
	$output = '';

	foreach ( $buffer as $post_id ) {
		$card    = cph_get_card_data( $post_id );
		$output .= cph_render_card( $card, $slot_number, $settings, $show_logo, $show_excerpt, false, $show_video );
		$slot_number++;
	}

	return $output;
}

// Register the shortcode.
add_shortcode( 'cph_portfolio_grid', 'cph_portfolio_grid_shortcode' );
