<?php
/**
 * CPH News Grid - WPBakery Element Map
 *
 * Defines the element configuration and parameters for the WPBakery page builder.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get post categories for dropdown.
 *
 * @since 1.0.0
 *
 * @return array Categories formatted for WPBakery dropdown.
 */
function cph_get_post_categories_dropdown() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
		)
	);

	$options = array(
		esc_html__( 'All Categories', 'cph-elements' ) => '',
	);

	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			// Skip "Uncategorized" category.
			if ( 'uncategorized' === $term->slug ) {
				continue;
			}
			$options[ $term->name ] = $term->slug;
		}
	}

	return $options;
}

return array(
	'name'        => esc_html__( 'CPH News Grid', 'cph-elements' ),
	'base'        => 'cph_news_grid',
	'icon'        => 'icon-wpb-application-icon-large',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Display blog posts with featured article, filters, and AJAX load more.', 'cph-elements' ),
	'params'      => array(

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * FEATURED POST SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Featured Post', 'cph-elements' ),
			'param_name' => 'group_header_featured',
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Featured Post Section', 'cph-elements' ),
			'param_name'  => 'show_featured',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Display the latest post in a large featured layout at the top.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Featured Section Title', 'cph-elements' ),
			'param_name'  => 'featured_title',
			'value'       => '',
			'dependency'  => array(
				'element' => 'show_featured',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Title displayed above the featured post (leave empty to hide header).', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * VIEW SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'View Settings', 'cph-elements' ),
			'param_name' => 'group_header_view',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Default View', 'cph-elements' ),
			'param_name'  => 'default_view',
			'value'       => array(
				esc_html__( 'Grid', 'cph-elements' ) => 'grid',
				esc_html__( 'List', 'cph-elements' ) => 'list',
			),
			'std'         => 'grid',
			'description' => esc_html__( 'Initial view mode when page loads.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show View Toggle', 'cph-elements' ),
			'param_name'  => 'show_view_toggle',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Allow users to switch between Grid and List views.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * FILTER SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Filters', 'cph-elements' ),
			'param_name' => 'group_header_filters',
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Category Filter', 'cph-elements' ),
			'param_name'  => 'show_category_filter',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Display category dropdown filter.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Year Filter', 'cph-elements' ),
			'param_name'  => 'show_year_filter',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Display year dropdown filter (auto-populated from post dates).', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Reset Button', 'cph-elements' ),
			'param_name'  => 'show_reset',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Display reset button to clear filters.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * QUERY SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Query Settings', 'cph-elements' ),
			'param_name' => 'group_header_query',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Default Category', 'cph-elements' ),
			'param_name'  => 'default_category',
			'value'       => cph_get_post_categories_dropdown(),
			'description' => esc_html__( 'Optionally filter to a specific category by default.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Posts Per Page', 'cph-elements' ),
			'param_name'  => 'posts_per_page',
			'value'       => '6',
			'description' => esc_html__( 'Number of posts to display initially and load per AJAX request.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Order By', 'cph-elements' ),
			'param_name'  => 'orderby',
			'value'       => array(
				esc_html__( 'Date (Newest First)', 'cph-elements' ) => 'date',
				esc_html__( 'Title', 'cph-elements' )               => 'title',
				esc_html__( 'Menu Order', 'cph-elements' )          => 'menu_order',
			),
			'std'         => 'date',
			'description' => esc_html__( 'How to order the posts.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * CARD CONTENT
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Card Content', 'cph-elements' ),
			'param_name' => 'group_header_content',
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Publication Source', 'cph-elements' ),
			'param_name'  => 'show_source',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Display the publication name from post meta.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Date', 'cph-elements' ),
			'param_name'  => 'show_date',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Display the post date on cards.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Excerpt on Featured', 'cph-elements' ),
			'param_name'  => 'show_featured_excerpt',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Display the excerpt on the featured post.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * STYLING
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Styling', 'cph-elements' ),
			'param_name' => 'group_header_styling',
		),

		// Desktop Grid Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Grid Gap', 'cph-elements' ) . '</span>',
			'param_name'       => 'gap',
			'value'            => '30px',
			'edit_field_class' => 'vc_col-sm-12 desktop news-gap-device-group',
			'description'      => esc_html__( 'Space between grid cards.', 'cph-elements' ),
		),

		// Tablet Grid Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Gap', 'cph-elements' ) . '</span>',
			'param_name'       => 'gap_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet news-gap-device-group',
			'description'      => '',
		),

		// Phone Grid Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Gap', 'cph-elements' ) . '</span>',
			'param_name'       => 'gap_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone news-gap-device-group',
			'description'      => '',
		),

		// Desktop Card Border Radius.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Card Border Radius', 'cph-elements' ) . '</span>',
			'param_name'       => 'card_radius',
			'value'            => '45px',
			'edit_field_class' => 'vc_col-sm-12 desktop card-radius-device-group',
			'description'      => esc_html__( 'Border radius for grid cards.', 'cph-elements' ),
		),

		// Tablet Card Border Radius.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Radius', 'cph-elements' ) . '</span>',
			'param_name'       => 'card_radius_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet card-radius-device-group',
			'description'      => '',
		),

		// Phone Card Border Radius.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Radius', 'cph-elements' ) . '</span>',
			'param_name'       => 'card_radius_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone card-radius-device-group',
			'description'      => '',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Featured Image Radius', 'cph-elements' ),
			'param_name'  => 'featured_image_radius',
			'value'       => '25px',
			'description' => esc_html__( 'Border radius for the featured post image.', 'cph-elements' ),
		),

	), // End params.
);
