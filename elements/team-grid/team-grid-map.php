<?php
/**
 * CPH Team Grid - WPBakery Element Configuration
 *
 * Defines the WPBakery Page Builder element parameters.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get team categories for dropdown.
 *
 * @since 1.0.0
 *
 * @return array Categories formatted for WPBakery dropdown.
 */
function cph_get_team_categories_for_dropdown() {
	$categories = get_terms(
		array(
			'taxonomy'   => 'bti_team_category',
			'hide_empty' => false,
		)
	);

	$options = array(
		__( 'All Categories', 'cph-elements' ) => '',
	);

	if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
		foreach ( $categories as $category ) {
			$options[ $category->name ] = $category->slug;
		}
	}

	return $options;
}

return array(
	'name'        => __( 'CPH Team Grid', 'cph-elements' ),
	'base'        => 'cph_team_grid',
	'description' => __( 'Display team members in a responsive grid layout.', 'cph-elements' ),
	'category'    => __( 'Clear pH Elements', 'cph-elements' ),
	'icon'        => 'icon-wpb-users-customers',
	'params'      => array(
		// Category Filter.
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Team Category', 'cph-elements' ),
			'param_name'  => 'category',
			'value'       => cph_get_team_categories_for_dropdown(),
			'description' => __( 'Select a category to filter team members.', 'cph-elements' ),
		),
		// Number of Columns.
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Columns', 'cph-elements' ),
			'param_name'  => 'columns',
			'value'       => array(
				__( '3 Columns', 'cph-elements' ) => '3',
				__( '2 Columns', 'cph-elements' ) => '2',
				__( '4 Columns', 'cph-elements' ) => '4',
			),
			'std'         => '3',
			'description' => __( 'Number of columns in the grid.', 'cph-elements' ),
		),
		// Posts Per Page.
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Number of Members', 'cph-elements' ),
			'param_name'  => 'posts_per_page',
			'value'       => '9',
			'description' => __( 'How many team members to display.', 'cph-elements' ),
		),
		// Card Height.
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Card Height', 'cph-elements' ),
			'param_name'  => 'card_height',
			'value'       => '544px',
			'description' => __( 'Height of each card (CSS value, e.g., 544px, 50vh).', 'cph-elements' ),
		),
		// Column Gap.
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Column Gap', 'cph-elements' ),
			'param_name'  => 'gap',
			'value'       => '30px',
			'description' => __( 'Horizontal space between cards (px, vh, vw, %).', 'cph-elements' ),
		),
		// Row Gap.
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Row Gap', 'cph-elements' ),
			'param_name'  => 'row_gap',
			'value'       => '30px',
			'description' => __( 'Vertical space between rows (px, vh, vw, %).', 'cph-elements' ),
		),
		// Extra Class.
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra Class Name', 'cph-elements' ),
			'param_name'  => 'el_class',
			'value'       => '',
			'description' => __( 'Add a custom CSS class for styling.', 'cph-elements' ),
		),
	),
);
