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
	$logo_alt = '';
	if ( ! empty( $custom_thumb ) ) {
		if ( is_numeric( $custom_thumb ) ) {
			$logo_url = wp_get_attachment_image_url( $custom_thumb, 'medium' );
			$logo_alt = get_post_meta( $custom_thumb, '_wp_attachment_image_alt', true );
		} else {
			// Already a URL.
			$logo_url = $custom_thumb;
		}
	}

	// Get featured image alt text from the attachment.
	$image_alt = '';
	$thumb_id  = get_post_thumbnail_id( $post_id );
	if ( $thumb_id ) {
		$image_alt = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
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
		'image_alt'   => ! empty( $image_alt ) ? $image_alt : $post->post_title,
		'logo'        => $logo_url,
		'logo_alt'    => ! empty( $logo_alt ) ? $logo_alt : $post->post_title . ' logo',
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
	$display_logo      = $show_logo && ! empty( $card['logo'] );
	$display_excerpt   = $show_excerpt && ! empty( $card['excerpt'] );
	$display_video     = $show_video && ! empty( $card['video'] );
	$has_extra         = $display_logo || $display_excerpt;
	$has_content       = ! empty( $card['has_content'] );
	$content_position  = isset( $settings['content_position'] ) ? $settings['content_position'] : 'bottom-stretch';
	$button_type       = isset( $settings['button_type'] ) ? $settings['button_type'] : 'arrow';
	$animation         = 'none' !== $settings['animation'] ? $settings['animation'] : '';
	$stagger_delay     = (float) $settings['animation_stagger'] * ( $slot - 1 );

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

	if ( 'middle-center' === $content_position ) {
		$classes[] = 'cph-card--middle-center';
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
	$tag          = $has_content ? 'a' : 'div';
	$href_attr    = $has_content ? ' href="' . esc_url( $card['permalink'] ) . '"' : '';
	$show_button  = $has_content && 'none' !== $button_type;

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
				     alt="<?php echo esc_attr( $card['image_alt'] ); ?>"
				     loading="lazy" />
			<?php endif; ?>
		</div>

		<div class="cph-card__overlay"></div>

		<?php if ( $has_extra ) : ?>
			<div class="cph-card__extra">
				<?php if ( $display_logo ) : ?>
					<img class="cph-card__logo"
					     src="<?php echo esc_url( $card['logo'] ); ?>"
					     alt="<?php echo esc_attr( $card['logo_alt'] ); ?>" />
				<?php endif; ?>
				<?php if ( $display_excerpt ) : ?>
					<p class="cph-card__excerpt"><?php echo wp_kses_post( $card['excerpt'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="cph-card__content">
			<span class="cph-card__location"><?php echo esc_html( $card['title'] ); ?></span>
			<?php if ( $show_button && 'arrow' === $button_type ) : ?>
				<span class="cph-card__arrow">
					<?php echo cph_get_arrow_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			<?php elseif ( $show_button && 'text' === $button_type ) : ?>
				<span class="cph-card__text-btn"><?php echo esc_html( $settings['button_text'] ); ?></span>
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

	if ( 'homepage-bento' === $layout || 'homepage-bento-4' === $layout ) {
		$slots[1] = isset( $atts['slot_1'] ) ? (int) $atts['slot_1'] : 0;
		$slots[2] = isset( $atts['slot_2'] ) ? (int) $atts['slot_2'] : 0;
		$slots[3] = isset( $atts['slot_3'] ) ? (int) $atts['slot_3'] : 0;

		if ( 'homepage-bento-4' === $layout ) {
			$slots[4] = isset( $atts['slot_4'] ) ? (int) $atts['slot_4'] : 0;
		}
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
		4 => isset( $atts['slot_4_show_logo'] ) && 'yes' === $atts['slot_4_show_logo'],
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
		4 => isset( $atts['slot_4_show_video'] ) && 'yes' === $atts['slot_4_show_video'],
	);
}

/**
 * Get show_excerpt settings for homepage-bento layouts.
 *
 * @since 1.1.0
 *
 * @param array $atts Shortcode attributes.
 * @return array Array of booleans keyed by slot number.
 */
function cph_get_bento_show_excerpt_settings( $atts ) {
	return array(
		1 => isset( $atts['slot_1_show_excerpt'] ) && 'yes' === $atts['slot_1_show_excerpt'],
		2 => isset( $atts['slot_2_show_excerpt'] ) && 'yes' === $atts['slot_2_show_excerpt'],
		3 => isset( $atts['slot_3_show_excerpt'] ) && 'yes' === $atts['slot_3_show_excerpt'],
		4 => isset( $atts['slot_4_show_excerpt'] ) && 'yes' === $atts['slot_4_show_excerpt'],
	);
}

/**
 * Get content_position settings for homepage-bento.
 *
 * @since 1.1.0
 *
 * @param array $atts Shortcode attributes.
 * @return array Array of position strings keyed by slot number.
 */
function cph_get_bento_content_position_settings( $atts ) {
	return array(
		1 => ! empty( $atts['slot_1_content_position'] ) ? $atts['slot_1_content_position'] : 'bottom-stretch',
		2 => ! empty( $atts['slot_2_content_position'] ) ? $atts['slot_2_content_position'] : 'bottom-stretch',
		3 => ! empty( $atts['slot_3_content_position'] ) ? $atts['slot_3_content_position'] : 'bottom-stretch',
		4 => ! empty( $atts['slot_4_content_position'] ) ? $atts['slot_4_content_position'] : 'bottom-stretch',
	);
}

/**
 * Get button_type settings for homepage-bento.
 *
 * @since 1.1.0
 *
 * @param array  $atts     Shortcode attributes.
 * @param string $fallback Global button type fallback.
 * @return array Array of button type strings keyed by slot number.
 */
function cph_get_bento_button_type_settings( $atts, $fallback ) {
	return array(
		1 => ! empty( $atts['slot_1_button_type'] ) ? $atts['slot_1_button_type'] : $fallback,
		2 => ! empty( $atts['slot_2_button_type'] ) ? $atts['slot_2_button_type'] : $fallback,
		3 => ! empty( $atts['slot_3_button_type'] ) ? $atts['slot_3_button_type'] : $fallback,
		4 => ! empty( $atts['slot_4_button_type'] ) ? $atts['slot_4_button_type'] : $fallback,
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
			'grid_height'               => '',
			'grid_height_tablet'        => '',
			'grid_height_phone'         => '',
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
			'content_position'          => 'bottom-stretch',
			'button_type'               => '',
			'button_text'               => 'Read More',
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
			'slot_1_show_excerpt'       => '',
			'slot_1_content_position'   => '',
			'slot_1_button_type'        => '',
			'slot_2'                    => '',
			'slot_2_show_logo'          => '',
			'slot_2_show_video'         => '',
			'slot_2_show_excerpt'       => '',
			'slot_2_content_position'   => '',
			'slot_2_button_type'        => '',
			'slot_3'                    => '',
			'slot_3_show_logo'          => '',
			'slot_3_show_video'         => '',
			'slot_3_show_excerpt'       => '',
			'slot_3_content_position'   => '',
			'slot_3_button_type'        => '',
			'slot_4'                    => '',
			'slot_4_show_logo'          => '',
			'slot_4_show_video'         => '',
			'slot_4_show_excerpt'       => '',
			'slot_4_content_position'   => '',
			'slot_4_button_type'        => '',
			// Featured-2col settings.
			'category'                  => '',
			'status'                    => '',
			'posts_per_page'            => '',
			'show_logo'                 => '',
			'show_excerpt'              => '',
			'show_video'                => '',
			// Zigzag settings.
			'heading_tag'               => 'h3',
			'show_filter'               => 'yes',
			'show_eyebrow'              => 'yes',
			'zigzag_button_text'        => 'VIEW PROJECT',
			'zigzag_row_height'         => '500px',
			'zigzag_row_gap'            => '40px',
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

	// Determine global button type (backward compat with show_arrow).
	$global_button_type = $atts['button_type'];
	if ( empty( $global_button_type ) ) {
		$global_button_type = ( 'yes' === $atts['show_arrow'] ) ? 'arrow' : 'none';
	}

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
		// Content & Button.
		'text_color'               => $atts['text_color'],
		'content_position'         => $atts['content_position'],
		'button_type'              => $global_button_type,
		'button_text'              => $atts['button_text'],
		'card_height'              => $atts['card_height'],
		'location_font_size'       => $atts['location_font_size'],
		'location_letter_spacing'  => $atts['location_letter_spacing'],
		'animation'                => $atts['animation'],
		'animation_stagger'        => $atts['animation_stagger'],
	);

	// Determine if grid height is constrained (bento layouts only).
	$is_constrained = ! empty( $atts['grid_height'] ) && in_array( $layout, array( 'homepage-bento', 'homepage-bento-4' ), true );

	// Build responsive CSS using style block (not inline) for proper media query support.
	$desktop_vars = array(
		'--grid-gap: ' . $gap . 'px',
		$is_constrained ? '--grid-height: ' . esc_attr( $atts['grid_height'] ) : '',
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
		// Zigzag.
		'zigzag' === $layout ? '--zigzag-row-height: ' . esc_attr( $atts['zigzag_row_height'] ) : '',
		'zigzag' === $layout ? '--zigzag-row-gap: ' . esc_attr( $atts['zigzag_row_gap'] ) : '',
	);
	$desktop_vars = array_filter( $desktop_vars );
	$element_css  = '#' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $desktop_vars ) . '; }';

	// Tablet styles (max-width: 999px).
	$tablet_vars = array();
	if ( ! empty( $atts['grid_height_tablet'] ) ) {
		$tablet_vars[] = '--grid-height: ' . esc_attr( $atts['grid_height_tablet'] );
	}
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
	if ( ! empty( $atts['grid_height_phone'] ) ) {
		$phone_vars[] = '--grid-height: ' . esc_attr( $atts['grid_height_phone'] );
	}
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

	if ( $is_constrained ) {
		$grid_classes[] = 'cph-portfolio-grid--constrained';
	}

	if ( 'yes' === $atts['lower_third_enabled'] ) {
		$grid_classes[] = 'cph-portfolio-grid--lower-third';
	}

	ob_start();
	?>
	<style><?php echo $element_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<div id="<?php echo esc_attr( $grid_id ); ?>" class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
		<?php
		if ( 'homepage-bento' === $layout || 'homepage-bento-4' === $layout ) {
			// Homepage Bento: manual slot selection.
			$slots             = cph_get_slot_ids( $layout, $atts );
			$show_logos        = cph_get_bento_show_logo_settings( $atts );
			$show_videos       = cph_get_bento_show_video_settings( $atts );
			$show_excerpts     = cph_get_bento_show_excerpt_settings( $atts );
			$content_positions = cph_get_bento_content_position_settings( $atts );
			$button_types      = cph_get_bento_button_type_settings( $atts, $global_button_type );

			foreach ( $slots as $slot_num => $post_id ) {
				$card         = cph_get_card_data( $post_id );
				$show_logo    = isset( $show_logos[ $slot_num ] ) ? $show_logos[ $slot_num ] : false;
				$show_video   = isset( $show_videos[ $slot_num ] ) ? $show_videos[ $slot_num ] : false;
				$show_excerpt = isset( $show_excerpts[ $slot_num ] ) ? $show_excerpts[ $slot_num ] : false;
				$size_class   = ( 1 === $slot_num );

				// Per-slot overrides.
				$slot_settings                     = $settings;
				$slot_settings['content_position'] = $content_positions[ $slot_num ];
				$slot_settings['button_type']      = $button_types[ $slot_num ];

				echo cph_render_card( $card, $slot_num, $slot_settings, $show_logo, $show_excerpt, $size_class, $show_video ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		} elseif ( 'featured-2col' === $layout ) {
			// Projects Grid: auto-populate from post order with featured support.
			echo cph_render_featured_2col_grid( $atts, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} elseif ( 'zigzag' === $layout ) {
			// Zigzag: alternating image/text rows with category filter.
			echo cph_render_zigzag_grid( $atts, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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

/**
 * Render the zigzag grid layout.
 *
 * Alternating 50/50 rows: odd rows have image-left/text-right,
 * even rows flip. Includes an optional category filter bar.
 *
 * @since 1.2.0
 *
 * @param array $atts     Shortcode attributes.
 * @param array $settings Card rendering settings.
 * @return string HTML output.
 */
function cph_render_zigzag_grid( $atts, $settings ) {
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
		$query_args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	}

	$query = new WP_Query( $query_args );

	if ( ! $query->have_posts() ) {
		return '<p class="cph-portfolio-grid-empty">' . esc_html__( 'No projects found.', 'cph-elements' ) . '</p>';
	}

	// Settings.
	$heading_tag  = in_array( $atts['heading_tag'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ? $atts['heading_tag'] : 'h3';
	$show_filter  = 'yes' === $atts['show_filter'];
	$show_eyebrow = 'yes' === $atts['show_eyebrow'];
	$button_text  = ! empty( $atts['zigzag_button_text'] ) ? $atts['zigzag_button_text'] : 'VIEW PROJECT';

	// Collect all posts and their categories for filter bar.
	$posts_data  = array();
	$all_terms   = array(); // slug => name.

	while ( $query->have_posts() ) {
		$query->the_post();
		$post_id = get_the_ID();
		$card    = cph_get_card_data( $post_id );

		if ( ! $card ) {
			continue;
		}

		// Get project-type terms.
		$terms      = wp_get_post_terms( $post_id, 'project-type' );
		$term_slugs = array();
		$term_names = array();

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$term_slugs[] = $term->slug;
				$term_names[] = $term->name;
				if ( ! isset( $all_terms[ $term->slug ] ) ) {
					$all_terms[ $term->slug ] = $term->name;
				}
			}
		}

		$posts_data[] = array(
			'card'       => $card,
			'term_slugs' => $term_slugs,
			'term_names' => $term_names,
		);
	}

	wp_reset_postdata();

	ob_start();

	// Filter bar.
	if ( $show_filter && ! empty( $all_terms ) ) {
		?>
		<nav class="cph-zigzag__filter" aria-label="<?php esc_attr_e( 'Project filter', 'cph-elements' ); ?>">
			<button class="cph-zigzag__filter-btn is-active" data-filter="" type="button"><?php esc_html_e( 'All', 'cph-elements' ); ?></button>
			<?php foreach ( $all_terms as $slug => $name ) : ?>
				<button class="cph-zigzag__filter-btn" data-filter="<?php echo esc_attr( $slug ); ?>" type="button"><?php echo esc_html( $name ); ?></button>
			<?php endforeach; ?>
		</nav>
		<?php
	}

	// Rows.
	?>
	<div class="cph-zigzag__rows">
		<?php foreach ( $posts_data as $post_item ) :
			$card       = $post_item['card'];
			$cat_attr   = implode( ' ', $post_item['term_slugs'] );
			$eyebrow_terms = $show_eyebrow && ! empty( $post_item['term_names'] ) ? $post_item['term_names'] : array();
			$has_link      = ! empty( $card['has_content'] );
		?>
		<div class="cph-zigzag__row" data-categories="<?php echo esc_attr( $cat_attr ); ?>">
			<div class="cph-zigzag__image">
				<?php if ( ! empty( $card['image'] ) ) : ?>
					<img src="<?php echo esc_url( $card['image'] ); ?>"
					     alt="<?php echo esc_attr( $card['image_alt'] ); ?>"
					     loading="lazy" />
				<?php endif; ?>
			</div>
			<div class="cph-zigzag__text">
				<?php if ( ! empty( $eyebrow_terms ) ) : ?>
					<span class="cph-zigzag__eyebrow">
						<?php
						$last = count( $eyebrow_terms ) - 1;
						foreach ( $eyebrow_terms as $i => $term_name ) :
							echo '<span class="cph-zigzag__eyebrow-tag">' . esc_html( $term_name ) . '</span>';
							if ( $i < $last ) {
								echo ', ';
							}
						endforeach;
						?>
					</span>
				<?php endif; ?>
				<<?php echo esc_attr( $heading_tag ); ?> class="cph-zigzag__title"><?php echo esc_html( $card['title'] ); ?></<?php echo esc_attr( $heading_tag ); ?>>
				<?php if ( $has_link ) : ?>
					<a class="cph-zigzag__btn" href="<?php echo esc_url( $card['permalink'] ); ?>"><?php echo esc_html( $button_text ); ?></a>
				<?php else : ?>
					<span class="cph-zigzag__btn cph-zigzag__btn--disabled"><?php echo esc_html( $button_text ); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php

	return ob_get_clean();
}

// Register the shortcode.
add_shortcode( 'cph_portfolio_grid', 'cph_portfolio_grid_shortcode' );
