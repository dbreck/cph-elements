<?php
/**
 * Arrow Button - Shortcode Rendering
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Convert a nectar_numerical value to a CSS length. Mirrors Salient: a bare
 * number is px, a value containing % is a percentage. Negatives are kept.
 *
 * @since 1.0.0
 *
 * @param string $value Raw value from the editor.
 * @return string CSS length (e.g. "50px"), or empty string if blank.
 */
function cph_arrow_button_len( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( false !== strpos( $value, '%' ) ) {
		return intval( $value ) . '%';
	}
	return intval( $value ) . 'px';
}

/**
 * Build padding/margin/transform CSS declarations for one device.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @param array $pad  Map of side => attribute key for padding.
 * @param array $mar  Map of side => attribute key for margin.
 * @param array $tf   Map of ty|tx|scale|rotate => attribute key for transform.
 * @return string CSS declarations, empty if nothing set.
 */
function cph_arrow_button_device_css( $atts, $pad, $mar, $tf ) {
	$decls = '';

	foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
		$val = cph_arrow_button_len( isset( $atts[ $pad[ $side ] ] ) ? $atts[ $pad[ $side ] ] : '' );
		if ( '' !== $val ) {
			$decls .= 'padding-' . $side . ':' . $val . ';';
		}
	}

	foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
		$val = cph_arrow_button_len( isset( $atts[ $mar[ $side ] ] ) ? $atts[ $mar[ $side ] ] : '' );
		if ( '' !== $val ) {
			$decls .= 'margin-' . $side . ':' . $val . ';';
		}
	}

	// Transform: combine translate + scale + rotate.
	$ty     = trim( (string) ( isset( $atts[ $tf['ty'] ] ) ? $atts[ $tf['ty'] ] : '' ) );
	$tx     = trim( (string) ( isset( $atts[ $tf['tx'] ] ) ? $atts[ $tf['tx'] ] : '' ) );
	$scale  = trim( (string) ( isset( $atts[ $tf['scale'] ] ) ? $atts[ $tf['scale'] ] : '' ) );
	$rotate = trim( (string) ( isset( $atts[ $tf['rotate'] ] ) ? $atts[ $tf['rotate'] ] : '' ) );

	$transform = '';
	if ( '' !== $ty && 0.0 !== (float) $ty ) {
		$transform .= 'translateY(' . cph_arrow_button_len( $ty ) . ') ';
	}
	if ( '' !== $tx && 0.0 !== (float) $tx ) {
		$transform .= 'translateX(' . cph_arrow_button_len( $tx ) . ') ';
	}
	if ( '' !== $scale && 1.0 !== (float) $scale ) {
		$transform .= 'scale(' . floatval( $scale ) . ') ';
	}
	if ( '' !== $rotate && 0.0 !== (float) $rotate ) {
		$transform .= 'rotate(' . floatval( $rotate ) . 'deg) ';
	}
	if ( '' !== $transform ) {
		$decls .= 'transform:' . trim( $transform ) . ';';
	}

	return $decls;
}

/**
 * Render the Arrow Button shortcode.
 *
 * @since 1.0.0
 *
 * @param array  $atts    Shortcode attributes.
 * @param string $content Shortcode content (unused).
 * @param string $tag     Shortcode tag.
 * @return string HTML output.
 */
