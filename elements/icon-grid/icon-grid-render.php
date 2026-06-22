<?php
/**
 * CPH Icon Grid - Shortcode Rendering
 *
 * Renders the icon grid element with configurable columns and styling.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the icon grid shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cph_icon_grid_shortcode( $atts ) {
	// Extract attributes with defaults.
	$atts = shortcode_atts(
		array(
			// Desktop values (base).
			'columns'               => '5',
			'cell_height'           => '175px',
			'gap'                   => '30px',
			'row_gap'               => '0px',
			'icon_max_height'       => '100px',
			'label_font_size'       => '28px',
			// Tablet values.
			'columns_tablet'        => '',
			'cell_height_tablet'    => '',
			'gap_tablet'            => '',
			'row_gap_tablet'        => '',
			'icon_max_height_tablet' => '',
			'label_font_size_tablet' => '',
			// Phone values.
			'columns_phone'         => '',
			'cell_height_phone'     => '',
			'gap_phone'             => '',
			'row_gap_phone'         => '',
			'icon_max_height_phone' => '',
			'label_font_size_phone' => '',
			// Colors (not device-specific).
			'icon_color'            => '#000000',
			'label_color'           => '#000000',
			// Label typography / sub-label (not device-specific).
			'label_font_family'      => '',
			'label_font_weight'      => '',
			'label_letter_spacing'   => '',
			'sub_label_color'        => '',
			'sub_label_font_size'    => '',
			'sub_label_margin'       => '',
			'sub_label_font_weight'  => '',
			'sub_label_letter_spacing' => '',
			// Interaction.
			'hover_animation'       => '',
			// Other.
			'items'                 => '',
			'el_class'              => '',
		),
		$atts,
		'cph_icon_grid'
	);

	// Parse the items param_group.
	$items = array();
	if ( ! empty( $atts['items'] ) ) {
		$items = vc_param_group_parse_atts( $atts['items'] );
	}

	// Return early if no items.
	if ( empty( $items ) ) {
		return '';
	}

	// Generate unique ID for this instance.
	static $instance_id = 0;
	++$instance_id;
	$grid_id = 'cph-icon-grid-' . $instance_id;

	// Build wrapper classes.
	$wrapper_classes = array( 'cph-icon-grid' );
	if ( 'zoom' === $atts['hover_animation'] ) {
		$wrapper_classes[] = 'cph-icon-grid--hover-zoom';
	}
	if ( ! empty( $atts['el_class'] ) ) {
		$wrapper_classes[] = esc_attr( $atts['el_class'] );
	}

	// Sanitize desktop values.
	$columns         = intval( $atts['columns'] );
	$cell_height     = sanitize_text_field( $atts['cell_height'] );
	$gap             = sanitize_text_field( $atts['gap'] );
	$row_gap         = sanitize_text_field( $atts['row_gap'] );
	$icon_max_height = sanitize_text_field( $atts['icon_max_height'] );
	$label_font_size = sanitize_text_field( $atts['label_font_size'] );
	$icon_color      = sanitize_text_field( $atts['icon_color'] );
	$label_color     = sanitize_text_field( $atts['label_color'] );

	// Label typography / sub-label (optional — fall back to CSS defaults when blank).
	$label_font_family        = sanitize_text_field( $atts['label_font_family'] );
	$label_font_weight        = sanitize_text_field( $atts['label_font_weight'] );
	$label_letter_spacing     = sanitize_text_field( $atts['label_letter_spacing'] );
	$sub_label_color          = sanitize_text_field( $atts['sub_label_color'] );
	$sub_label_font_size      = sanitize_text_field( $atts['sub_label_font_size'] );
	$sub_label_margin         = sanitize_text_field( $atts['sub_label_margin'] );
	$sub_label_font_weight    = sanitize_text_field( $atts['sub_label_font_weight'] );
	$sub_label_letter_spacing = sanitize_text_field( $atts['sub_label_letter_spacing'] );

	// Sanitize tablet values.
	$columns_tablet         = sanitize_text_field( $atts['columns_tablet'] );
	$cell_height_tablet     = sanitize_text_field( $atts['cell_height_tablet'] );
	$gap_tablet             = sanitize_text_field( $atts['gap_tablet'] );
	$row_gap_tablet         = sanitize_text_field( $atts['row_gap_tablet'] );
	$icon_max_height_tablet = sanitize_text_field( $atts['icon_max_height_tablet'] );
	$label_font_size_tablet = sanitize_text_field( $atts['label_font_size_tablet'] );

	// Sanitize phone values.
	$columns_phone         = sanitize_text_field( $atts['columns_phone'] );
	$cell_height_phone     = sanitize_text_field( $atts['cell_height_phone'] );
	$gap_phone             = sanitize_text_field( $atts['gap_phone'] );
	$row_gap_phone         = sanitize_text_field( $atts['row_gap_phone'] );
	$icon_max_height_phone = sanitize_text_field( $atts['icon_max_height_phone'] );
	$label_font_size_phone = sanitize_text_field( $atts['label_font_size_phone'] );

	// Build desktop CSS custom properties.
	$desktop_vars = array(
		'--columns: ' . $columns,
		'--cell-height: ' . esc_attr( $cell_height ),
		'--gap: ' . esc_attr( $gap ),
		'--row-gap: ' . esc_attr( $row_gap ),
		'--icon-max-height: ' . esc_attr( $icon_max_height ),
		'--label-font-size: ' . esc_attr( $label_font_size ),
		'--icon-color: ' . esc_attr( $icon_color ),
		'--label-color: ' . esc_attr( $label_color ),
	);

	// Optional vars — only emit when set, so CSS defaults (inherit, etc.) win when blank.
	if ( ! empty( $label_font_family ) ) {
		$desktop_vars[] = '--label-font-family: ' . esc_attr( $label_font_family );
	}
	if ( ! empty( $label_font_weight ) ) {
		$desktop_vars[] = '--label-font-weight: ' . esc_attr( $label_font_weight );
	}
	if ( '' !== $label_letter_spacing ) {
		$desktop_vars[] = '--label-letter-spacing: ' . esc_attr( $label_letter_spacing );
	}
	if ( ! empty( $sub_label_color ) ) {
		$desktop_vars[] = '--sub-label-color: ' . esc_attr( $sub_label_color );
	}
	if ( ! empty( $sub_label_font_size ) ) {
		$desktop_vars[] = '--sub-label-font-size: ' . esc_attr( $sub_label_font_size );
	}
	if ( '' !== $sub_label_margin ) {
		$desktop_vars[] = '--sub-label-margin: ' . esc_attr( $sub_label_margin );
	}
	if ( ! empty( $sub_label_font_weight ) ) {
		$desktop_vars[] = '--sub-label-font-weight: ' . esc_attr( $sub_label_font_weight );
	}
	if ( '' !== $sub_label_letter_spacing ) {
		$desktop_vars[] = '--sub-label-letter-spacing: ' . esc_attr( $sub_label_letter_spacing );
	}

	$element_css = '#' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $desktop_vars ) . '; }';

	// Build tablet CSS (max-width: 999px).
	$tablet_vars = array();
	if ( ! empty( $columns_tablet ) ) {
		$tablet_vars[] = '--columns: ' . intval( $columns_tablet );
	}
	if ( ! empty( $cell_height_tablet ) ) {
		$tablet_vars[] = '--cell-height: ' . esc_attr( $cell_height_tablet );
	}
	if ( ! empty( $gap_tablet ) ) {
		$tablet_vars[] = '--gap: ' . esc_attr( $gap_tablet );
	}
	if ( ! empty( $row_gap_tablet ) ) {
		$tablet_vars[] = '--row-gap: ' . esc_attr( $row_gap_tablet );
	}
	if ( ! empty( $icon_max_height_tablet ) ) {
		$tablet_vars[] = '--icon-max-height: ' . esc_attr( $icon_max_height_tablet );
	}
	if ( ! empty( $label_font_size_tablet ) ) {
		$tablet_vars[] = '--label-font-size: ' . esc_attr( $label_font_size_tablet );
	}
	if ( ! empty( $tablet_vars ) ) {
		$element_css .= ' @media only screen and (max-width: 999px) { #' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $tablet_vars ) . '; } }';
	}

	// Build phone CSS (max-width: 690px).
	$phone_vars = array();
	if ( ! empty( $columns_phone ) ) {
		$phone_vars[] = '--columns: ' . intval( $columns_phone );
	}
	if ( ! empty( $cell_height_phone ) ) {
		$phone_vars[] = '--cell-height: ' . esc_attr( $cell_height_phone );
	}
	if ( ! empty( $gap_phone ) ) {
		$phone_vars[] = '--gap: ' . esc_attr( $gap_phone );
	}
	if ( ! empty( $row_gap_phone ) ) {
		$phone_vars[] = '--row-gap: ' . esc_attr( $row_gap_phone );
	}
	if ( ! empty( $icon_max_height_phone ) ) {
		$phone_vars[] = '--icon-max-height: ' . esc_attr( $icon_max_height_phone );
	}
	if ( ! empty( $label_font_size_phone ) ) {
		$phone_vars[] = '--label-font-size: ' . esc_attr( $label_font_size_phone );
	}
	if ( ! empty( $phone_vars ) ) {
		$element_css .= ' @media only screen and (max-width: 690px) { #' . esc_attr( $grid_id ) . ' { ' . implode( '; ', $phone_vars ) . '; } }';
	}

	ob_start();
	?>
	<style><?php echo $element_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<div id="<?php echo esc_attr( $grid_id ); ?>" class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
		<?php
		foreach ( $items as $item ) :
			$icon_preset = isset( $item['icon_preset'] ) ? $item['icon_preset'] : '';
			$custom_icon = isset( $item['custom_icon'] ) ? $item['custom_icon'] : '';
			$label       = isset( $item['label'] ) ? $item['label'] : '';
			$sub_label   = isset( $item['sub_label'] ) ? $item['sub_label'] : '';
			$item_url    = isset( $item['url'] ) ? trim( $item['url'] ) : '';
			$url_blank   = isset( $item['url_blank'] ) && 'yes' === $item['url_blank'];

			// Determine icon URL.
			$icon_url = '';
			if ( 'custom' === $icon_preset && ! empty( $custom_icon ) ) {
				// Media Library selection.
				$icon_url = wp_get_attachment_url( $custom_icon );
			} elseif ( ! empty( $icon_preset ) && 'custom' !== $icon_preset ) {
				// Built-in preset.
				$icon_url = cph_element_url( 'icon-grid' ) . 'assets/icons/' . $icon_preset . '.svg';
			}

			// Skip if no icon and no label.
			if ( empty( $icon_url ) && empty( $label ) && empty( $sub_label ) ) {
				continue;
			}

			// Determine alt text for icon.
			$alt_text = ! empty( $label ) ? $label : $icon_preset;

			// The inner element becomes an anchor when a URL is set.
			$inner_tag   = '';
			$inner_attrs = '';
			if ( ! empty( $item_url ) ) {
				$inner_tag   = 'a';
				$inner_attrs = ' href="' . esc_url( $item_url ) . '"';
				if ( $url_blank ) {
					$inner_attrs .= ' target="_blank" rel="noopener noreferrer"';
				}
			} else {
				$inner_tag = 'div';
			}
			?>
			<div class="cph-icon-grid__item">
				<<?php echo $inner_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="cph-icon-grid__inner"<?php echo $inner_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php if ( ! empty( $icon_url ) ) : ?>
						<img class="cph-icon-grid__icon" src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" loading="lazy" />
					<?php endif; ?>
					<?php if ( ! empty( $label ) || ! empty( $sub_label ) ) : ?>
						<div class="cph-icon-grid__text">
							<?php if ( ! empty( $label ) ) : ?>
								<span class="cph-icon-grid__label"><?php echo esc_html( $label ); ?></span>
							<?php endif; ?>
							<?php if ( ! empty( $sub_label ) ) : ?>
								<span class="cph-icon-grid__sublabel"><?php echo esc_html( $sub_label ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</<?php echo $inner_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

// Register the shortcode.
add_shortcode( 'cph_icon_grid', 'cph_icon_grid_shortcode' );
