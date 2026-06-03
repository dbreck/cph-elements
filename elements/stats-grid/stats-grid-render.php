<?php
/**
 * CPH Stats Grid - Shortcode Rendering
 *
 * Renders the stats grid element with configurable columns and dividers.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the stats grid shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_stats_grid_shortcode( $atts ) {
	// Extract attributes with defaults.
	$atts = shortcode_atts(
		array(
			'columns'         => '3',
			'divider_style'   => 'full',
			'divider_color'   => '#000000',
			'stats'           => '',
			'label_font_size' => '28px',
			'value_font_size' => '43px',
			'label_color'     => '#000000',
			'value_color'     => '#000000',
			'cell_padding'    => '40px',
			'label_value_gap' => '10px',
			'el_class'        => '',
		),
		$atts,
		'cph_stats_grid'
	);

	// Parse the stats param_group.
	$stats = array();
	if ( ! empty( $atts['stats'] ) ) {
		$stats = vc_param_group_parse_atts( $atts['stats'] );
	}

	// Return early if no stats.
	if ( empty( $stats ) ) {
		return '';
	}

	// Generate unique ID for this instance.
	static $instance_id = 0;
	++$instance_id;
	$grid_id = 'cph-stats-grid-' . $instance_id;

	// Build wrapper classes.
	$wrapper_classes   = array( 'cph-stats-grid' );
	$wrapper_classes[] = 'cph-stats-grid--cols-' . intval( $atts['columns'] );
	if ( 'none' !== $atts['divider_style'] ) {
		$wrapper_classes[] = 'cph-stats-grid--dividers';
		$wrapper_classes[] = 'cph-stats-grid--dividers-' . esc_attr( $atts['divider_style'] );
	}
	if ( ! empty( $atts['el_class'] ) ) {
		$wrapper_classes[] = esc_attr( $atts['el_class'] );
	}

	// Build CSS custom properties for scoped styling.
	$css_vars = array(
		'--columns: ' . intval( $atts['columns'] ),
		'--divider-color: ' . esc_attr( $atts['divider_color'] ),
		'--label-font-size: ' . esc_attr( $atts['label_font_size'] ),
		'--value-font-size: ' . esc_attr( $atts['value_font_size'] ),
		'--label-color: ' . esc_attr( $atts['label_color'] ),
		'--value-color: ' . esc_attr( $atts['value_color'] ),
		'--cell-padding: ' . esc_attr( $atts['cell_padding'] ),
		'--label-value-gap: ' . esc_attr( $atts['label_value_gap'] ),
	);

	// Build responsive CSS using style block.
	$element_css = '#' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $css_vars ) . '; }';

	// Tablet styles (max-width: 999px) - reduce to 2 columns if more than 2.
	$columns = intval( $atts['columns'] );
	if ( $columns > 2 ) {
		$element_css .= ' @media only screen and (max-width: 999px) { #' . esc_attr( $grid_id ) . ' { --columns: 2; } }';
	}

	// Phone styles (max-width: 690px) - single column.
	$element_css .= ' @media only screen and (max-width: 690px) { #' . esc_attr( $grid_id ) . ' { --columns: 1; } }';

	ob_start();
	?>
	<style><?php echo $element_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<div id="<?php echo esc_attr( $grid_id ); ?>" class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
		<?php foreach ( $stats as $index => $stat ) : ?>
			<?php
			$label = isset( $stat['label'] ) ? $stat['label'] : '';
			$value = isset( $stat['value'] ) ? $stat['value'] : '';

			// Skip empty items.
			if ( empty( $label ) && empty( $value ) ) {
				continue;
			}
			?>
			<div class="cph-stats-grid__item">
				<?php if ( ! empty( $label ) ) : ?>
					<span class="cph-stats-grid__label"><?php echo esc_html( $label ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $value ) ) : ?>
					<span class="cph-stats-grid__value"><?php echo esc_html( $value ); ?></span>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

// Register the shortcode.
add_shortcode( 'cph_stats_grid', 'cph_stats_grid_shortcode' );
