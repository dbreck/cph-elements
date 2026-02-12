<?php
/**
 * CPH Animated Vert Line - Shortcode Rendering
 *
 * Renders a decorative vertical line with optional grow animation.
 * Uses a zero-height wrapper so the line can straddle section boundaries.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the animated vert line shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_animated_vert_line_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			// General.
			'line_color'         => '#87A80E',
			'animate'            => 'top-to-bottom',
			'animation_duration' => '0.8s',
			'el_class'           => '',
			// Layout.
			'height'             => '185px',
			'height_tablet'      => '',
			'height_phone'       => '',
			'width'              => '2px',
			'width_tablet'       => '',
			'width_phone'        => '',
			'align'              => 'center',
			'align_tablet'       => '',
			'align_phone'        => '',
			'top'                => '',
			'top_tablet'         => '',
			'top_phone'          => '',
		),
		$atts,
		'cph_animated_vert_line'
	);

	// Generate unique ID.
	static $instance_id = 0;
	++$instance_id;
	$id = 'cph-avl-' . $instance_id;

	// Sanitize.
	$valid_animate = array( 'top-to-bottom', 'bottom-to-top', 'none' );
	$animate       = in_array( $atts['animate'], $valid_animate, true ) ? $atts['animate'] : 'top-to-bottom';

	$height         = sanitize_text_field( $atts['height'] ) ?: '185px';
	$width          = sanitize_text_field( $atts['width'] ) ?: '2px';
	$align          = sanitize_text_field( $atts['align'] ) ?: 'center';
	$top            = sanitize_text_field( $atts['top'] );
	$anim_duration  = sanitize_text_field( $atts['animation_duration'] ) ?: '0.8s';
	$line_color     = sanitize_text_field( $atts['line_color'] ) ?: '#87A80E';

	// Alignment → CSS custom properties.
	$align_left     = 'center' === $align ? '50%' : $align;
	$align_translate = 'center' === $align ? '-50%' : '0';

	// Desktop CSS custom properties.
	$css_vars = array(
		'--line-height: ' . esc_attr( $height ),
		'--line-width: ' . esc_attr( $width ),
		'--line-left: ' . esc_attr( $align_left ),
		'--line-translate-x: ' . esc_attr( $align_translate ),
		'--line-color: ' . esc_attr( $line_color ),
		'--line-animation-duration: ' . esc_attr( $anim_duration ),
	);

	// Top — only set when explicit value provided. Empty = auto-center via CSS calc fallback.
	if ( '' !== $top ) {
		$css_vars[] = '--line-top: ' . esc_attr( $top );
	}

	$selector    = '#' . esc_attr( $id ) . ' .cph-animated-vert-line';
	$element_css = $selector . ' { ' . implode( '; ', $css_vars ) . '; }';

	// Responsive overrides — tablet (≤999px) and phone (≤690px).
	foreach ( array( 'tablet' => '999px', 'phone' => '690px' ) as $device => $breakpoint ) {
		$vars = array();

		// Height.
		$val = sanitize_text_field( $atts[ 'height_' . $device ] );
		if ( '' !== $val ) {
			$vars[] = '--line-height: ' . esc_attr( $val );
		}

		// Width.
		$val = sanitize_text_field( $atts[ 'width_' . $device ] );
		if ( '' !== $val ) {
			$vars[] = '--line-width: ' . esc_attr( $val );
		}

		// Top — only set when explicit value provided.
		$val = sanitize_text_field( $atts[ 'top_' . $device ] );
		if ( '' !== $val ) {
			$vars[] = '--line-top: ' . esc_attr( $val );
		}

		// Align — maps to both --line-left and --line-translate-x.
		$val = sanitize_text_field( $atts[ 'align_' . $device ] );
		if ( '' !== $val ) {
			if ( 'center' === $val ) {
				$vars[] = '--line-left: 50%';
				$vars[] = '--line-translate-x: -50%';
			} else {
				$vars[] = '--line-left: ' . esc_attr( $val );
				$vars[] = '--line-translate-x: 0';
			}
		}

		if ( ! empty( $vars ) ) {
			$element_css .= ' @media only screen and (max-width: ' . $breakpoint . ') { ' . $selector . ' { ' . implode( '; ', $vars ) . '; } }';
		}
	}

	// Wrapper classes.
	$wrapper_classes = array( 'cph-animated-vert-line-wrap' );
	if ( ! empty( $atts['el_class'] ) ) {
		$wrapper_classes[] = esc_attr( $atts['el_class'] );
	}

	// Data attribute for animation.
	$data_attr = '';
	if ( 'none' !== $animate ) {
		$data_attr = ' data-animate="' . esc_attr( $animate ) . '"';
	}

	ob_start();
	?>
	<style><?php echo $element_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<div id="<?php echo esc_attr( $id ); ?>"
		 class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
		 aria-hidden="true">
		<div class="cph-animated-vert-line"<?php echo $data_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></div>
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'cph_animated_vert_line', 'cph_animated_vert_line_shortcode' );
