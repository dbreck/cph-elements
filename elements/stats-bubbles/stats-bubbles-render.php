<?php
/**
 * CPH Stats Bubbles - Shortcode Rendering
 *
 * Renders the stats bubbles element with configurable layouts and animations.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the stats bubbles shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_stats_bubbles_shortcode( $atts ) {
	// Extract attributes with defaults.
	$atts = shortcode_atts(
		array(
			// Bubbles repeater.
			'bubbles'                 => '',
			// Layout.
			'layout_pattern'          => 'diagonal',
			'container_height'        => '600px',
			'container_height_tablet' => '',
			'container_height_phone'  => '',
			'min_bubble_size'         => '150px',
			'max_bubble_size'         => '350px',
			'auto_bubble_size'        => '',
			'auto_size_padding'       => '30',
			// Style.
			'bubble_color'            => '#E14A13',
			'text_color'              => '#ffffff',
			'value_font_size'         => '48px',
			'value_font_size_tablet'  => '',
			'value_font_size_phone'   => '',
			'label_font_size'         => '20px',
			'label_font_size_tablet'  => '',
			'label_font_size_phone'   => '',
			'enable_shadow'           => 'yes',
			// Animation.
			'enable_countup'          => 'yes',
			'animation_duration'      => '2',
			'animation_easing'        => 'power2.out',
			'enable_scramble'         => '',
			// Extra.
			'el_class'                => '',
		),
		$atts,
		'cph_stats_bubbles'
	);

	// Parse the bubbles param_group.
	$bubbles = array();
	if ( ! empty( $atts['bubbles'] ) ) {
		$bubbles = vc_param_group_parse_atts( $atts['bubbles'] );
	}

	// Return early if no bubbles.
	if ( empty( $bubbles ) ) {
		return '';
	}

	// Generate unique ID for this instance.
	static $instance_id = 0;
	++$instance_id;
	$element_id = 'cph-stats-bubbles-' . $instance_id;

	$auto_bubble_size  = 'yes' === $atts['auto_bubble_size'];
	$auto_size_padding = max( 0, intval( $atts['auto_size_padding'] ) );

	// Build wrapper classes.
	$wrapper_classes = array(
		'cph-stats-bubbles',
		'cph-stats-bubbles--pattern-' . esc_attr( $atts['layout_pattern'] ),
	);
	if ( 'yes' === $atts['enable_shadow'] ) {
		$wrapper_classes[] = 'cph-stats-bubbles--shadow';
	}
	if ( $auto_bubble_size ) {
		$wrapper_classes[] = 'cph-stats-bubbles--auto';
	}
	if ( ! empty( $atts['el_class'] ) ) {
		$wrapper_classes[] = esc_attr( $atts['el_class'] );
	}

	// Sanitize values.
	$container_height        = sanitize_text_field( $atts['container_height'] );
	$container_height_tablet = sanitize_text_field( $atts['container_height_tablet'] );
	$container_height_phone  = sanitize_text_field( $atts['container_height_phone'] );
	$min_bubble_size         = sanitize_text_field( $atts['min_bubble_size'] );
	$max_bubble_size         = sanitize_text_field( $atts['max_bubble_size'] );
	$bubble_color            = sanitize_text_field( $atts['bubble_color'] );
	$text_color              = sanitize_text_field( $atts['text_color'] );
	$value_font_size         = sanitize_text_field( $atts['value_font_size'] );
	$value_font_size_tablet  = sanitize_text_field( $atts['value_font_size_tablet'] );
	$value_font_size_phone   = sanitize_text_field( $atts['value_font_size_phone'] );
	$label_font_size         = sanitize_text_field( $atts['label_font_size'] );
	$label_font_size_tablet  = sanitize_text_field( $atts['label_font_size_tablet'] );
	$label_font_size_phone   = sanitize_text_field( $atts['label_font_size_phone'] );
	$enable_countup          = 'yes' === $atts['enable_countup'];
	$animation_duration      = floatval( $atts['animation_duration'] );
	$animation_easing        = sanitize_text_field( $atts['animation_easing'] );
	$enable_scramble         = 'yes' === $atts['enable_scramble'];

	// Calculate bubble sizes based on min/max.
	$min_size = intval( $min_bubble_size );
	$max_size = intval( $max_bubble_size );
	$mid_size = $min_size + ( ( $max_size - $min_size ) / 2 );

	$size_map = array(
		'small'  => $min_size . 'px',
		'medium' => $mid_size . 'px',
		'large'  => $max_size . 'px',
	);

	// Build desktop CSS custom properties.
	$desktop_vars = array(
		'--container-height: ' . esc_attr( $container_height ),
		'--min-bubble-size: ' . esc_attr( $min_bubble_size ),
		'--max-bubble-size: ' . esc_attr( $max_bubble_size ),
		'--bubble-color: ' . esc_attr( $bubble_color ),
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
	$data_attrs = array(
		'data-pattern="' . esc_attr( $atts['layout_pattern'] ) . '"',
		'data-bubble-count="' . count( $bubbles ) . '"',
	);
	if ( $auto_bubble_size ) {
		$data_attrs[] = 'data-auto-size="true"';
		$data_attrs[] = 'data-auto-size-padding="' . esc_attr( $auto_size_padding ) . '"';
	}
	if ( $enable_countup ) {
		$data_attrs[] = 'data-enable-countup="true"';
		$data_attrs[] = 'data-animation-duration="' . esc_attr( $animation_duration ) . '"';
		$data_attrs[] = 'data-animation-easing="' . esc_attr( $animation_easing ) . '"';
		if ( $enable_scramble ) {
			$data_attrs[] = 'data-enable-scramble="true"';
		}
	}

	ob_start();
	?>
	<style><?php echo $element_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<div id="<?php echo esc_attr( $element_id ); ?>"
		class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
		<?php echo implode( ' ', $data_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php
		$bubble_index = 0;
		foreach ( $bubbles as $bubble ) :
			$value          = isset( $bubble['value'] ) ? $bubble['value'] : '';
			$label          = isset( $bubble['label'] ) ? $bubble['label'] : '';
			$size           = isset( $bubble['size'] ) ? $bubble['size'] : 'medium';
			$color_override = isset( $bubble['color_override'] ) ? $bubble['color_override'] : '';

			// Skip if no value.
			if ( empty( $value ) ) {
				continue;
			}

			// Determine bubble size.
			$bubble_size = isset( $size_map[ $size ] ) ? $size_map[ $size ] : $size_map['medium'];

			// Build bubble classes. In auto mode the size class is irrelevant
			// because JS will measure each bubble's text and write the diameter.
			$bubble_classes = array( 'cph-stats-bubbles__bubble' );
			if ( ! $auto_bubble_size ) {
				$bubble_classes[] = 'cph-stats-bubbles__bubble--' . esc_attr( $size );
			}

			// Build inline styles. In auto mode the size is written by JS
			// after measuring the rendered text content, so skip --bubble-size here.
			$bubble_styles = array();
			if ( ! $auto_bubble_size ) {
				$bubble_styles[] = '--bubble-size: ' . esc_attr( $bubble_size );
			}
			if ( ! empty( $color_override ) ) {
				$bubble_styles[] = '--bubble-color: ' . esc_attr( $color_override );
			}

			// Parse value for animation data.
			$parsed = cph_stats_bubbles_parse_value( $value );
			?>
			<div class="<?php echo esc_attr( implode( ' ', $bubble_classes ) ); ?>"
				style="<?php echo esc_attr( implode( '; ', $bubble_styles ) ); ?>"
				data-index="<?php echo esc_attr( $bubble_index ); ?>"
				data-raw-value="<?php echo esc_attr( $parsed['number'] ); ?>"
				data-prefix="<?php echo esc_attr( $parsed['prefix'] ); ?>"
				data-suffix="<?php echo esc_attr( $parsed['suffix'] ); ?>"
				data-decimals="<?php echo esc_attr( $parsed['decimals'] ); ?>"
				data-has-commas="<?php echo esc_attr( $parsed['has_commas'] ? 'true' : 'false' ); ?>">
				<span class="cph-stats-bubbles__value"><?php echo esc_html( $value ); ?></span>
				<?php if ( ! empty( $label ) ) : ?>
					<span class="cph-stats-bubbles__label"><?php echo esc_html( $label ); ?></span>
				<?php endif; ?>
			</div>
			<?php
			++$bubble_index;
		endforeach;
		?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Parse a value string to extract numeric parts and suffixes/prefixes.
 *
 * @since 1.0.0
 *
 * @param string $value The value to parse (e.g., "16,902", "4th", "$500K", "45.4%").
 * @return array Parsed components.
 */
function cph_stats_bubbles_parse_value( $value ) {
	$result = array(
		'number'     => 0,
		'prefix'     => '',
		'suffix'     => '',
		'decimals'   => 0,
		'has_commas' => false,
	);

	if ( empty( $value ) ) {
		return $result;
	}

	// Check for commas.
	$result['has_commas'] = strpos( $value, ',' ) !== false;

	// Remove commas for parsing.
	$clean_value = str_replace( ',', '', $value );

	// Match pattern: optional prefix + number + optional suffix.
	// Supports: $500, 4th, 45.4%, 16902, etc.
	if ( preg_match( '/^([^\d]*)([\d.]+)(.*)$/', $clean_value, $matches ) ) {
		$result['prefix'] = $matches[1];
		$result['number'] = floatval( $matches[2] );
		$result['suffix'] = $matches[3];

		// Count decimal places.
		if ( strpos( $matches[2], '.' ) !== false ) {
			$parts              = explode( '.', $matches[2] );
			$result['decimals'] = strlen( $parts[1] );
		}
	}

	return $result;
}

// Register the shortcode.
add_shortcode( 'cph_stats_bubbles', 'cph_stats_bubbles_shortcode' );
