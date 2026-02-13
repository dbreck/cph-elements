<?php
/**
 * CPH Expandable Grid - WPBakery Element Map
 *
 * Defines the element configuration and parameters for the WPBakery page builder.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'name'        => esc_html__( 'CPH Expandable Grid', 'cph-elements' ),
	'base'        => 'cph_expandable_grid',
	'icon'        => 'icon-wpb-application-icon-large',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Grid of items with expandable detail panels.', 'cph-elements' ),
	'params'      => array(

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * CONTENT SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Content', 'cph-elements' ),
			'param_name' => 'group_header_content',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Posts Per Page', 'cph-elements' ),
			'param_name'  => 'posts_per_page',
			'value'       => array(
				esc_html__( 'All', 'cph-elements' )  => '-1',
				esc_html__( '3', 'cph-elements' )     => '3',
				esc_html__( '6', 'cph-elements' )     => '6',
				esc_html__( '9', 'cph-elements' )     => '9',
				esc_html__( '12', 'cph-elements' )    => '12',
			),
			'std'         => '-1',
			'admin_label' => true,
			'description' => esc_html__( 'Number of grid items to display.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Order By', 'cph-elements' ),
			'param_name'  => 'orderby',
			'value'       => array(
				esc_html__( 'Menu Order', 'cph-elements' )    => 'menu_order',
				esc_html__( 'Date (Newest)', 'cph-elements' ) => 'date_desc',
				esc_html__( 'Date (Oldest)', 'cph-elements' ) => 'date_asc',
				esc_html__( 'Title', 'cph-elements' )         => 'title',
			),
			'std'         => 'menu_order',
			'description' => esc_html__( 'How to sort the grid items.', 'cph-elements' ),
		),

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
			'heading'     => esc_html__( 'Columns', 'cph-elements' ),
			'param_name'  => 'columns',
			'value'       => array(
				esc_html__( '1 Column', 'cph-elements' )  => '1',
				esc_html__( '2 Columns', 'cph-elements' ) => '2',
				esc_html__( '3 Columns', 'cph-elements' ) => '3',
				esc_html__( '4 Columns', 'cph-elements' ) => '4',
			),
			'std'         => '3',
			'admin_label' => true,
			'description' => esc_html__( 'Number of grid columns.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * BUTTON TEXT
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Labels', 'cph-elements' ),
			'param_name' => 'group_header_labels',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Expand Button Text', 'cph-elements' ),
			'param_name'  => 'button_text',
			'value'       => '+ LOAD MORE',
			'description' => esc_html__( 'Text on each card\'s expand trigger.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Collapse Button Text', 'cph-elements' ),
			'param_name'  => 'close_text',
			'value'       => '+ SHOW LESS',
			'description' => esc_html__( 'Text when item is expanded.', 'cph-elements' ),
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

	),
);