function cph_arrow_button_shortcode( $atts = array(), $content = '', $tag = '' ) {

	$atts = shortcode_atts(
		array(
			'link'          => '',
			'aria_label'    => 'View more',
			'align'         => 'center',
			'width'         => '140',
			'stroke_width'  => '1',
			'color'         => '#ffffff',
			'hover_color'   => '',
			'hover_effect'  => 'slide',

			// Spacing & Transform (Salient Row controls). Padding.
			'top_padding'           => '',
			'bottom_padding'        => '',
			'left_padding_desktop'  => '',
			'right_padding_desktop' => '',
			'top_padding_tablet'    => '',
			'bottom_padding_tablet' => '',
			'left_padding_tablet'   => '',
			'right_padding_tablet'  => '',
			'top_padding_phone'     => '',
			'bottom_padding_phone'  => '',
			'left_padding_phone'    => '',
			'right_padding_phone'   => '',

			// Margin.
			'top_margin'            => '',
			'bottom_margin'         => '',
			'left_margin'           => '',
			'right_margin'          => '',
			'top_margin_tablet'     => '',
			'bottom_margin_tablet'  => '',
			'left_margin_tablet'    => '',
			'right_margin_tablet'   => '',
			'top_margin_phone'      => '',
			'bottom_margin_phone'   => '',
			'left_margin_phone'     => '',
			'right_margin_phone'    => '',

			// Transform.
			'translate_y'           => '',
			'translate_x'           => '',
			'scale_desktop'         => '1',
			'rotate_desktop'        => '',
			'translate_y_tablet'    => '',
			'translate_x_tablet'    => '',
			'scale_tablet'          => '1',
			'rotate_tablet'         => '',
			'translate_y_phone'     => '',
			'translate_x_phone'     => '',
			'scale_phone'           => '1',
			'rotate_phone'          => '',

			'css'           => '',
			'el_class'      => '',
			'el_id'         => '',
		),
		$atts,
		$tag
	);

	// Parse the WPBakery link field.
	$href   = '#';
	$target = '';
	$rel    = '';
	$title  = '';
	if ( ! empty( $atts['link'] ) && function_exists( 'vc_build_link' ) ) {
		$link = vc_build_link( $atts['link'] );
		if ( ! empty( $link['url'] ) ) {
			$href = $link['url'];
		}
		if ( ! empty( $link['target'] ) ) {
			$target = trim( $link['target'] );
		}
		if ( ! empty( $link['rel'] ) ) {
			$rel = trim( $link['rel'] );
		}
		if ( ! empty( $link['title'] ) ) {
			$title = $link['title'];
		}
	}

	// Sanitize numerics.
	$width        = max( 1, intval( $atts['width'] ) );
	$stroke_width = is_numeric( $atts['stroke_width'] ) ? floatval( $atts['stroke_width'] ) : 1;

	// Alignment.
	$allowed_align = array( 'left', 'center', 'right' );
	$align         = in_array( $atts['align'], $allowed_align, true ) ? $atts['align'] : 'center';

	// Hover effect.
	$allowed_effect = array( 'slide', 'grow', 'none' );
	$hover_effect   = in_array( $atts['hover_effect'], $allowed_effect, true ) ? $atts['hover_effect'] : 'slide';

	// Spacing & Transform CSS per device (Padding + Margin + Transform).
	$css_desktop = cph_arrow_button_device_css(
		$atts,
		array( 'top' => 'top_padding', 'right' => 'right_padding_desktop', 'bottom' => 'bottom_padding', 'left' => 'left_padding_desktop' ),
		array( 'top' => 'top_margin', 'right' => 'right_margin', 'bottom' => 'bottom_margin', 'left' => 'left_margin' ),
		array( 'ty' => 'translate_y', 'tx' => 'translate_x', 'scale' => 'scale_desktop', 'rotate' => 'rotate_desktop' )
	);
	$css_tablet = cph_arrow_button_device_css(
		$atts,
		array( 'top' => 'top_padding_tablet', 'right' => 'right_padding_tablet', 'bottom' => 'bottom_padding_tablet', 'left' => 'left_padding_tablet' ),
		array( 'top' => 'top_margin_tablet', 'right' => 'right_margin_tablet', 'bottom' => 'bottom_margin_tablet', 'left' => 'left_margin_tablet' ),
		array( 'ty' => 'translate_y_tablet', 'tx' => 'translate_x_tablet', 'scale' => 'scale_tablet', 'rotate' => 'rotate_tablet' )
	);
	$css_phone = cph_arrow_button_device_css(
		$atts,
		array( 'top' => 'top_padding_phone', 'right' => 'right_padding_phone', 'bottom' => 'bottom_padding_phone', 'left' => 'left_padding_phone' ),
		array( 'top' => 'top_margin_phone', 'right' => 'right_margin_phone', 'bottom' => 'bottom_margin_phone', 'left' => 'left_margin_phone' ),
		array( 'ty' => 'translate_y_phone', 'tx' => 'translate_x_phone', 'scale' => 'scale_phone', 'rotate' => 'rotate_phone' )
	);

	// Build the link inline style (CSS custom properties only).
	$btn_style  = '--cph-ab-width:' . $width . 'px;';
	$btn_style .= '--cph-ab-color:' . esc_attr( $atts['color'] ) . ';';
	if ( ! empty( $atts['hover_color'] ) ) {
		$btn_style .= '--cph-ab-hover-color:' . esc_attr( $atts['hover_color'] ) . ';';
	}

	// Wrapper classes (alignment + Design Options + extra class).
	$wrap_classes = array(
		'cph-arrow-button-wrap',
		'cph-arrow-button-wrap--align-' . $align,
	);
	if ( ! empty( $atts['css'] ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$wrap_classes[] = vc_shortcode_custom_css_class( $atts['css'], ' ' );
	}
	if ( ! empty( $atts['el_class'] ) ) {
		$wrap_classes[] = $atts['el_class'];
	}

	// Button classes.
	$btn_classes = array(
		'cph-arrow-button',
		'cph-arrow-button--hover-' . $hover_effect,
	);

	// Per-instance class + scoped CSS for Spacing & Transform across devices.
	static $instance = 0;
	++$instance;
	$style_block = '';
	if ( '' !== $css_desktop || '' !== $css_tablet || '' !== $css_phone ) {
		$uid           = 'cph-arrow-button--i' . $instance;
		$btn_classes[] = $uid;
		if ( '' !== $css_desktop ) {
			$style_block .= '.' . $uid . '{' . $css_desktop . '}';
		}
		if ( '' !== $css_tablet ) {
			$style_block .= '@media only screen and (max-width:999px){.' . $uid . '{' . $css_tablet . '}}';
		}
		if ( '' !== $css_phone ) {
			$style_block .= '@media only screen and (max-width:690px){.' . $uid . '{' . $css_phone . '}}';
		}
	}

	// Anchor attributes.
	$attr  = ' href="' . esc_url( $href ) . '"';
	$attr .= ' style="' . esc_attr( $btn_style ) . '"';
	if ( $target ) {
		$attr .= ' target="' . esc_attr( $target ) . '"';
	}
	if ( $rel ) {
		$attr .= ' rel="' . esc_attr( $rel ) . '"';
	}
	if ( $title ) {
		$attr .= ' title="' . esc_attr( $title ) . '"';
	}
	if ( ! empty( $atts['aria_label'] ) ) {
		$attr .= ' aria-label="' . esc_attr( $atts['aria_label'] ) . '"';
	}

	$wrap_id = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';
	$sw      = esc_attr( $stroke_width );

	ob_start();
	if ( '' !== $style_block ) {
		echo '<style>' . $style_block . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>
	<div class="<?php echo esc_attr( implode( ' ', array_filter( $wrap_classes ) ) ); ?>"<?php echo $wrap_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<a class="<?php echo esc_attr( implode( ' ', $btn_classes ) ); ?>"<?php echo $attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<span class="cph-arrow-button__icon">
				<svg viewBox="0 0 140 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
					<rect x="0.5" y="0.5" width="139" height="49" rx="24.5" stroke="currentColor" stroke-width="<?php echo $sw; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"></rect>
					<path d="M45 25H95M95 25L89 20M95 25L89 30" stroke="currentColor" stroke-width="<?php echo $sw; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" stroke-linecap="round" stroke-linejoin="round"></path>
				</svg>
			</span>
		</a>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'cph_arrow_button', 'cph_arrow_button_shortcode' );
