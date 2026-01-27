<?php
/**
 * CPH Stats Grid - WPBakery Element Map
 *
 * Defines the element configuration and parameters for the WPBakery page builder.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Column options.
$column_options = array(
	esc_html__( '2 Columns', 'cph-elements' ) => '2',
	esc_html__( '3 Columns', 'cph-elements' ) => '3',
	esc_html__( '4 Columns', 'cph-elements' ) => '4',
);

// Divider style options.
$divider_options = array(
	esc_html__( 'None', 'cph-elements' )                          => 'none',
	esc_html__( 'Internal Only (no outer border)', 'cph-elements' ) => 'internal',
	esc_html__( 'Full Grid (outer border + internal)', 'cph-elements' ) => 'full',
);

return array(
	'name'        => esc_html__( 'CPH Stats Grid', 'cph-elements' ),
	'base'        => 'cph_stats_grid',
	'icon'        => 'icon-wpb-graph',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Display statistics in a configurable grid layout with dividers.', 'cph-elements' ),
	'params'      => array(

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * LAYOUT SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Layout', 'cph-elements' ),
			'param_name' => 'group_header_layout',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Number of Columns', 'cph-elements' ),
			'param_name'  => 'columns',
			'value'       => $column_options,
			'std'         => '3',
			'admin_label' => true,
			'description' => esc_html__( 'Number of columns in the grid.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Divider Style', 'cph-elements' ),
			'param_name'  => 'divider_style',
			'value'       => $divider_options,
			'std'         => 'full',
			'description' => esc_html__( 'Style of divider lines between grid cells.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Divider Color', 'cph-elements' ),
			'param_name'  => 'divider_color',
			'value'       => '#000000',
			'dependency'  => array(
				'element'            => 'divider_style',
				'value_not_equal_to' => array( 'none' ),
			),
			'description' => esc_html__( 'Color of the divider lines.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * STAT ITEMS (REPEATER)
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Statistics', 'cph-elements' ),
			'param_name' => 'group_header_stats',
		),

		array(
			'type'        => 'param_group',
			'heading'     => esc_html__( 'Stat Items', 'cph-elements' ),
			'param_name'  => 'stats',
			'description' => esc_html__( 'Add statistics to display in the grid.', 'cph-elements' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Label', 'cph-elements' ),
					'param_name'  => 'label',
					'admin_label' => true,
					'description' => esc_html__( 'The stat label (e.g., "RESIDENTIAL UNITS").', 'cph-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Value', 'cph-elements' ),
					'param_name'  => 'value',
					'admin_label' => true,
					'description' => esc_html__( 'The stat value (e.g., "52" or "$1.5M").', 'cph-elements' ),
				),
			),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * TYPOGRAPHY
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Typography', 'cph-elements' ),
			'param_name' => 'group_header_typography',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Label Font Size', 'cph-elements' ),
			'param_name'  => 'label_font_size',
			'value'       => '28px',
			'description' => esc_html__( 'Font size for stat labels.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Value Font Size', 'cph-elements' ),
			'param_name'  => 'value_font_size',
			'value'       => '43px',
			'description' => esc_html__( 'Font size for stat values.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Label Color', 'cph-elements' ),
			'param_name'  => 'label_color',
			'value'       => '#000000',
			'description' => esc_html__( 'Color for stat labels.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Value Color', 'cph-elements' ),
			'param_name'  => 'value_color',
			'value'       => '#000000',
			'description' => esc_html__( 'Color for stat values.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * SPACING
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Spacing', 'cph-elements' ),
			'param_name' => 'group_header_spacing',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Cell Padding', 'cph-elements' ),
			'param_name'  => 'cell_padding',
			'value'       => '40px',
			'description' => esc_html__( 'Padding inside each grid cell.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Gap Between Label and Value', 'cph-elements' ),
			'param_name'  => 'label_value_gap',
			'value'       => '10px',
			'description' => esc_html__( 'Vertical space between label and value.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * EXTRA
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Extra', 'cph-elements' ),
			'param_name' => 'group_header_extra',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra Class Name', 'cph-elements' ),
			'param_name'  => 'el_class',
			'value'       => '',
			'description' => esc_html__( 'Add custom CSS class(es) to the wrapper element.', 'cph-elements' ),
		),

	), // End params.
);
