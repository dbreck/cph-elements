<?php

/**
 * CPH Horizontal Scroller - WPBakery Element Map
 *
 * Defines the element configuration and parameters for the WPBakery page builder.
 *
 * @package CPH_Elements
 * @since   1.0.0
 */

if (! defined('ABSPATH')) {
	exit;
}

// Post type options.
$post_type_options = array(
	esc_html__('Team Members', 'cph-elements') => 'bti_team',
	esc_html__('Blog Posts', 'cph-elements')   => 'post',
);

// Direction options.
$direction_options = array(
	esc_html__('Left (arrow on left)', 'cph-elements')  => 'left',
	esc_html__('Right (arrow on right)', 'cph-elements') => 'right',
);

// Aspect ratio options.
$aspect_ratio_options = array(
	esc_html__('3:4 Portrait (default)', 'cph-elements') => '3/4',
	esc_html__('4:5 Portrait', 'cph-elements')           => '4/5',
	esc_html__('1:1 Square', 'cph-elements')             => '1/1',
	esc_html__('4:3 Landscape', 'cph-elements')          => '4/3',
	esc_html__('16:9 Widescreen', 'cph-elements')        => '16/9',
	esc_html__('Custom', 'cph-elements')                 => 'custom',
);

// Posts per page options.
$posts_per_page_options = array(
	'3'  => '3',
	'4'  => '4',
	'5'  => '5',
	'6'  => '6',
	'8'  => '8',
	'10' => '10',
	'12' => '12',
);

