<?php
/**
 * CPH Stats Photos - Shortcode Rendering
 *
 * Renders the stats photos element with hover and random reveal functionality.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the stats photos shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_stats_photos_shortcode( $atts ) {
	// Extract attributes with defaults.
	$atts = shortcode_atts(
		array(
			// Photos repeater.
			'photos'                  => '',
			// Behavior.
			'show_on_hover'           => 'yes',
			'show_randomly'           => '',
			'random_interval'         => '3',
			// Layout.
			'container_height'        => '500px',
			'container_height_tablet' => '',
			'container_height_phone'  => '',
			'text_alignment'          => 'center',
			'text_placement'          => 'bottom',
			'image_alignment'         => 'center center',
			// Style.
			'overlay_color'           => '#000000',
			'overlay_opacity'         => '0.4',
			'text_color'              => '#ffffff',
			'value_font_size'         => '72px',
			'value_font_size_tablet'  => '',
			'value_font_size_phone'   => '',
			'label_font_size'         => '16px',
			'label_font_size_tablet'  => '',
			'label_font_size_phone'   => '',
			// Extra.
			'el_class'                => '',
		),
		$atts,
		'cph_stats_photos'
	);

	// Parse the photos param_group.
	$photos = array();
	if ( ! empty( $atts['photos'] ) ) {
		$photos = vc_param_group_parse_atts( $atts['photos'] );
	}

	// Return early if no photos.
	if ( empty( $photos ) ) {
		return '';
	}

	// Generate unique ID for this instance.
	static $instance_id = 0;
	++$instance_id;
	$element_id = 'cph-stats-photos-' . $instance_id;

	// Build wrapper classes.
	$wrapper_classes = array(
		'cph-stats-photos',
		'cph-stats-photos--align-' . esc_attr( $atts['text_alignment'] ),
		'cph-stats-photos--placement-' . esc_attr( $atts['text_placement'] ),
	);
	if ( 'yes' === $atts['show_on_hover'] ) {
		$wrapper_classes[] = 'cph-stats-photos--hover';
	}
	if ( 'yes' === $atts['show_randomly'] ) {
		$wrapper_classes[] = 'cph-stats-photos--random';
	}
	if ( ! empty( $atts['el_class'] ) ) {
		$wrapper_classes[] = esc_attr( $atts['el_class'] );
	}

	// Sanitize values.
	$container_height        = sanitize_text_field( $atts['container_height'] );
	$container_height_tablet = sanitize_text_field( $atts['container_height_tablet'] );
	$container_height_phone  = sanitize_text_field( $atts['container_height_phone'] );
	$image_alignment         = sanitize_text_field( $atts['image_alignment'] );
	$overlay_color           = sanitize_text_field( $atts['overlay_color'] );
	$overlay_opacity         = floatval( $atts['overlay_opacity'] );
	$text_color              = sanitize_text_field( $atts['text_color'] );
	$value_font_size         = sanitize_text_field( $atts['value_font_size'] );
	$value_font_size_tablet  = sanitize_text_field( $atts['value_font_size_tablet'] );
	$value_font_size_phone   = sanitize_text_field( $atts['value_font_size_phone'] );
	$label_font_size         = sanitize_text_field( $atts['label_font_size'] );
	$label_font_size_tablet  = sanitize_text_field( $atts['label_font_size_tablet'] );
	$label_font_size_phone   = sanitize_text_field( $atts['label_font_size_phone'] );
	$random_interval         = floatval( $atts['random_interval'] );

	// Build desktop CSS custom properties.
	$desktop_vars = array(
		'--container-height: ' . esc_attr( $container_height ),
		'--image-alignment: ' . esc_attr( $image_alignment ),
		'--overlay-color: ' . esc_attr( $overlay_color ),
		'--overlay-opacity: ' . esc_attr( $overlay_opacity ),
		'--text-color: ' . esc_attr( $text_color ),
		'--value-font-size: ' . esc_attr( $value_font_size ),
		'--label-font-size: ' . esc_attr( $label_font_size ),
	);

	$element_css = '#' . esc_attr( $element_id ) . ' { ' . implode( '; ', $desktop_vars ) . '; }';

	// Build tablet CSS (max-width: 999px).
	$tablet_vars = array();
	if ( ! empty( $container_height_tablet ) ) {
		$tablet_vars[] = '--container-height: ' . esc_attr( $container_height_tablet );
	}
	if ( ! empty( $value_font_size_tablet ) ) {
		$tablet_vars[] = '--value-font-size: ' . esc_attr( $value_font_size_tablet );
	}
	if ( ! empty( $label_font_size_tablet ) ) {
		$tablet_vars[] = '--label-font-size: ' . esc_attr( $label_font_size_tablet );
	}
	if ( ! empty( $tablet_vars ) ) {
		$element_css .= ' @media only screen and (max-width: 999px) { #' . esc_attr( $element_id ) . ' { ' . implode( '; ', $tablet_vars ) . '; } }';
	}

	// Build phone CSS (max-width: 690px).
	$phone_vars = array();
	if ( ! empty( $container_height_phone ) ) {
		$phone_vars[] = '--container-height: ' . esc_attr( $container_height_phone );
	}
	if ( ! empty( $value_font_size_phone ) ) {
		$phone_vars[] = '--value-font-size: ' . esc_attr( $value_font_size_phone );
	}
	if ( ! empty( $label_font_size_phone ) ) {
		$phone_vars[] = '--label-font-size: ' . esc_attr( $label_font_size_phone );
	}
	if ( ! empty( $phone_vars ) ) {
		$element_css .= ' @media only screen and (max-width: 690px) { #' . esc_attr( $element_id ) . ' { ' . implode( '; ', $phone_vars ) . '; } }';
	}

	// Build data attributes for JS.
	$data_attrs = array();
	if ( 'yes' === $atts['show_on_hover'] ) {
		$data_attrs[] = 'data-show-on-hover="true"';
	}
	if ( 'yes' === $atts['show_randomly'] ) {
		$data_attrs[] = 'data-show-randomly="true"';
		$data_attrs[] = 'data-random-interval="' . esc_attr( $random_interval ) . '"';
	}

	ob_start();
	?>
	<style><?php echo $element_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<div id="<?php echo esc_attr( $element_id ); ?>"
		class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
		<?php echo implode( ' ', $data_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php
		$photo_index = 0;
		foreach ( $photos as $photo ) :
			$image_id       = isset( $photo['image'] ) ? intval( $photo['image'] ) : 0;
			$value          = isset( $photo['value'] ) ? $photo['value'] : '';
			$label          = isset( $photo['label'] ) ? $photo['label'] : '';
			$image_position = isset( $photo['image_position'] ) ? $photo['image_position'] : '';

			// Skip if no image.
			if ( empty( $image_id ) ) {
				continue;
			}

			// Get image URL.
			$image_url = wp_get_attachment_image_url( $image_id, 'full' );
			if ( ! $image_url ) {
				continue;
			}

			// Check if this photo has stats.
			$has_stats = ! empty( $value ) || ! empty( $label );

			// Build item classes.
			$item_classes = array( 'cph-stats-photos__item' );
			if ( $has_stats ) {
				$item_classes[] = 'cph-stats-photos__item--has-stats';
			}

			// Build item styles.
			$item_styles = array();
			if ( ! empty( $image_position ) ) {
				$item_styles[] = '--image-alignment: ' . esc_attr( $image_position );
			}
			?>
			<div class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>"
				data-index="<?php echo esc_attr( $photo_index ); ?>"
				<?php if ( ! empty( $item_styles ) ) : ?>
					style="<?php echo esc_attr( implode( '; ', $item_styles ) ); ?>"
				<?php endif; ?>>
				<div class="cph-stats-photos__image-wrap">
					<img class="cph-stats-photos__image"
						src="<?php echo esc_url( $image_url ); ?>"
						alt="<?php echo esc_attr( $value . ' ' . $label ); ?>"
						loading="lazy" />
				</div>
				<?php if ( $has_stats ) : ?>
					<div class="cph-stats-photos__overlay"></div>
					<div class="cph-stats-photos__content">
						<?php if ( ! empty( $value ) ) : ?>
							<span class="cph-stats-photos__value"><?php echo esc_html( $value ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $label ) ) : ?>
							<span class="cph-stats-photos__label"><?php echo nl2br( esc_html( $label ) ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php
			++$photo_index;
		endforeach;
		?>
	</div>
	<?php
	return ob_get_clean();
}

// Register the shortcode.
add_shortcode( 'cph_stats_photos', 'cph_stats_photos_shortcode' );