return array(
	'name'        => esc_html__('CPH Horizontal Scroller', 'cph-elements'),
	'base'        => 'cph_horizontal_scroller',
	'icon'        => 'icon-wpb-ui-separator',
	'category'    => esc_html__('Clear pH Elements', 'cph-elements'),
	'description' => esc_html__('Infinite horizontal carousel with drag/swipe and arrow navigation', 'cph-elements'),
	'params'      => array(

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * CONTENT SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__('Content', 'cph-elements'),
			'param_name' => 'group_header_content',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__('Post Type', 'cph-elements'),
			'param_name'  => 'post_type',
			'value'       => $post_type_options,
			'std'         => 'bti_team',
			'admin_label' => true,
			'description' => esc_html__('Select the content type to display.', 'cph-elements'),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__('Number of Posts', 'cph-elements'),
			'param_name'  => 'posts_per_page',
			'value'       => $posts_per_page_options,
			'std'         => '6',
			'description' => esc_html__('How many items to display in the carousel.', 'cph-elements'),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Order By', 'cph-elements' ),
			'param_name'  => 'orderby',
			'value'       => array(
				esc_html__( 'Menu Order', 'cph-elements' )      => 'menu_order',
				esc_html__( 'Date (Newest First)', 'cph-elements' ) => 'date_desc',
				esc_html__( 'Date (Oldest First)', 'cph-elements' ) => 'date_asc',
				esc_html__( 'Title', 'cph-elements' )           => 'title',
			),
			'std'         => 'menu_order',
			'description' => esc_html__( 'Menu Order requires the Simple Custom Post Order plugin for drag-and-drop sorting.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__('Category', 'cph-elements'),
			'param_name'  => 'category',
			'value'       => cph_get_category_options(),
			'std'         => '',
			'description' => esc_html__('Filter by category (Blog posts only).', 'cph-elements'),
			'dependency'  => array(
				'element' => 'post_type',
				'value'   => array('post'),
			),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Team Category', 'cph-elements' ),
			'param_name'  => 'team_category',
			'value'       => function_exists( 'cph_get_team_categories_for_dropdown' )
				? cph_get_team_categories_for_dropdown()
				: array( esc_html__( 'All Categories', 'cph-elements' ) => '' ),
			'std'         => '',
			'description' => esc_html__( 'Filter by team category.', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'post_type',
				'value'   => array( 'bti_team' ),
			),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Filter Tabs', 'cph-elements' ),
			'param_name'  => 'show_filter',
			'value'       => array(
				esc_html__( 'No', 'cph-elements' )  => 'no',
				esc_html__( 'Yes', 'cph-elements' ) => 'yes',
			),
			'std'         => 'no',
			'description' => esc_html__( 'Show category filter tabs above the scroller.', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'post_type',
				'value'   => array( 'bti_team' ),
			),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Short Bio', 'cph-elements' ),
			'param_name'  => 'show_short_bio',
			'value'       => array(
				esc_html__( 'Yes', 'cph-elements' ) => 'yes',
				esc_html__( 'No', 'cph-elements' )  => 'no',
			),
			'std'         => 'yes',
			'description' => esc_html__( 'Show short bio excerpt on team cards.', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'post_type',
				'value'   => array( 'bti_team' ),
			),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Button Text', 'cph-elements' ),
			'param_name'  => 'button_text',
			'value'       => '+ LOAD MORE',
			'description' => esc_html__( 'Text label for the card button.', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'post_type',
				'value'   => array( 'bti_team' ),
			),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Button Style', 'cph-elements' ),
			'param_name'  => 'button_style',
			'value'       => array(
				esc_html__( 'Text Link', 'cph-elements' )  => 'text-link',
				esc_html__( 'Pill Button', 'cph-elements' ) => 'pill',
			),
			'std'         => 'text-link',
			'description' => esc_html__( 'Button appearance style.', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'post_type',
				'value'   => array( 'bti_team' ),
			),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * LAYOUT SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__('Layout', 'cph-elements'),
			'param_name' => 'group_header_layout',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__('Scroll Direction', 'cph-elements'),
			'param_name'  => 'direction',
			'value'       => $direction_options,
			'std'         => 'left',
			'admin_label' => true,
			'description' => esc_html__('Arrow position and primary scroll direction.', 'cph-elements'),
		),

		// Desktop Card Width.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__('Card Width', 'cph-elements') . '</span>',
			'param_name'       => 'card_width',
			'value'            => '25vw',
			'edit_field_class' => 'vc_col-sm-12 desktop card-width-device-group',
			'description'      => esc_html__('Width of each card. Any CSS unit: px, vw, %.', 'cph-elements'),
		),

		// Tablet Card Width.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__('Width', 'cph-elements') . '</span>',
			'param_name'       => 'card_width_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet card-width-device-group',
			'description'      => '',
		),

		// Phone Card Width.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__('Width', 'cph-elements') . '</span>',
			'param_name'       => 'card_width_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone card-width-device-group',
			'description'      => '',
		),

		// Desktop Visible Cards.
		array(
			'type'             => 'dropdown',
			'heading'          => '<span class="group-title">' . esc_html__( 'Visible Cards', 'cph-elements' ) . '</span>',
			'param_name'       => 'visible_cards',
			'value'            => array(
				esc_html__( 'Auto (use Card Width)', 'cph-elements' ) => '',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
			),
			'std'              => '',
			'edit_field_class'  => 'vc_col-sm-4 desktop visible-cards-device-group',
			'description'      => esc_html__( 'Show exactly this many cards. Overrides Card Width.', 'cph-elements' ),
		),

		// Tablet Visible Cards.
		array(
			'type'             => 'dropdown',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Visible Cards', 'cph-elements' ) . '</span>',
			'param_name'       => 'visible_cards_tablet',
			'value'            => array(
				esc_html__( 'Auto (use Card Width)', 'cph-elements' ) => '',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
			),
			'std'              => '',
			'edit_field_class'  => 'vc_col-sm-4 tablet visible-cards-device-group',
			'description'      => '',
		),

		// Phone Visible Cards.
		array(
			'type'             => 'dropdown',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Visible Cards', 'cph-elements' ) . '</span>',
			'param_name'       => 'visible_cards_phone',
			'value'            => array(
				esc_html__( 'Auto (use Card Width)', 'cph-elements' ) => '',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
			),
			'std'              => '',
			'edit_field_class'  => 'vc_col-sm-4 phone visible-cards-device-group',
			'description'      => '',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__('Card Aspect Ratio', 'cph-elements'),
			'param_name'  => 'aspect_ratio',
			'value'       => $aspect_ratio_options,
			'std'         => '3/4',
			'description' => esc_html__('Proportion of card height to width.', 'cph-elements'),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__('Custom Aspect Ratio', 'cph-elements'),
			'param_name'  => 'custom_aspect_ratio',
			'value'       => '3/4',
			'description' => esc_html__('Enter as fraction (e.g., 3/4) or decimal (e.g., 0.75).', 'cph-elements'),
			'dependency'  => array(
				'element' => 'aspect_ratio',
				'value'   => array('custom'),
			),
		),

		// Desktop Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__('Gap Between Cards', 'cph-elements') . '</span>',
			'param_name'       => 'gap',
			'value'            => '178px',
			'edit_field_class' => 'vc_col-sm-12 desktop gap-device-group',
			'description'      => esc_html__('Space between cards.', 'cph-elements'),
		),

		// Tablet Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__('Gap', 'cph-elements') . '</span>',
			'param_name'       => 'gap_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet gap-device-group',
			'description'      => '',
		),

		// Phone Gap.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__('Gap', 'cph-elements') . '</span>',
			'param_name'       => 'gap_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone gap-device-group',
			'description'      => '',
		),

		// Desktop Arrow Offset.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__('Arrow Offset', 'cph-elements') . '</span>',
			'param_name'       => 'arrow_offset',
			'value'            => '158px',
			'edit_field_class' => 'vc_col-sm-12 desktop arrow-offset-device-group',
			'description'      => esc_html__('Distance from edge to arrow button.', 'cph-elements'),
		),

		// Tablet Arrow Offset.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__('Offset', 'cph-elements') . '</span>',
			'param_name'       => 'arrow_offset_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet arrow-offset-device-group',
			'description'      => '',
		),

		// Phone Arrow Offset.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__('Offset', 'cph-elements') . '</span>',
			'param_name'       => 'arrow_offset_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone arrow-offset-device-group',
			'description'      => '',
		),

		// Desktop Content Inset.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__('Content Inset', 'cph-elements') . '</span>',
			'param_name'       => 'content_inset',
			'value'            => '356px',
			'edit_field_class' => 'vc_col-sm-12 desktop content-inset-device-group',
			'description'      => esc_html__('Safe zone for arrow area.', 'cph-elements'),
		),

		// Tablet Content Inset.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__('Inset', 'cph-elements') . '</span>',
			'param_name'       => 'content_inset_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet content-inset-device-group',
			'description'      => '',
		),

		// Phone Content Inset.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__('Inset', 'cph-elements') . '</span>',
			'param_name'       => 'content_inset_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone content-inset-device-group',
			'description'      => '',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Image Max Height', 'cph-elements' ),
			'param_name'  => 'max_height',
			'value'       => '',
			'description' => esc_html__( 'Maximum height for card images. Any CSS value: 400px, 50vh. Leave empty for no limit.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Image Border Radius', 'cph-elements' ),
			'param_name'  => 'border_radius',
			'value'       => '25px',
			'description' => esc_html__( 'Border radius for card images. Any CSS value: 25px, 10px, 0, 50%.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Arrow Mode', 'cph-elements' ),
			'param_name'  => 'arrow_mode',
			'value'       => array(
				esc_html__( 'Single Arrow', 'cph-elements' ) => 'single',
				esc_html__( 'Both Arrows', 'cph-elements' )  => 'both',
			),
			'std'         => 'single',
			'description' => esc_html__( 'Single arrow on direction side, or both arrows on left and right.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * EXTRA
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__('Extra', 'cph-elements'),
			'param_name' => 'group_header_extra',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__('Extra Class Name', 'cph-elements'),
			'param_name'  => 'el_class',
			'value'       => '',
			'description' => esc_html__('Add custom CSS class(es) to the wrapper element.', 'cph-elements'),
		),

	),
);

/**
 * Get category options for the dropdown.
 *
 * @return array Category options.
 */
function cph_get_category_options()
{
	$options = array(
		esc_html__('All Categories', 'cph-elements') => '',
	);

	$categories = get_categories(
		array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);

	foreach ($categories as $category) {
		$options[esc_html($category->name)] = $category->slug;
	}

	return $options;
}
