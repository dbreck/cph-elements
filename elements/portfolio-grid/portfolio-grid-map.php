<?php
/**
 * CPH Portfolio Grid - WPBakery Element Map
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
 * Get portfolio categories for dropdown.
 *
 * @since 1.0.0
 *
 * @return array Categories formatted for WPBakery dropdown.
 */
function cph_get_portfolio_categories_dropdown() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'project-type',
			'hide_empty' => false,
		)
	);

	$options = array();

	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$options[ $term->name ] = $term->slug;
		}
	}

	return $options;
}

/**
 * Get project statuses keyed by name for WPBakery options.
 *
 * @since 1.0.0
 *
 * @return array Statuses as Name => slug.
 */
function cph_get_project_status_dropdown() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'project-status',
			'hide_empty' => false,
		)
	);

	$options = array();

	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$options[ $term->name ] = $term->slug;
		}
	}

	return $options;
}

// Layout presets configuration.
$layout_presets = array(
	esc_html__( 'Homepage Bento (3 cards)', 'cph-elements' ) => 'homepage-bento',
	esc_html__( 'Homepage Bento (4 cards)', 'cph-elements' ) => 'homepage-bento-4',
	esc_html__( 'Projects Grid', 'cph-elements' )            => 'featured-2col',
	esc_html__( 'Masonry', 'cph-elements' )                  => 'masonry',
	esc_html__( 'Zigzag', 'cph-elements' )                   => 'zigzag',
);

// Gap options.
$gap_options = array(
	esc_html__( 'No Gap', 'cph-elements' ) => '0',
	esc_html__( '10px', 'cph-elements' )   => '10',
	esc_html__( '20px', 'cph-elements' )   => '20',
	esc_html__( '30px', 'cph-elements' )   => '30',
);

// Animation options.
$animation_options = array(
	esc_html__( 'None', 'cph-elements' )         => 'none',
	esc_html__( 'Fade', 'cph-elements' )         => 'fade',
	esc_html__( 'Fade Up', 'cph-elements' )      => 'fade-up',
	esc_html__( 'Scale Up', 'cph-elements' )     => 'scale-up',
	esc_html__( 'Blur Reveal', 'cph-elements' )  => 'blur-reveal',
	esc_html__( 'Curtain Wipe', 'cph-elements' ) => 'curtain-wipe',
);

// Content position options.
$content_position_options = array(
	esc_html__( 'Bottom Stretch', 'cph-elements' )        => 'bottom-stretch',
	esc_html__( 'Middle Center Stacked', 'cph-elements' ) => 'middle-center',
);

// Button type options.
$button_type_options = array(
	esc_html__( 'Arrow', 'cph-elements' ) => 'arrow',
	esc_html__( 'Text', 'cph-elements' )  => 'text',
	esc_html__( 'None', 'cph-elements' )  => 'none',
);

// Mix-blend-mode options.
$blend_mode_options = array(
	esc_html__( 'None', 'cph-elements' )       => 'normal',
	esc_html__( 'Multiply', 'cph-elements' )   => 'multiply',
	esc_html__( 'Screen', 'cph-elements' )     => 'screen',
	esc_html__( 'Overlay', 'cph-elements' )    => 'overlay',
	esc_html__( 'Darken', 'cph-elements' )     => 'darken',
	esc_html__( 'Lighten', 'cph-elements' )    => 'lighten',
	esc_html__( 'Color Dodge', 'cph-elements' ) => 'color-dodge',
	esc_html__( 'Color Burn', 'cph-elements' ) => 'color-burn',
	esc_html__( 'Hard Light', 'cph-elements' ) => 'hard-light',
	esc_html__( 'Soft Light', 'cph-elements' ) => 'soft-light',
	esc_html__( 'Difference', 'cph-elements' ) => 'difference',
	esc_html__( 'Exclusion', 'cph-elements' )  => 'exclusion',
	esc_html__( 'Hue', 'cph-elements' )        => 'hue',
	esc_html__( 'Saturation', 'cph-elements' ) => 'saturation',
	esc_html__( 'Color', 'cph-elements' )      => 'color',
	esc_html__( 'Luminosity', 'cph-elements' ) => 'luminosity',
);

/**
 * Get portfolio posts for dropdown.
 *
 * @since 1.1.0
 *
 * @return array Posts formatted for WPBakery dropdown (Title => ID).
 */
function cph_get_portfolio_posts_dropdown() {
	$posts = get_posts(
		array(
			'post_type'      => 'portfolio',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);

	$options = array(
		esc_html__( '— Select Project —', 'cph-elements' ) => '',
	);

	foreach ( $posts as $post ) {
		$options[ $post->post_title ] = $post->ID;
	}

	return $options;
}

return array(
	'name'        => esc_html__( 'CPH Portfolio Grid', 'cph-elements' ),
	'base'        => 'cph_portfolio_grid',
	'icon'        => 'icon-wpb-application-icon-large',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Display Portfolio projects in configurable grid layouts', 'cph-elements' ),
	'params'      => array(

		/*
		 * =====================================================================
		 * TAB: GENERAL (default)
		 * =====================================================================
		 */

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * LAYOUT
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Layout', 'cph-elements' ),
			'param_name' => 'group_header_layout',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Layout Preset', 'cph-elements' ),
			'param_name'  => 'layout',
			'value'       => $layout_presets,
			'std'         => 'homepage-bento',
			'admin_label' => true,
			'description' => esc_html__( 'Homepage Bento: manually select projects for a bento grid. Projects Grid: auto-populate with featured support. Masonry: CSS Grid masonry using Salient\'s per-item sizing.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Grid Gap', 'cph-elements' ),
			'param_name'  => 'gap',
			'value'       => $gap_options,
			'std'         => '20',
			'description' => esc_html__( 'Space between cards.', 'cph-elements' ),
		),

		// Desktop Grid Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Grid Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'grid_height',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 desktop grid-height-device-group',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
			'description'      => esc_html__( 'Constrain the total grid height. Leave empty for auto height based on card height. Any CSS unit: 800px, 70vh, etc.', 'cph-elements' ),
		),

		// Tablet Grid Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'grid_height_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet grid-height-device-group',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
			'description'      => '',
		),

		// Phone Grid Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'grid_height_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone grid-height-device-group',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
			'description'      => '',
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * CARD ASSIGNMENTS - HOMEPAGE BENTO LAYOUTS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Card Assignments', 'cph-elements' ),
			'param_name' => 'group_header_assignments',
			'dependency' => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		// ── Slot 1 ──────────────────────────────────────────────────────

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Slot 1 - Large Left', 'cph-elements' ),
			'param_name'  => 'slot_1',
			'value'       => cph_get_portfolio_posts_dropdown(),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
			'description' => esc_html__( 'Select Portfolio project for the tall left card.', 'cph-elements' ),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Logo', 'cph-elements' ),
			'param_name'       => 'slot_1_show_logo',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Video', 'cph-elements' ),
			'param_name'       => 'slot_1_show_video',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Excerpt', 'cph-elements' ),
			'param_name'       => 'slot_1_show_excerpt',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Card Label', 'cph-elements' ),
			'param_name'       => 'slot_1_show_card_label',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'              => 'yes',
			'edit_field_class' => 'vc_col-sm-12',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
			'description'      => esc_html__( 'Show the bottom-of-card label (uses the post\'s Card Label, falls back to the post title; or use Custom Location Text below to override per slot).', 'cph-elements' ),
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Content Position', 'cph-elements' ),
			'param_name'       => 'slot_1_content_position',
			'value'            => $content_position_options,
			'std'              => 'bottom-stretch',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Button Type', 'cph-elements' ),
			'param_name'       => 'slot_1_button_type',
			'value'            => $button_type_options,
			'std'              => 'arrow',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Custom Location Text', 'cph-elements' ),
			'param_name'  => 'slot_1_custom_location',
			'description' => esc_html__( 'Override the card label (defaults to the Portfolio post title).', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		// ── Slot 2 ──────────────────────────────────────────────────────

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Slot 2 - Top Right', 'cph-elements' ),
			'param_name'  => 'slot_2',
			'value'       => cph_get_portfolio_posts_dropdown(),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
			'description' => esc_html__( 'Select Portfolio project for top right card.', 'cph-elements' ),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Logo', 'cph-elements' ),
			'param_name'       => 'slot_2_show_logo',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Video', 'cph-elements' ),
			'param_name'       => 'slot_2_show_video',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Excerpt', 'cph-elements' ),
			'param_name'       => 'slot_2_show_excerpt',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Card Label', 'cph-elements' ),
			'param_name'       => 'slot_2_show_card_label',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'              => 'yes',
			'edit_field_class' => 'vc_col-sm-12',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
			'description'      => esc_html__( 'Show the bottom-of-card label (uses the post\'s Card Label, falls back to the post title; or use Custom Location Text below to override per slot).', 'cph-elements' ),
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Content Position', 'cph-elements' ),
			'param_name'       => 'slot_2_content_position',
			'value'            => $content_position_options,
			'std'              => 'bottom-stretch',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Button Type', 'cph-elements' ),
			'param_name'       => 'slot_2_button_type',
			'value'            => $button_type_options,
			'std'              => 'arrow',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Custom Location Text', 'cph-elements' ),
			'param_name'  => 'slot_2_custom_location',
			'description' => esc_html__( 'Override the card label (defaults to the Portfolio post title).', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		// ── Slot 3 ──────────────────────────────────────────────────────

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Slot 3 - Top Right / Bottom Right', 'cph-elements' ),
			'param_name'  => 'slot_3',
			'value'       => cph_get_portfolio_posts_dropdown(),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
			'description' => esc_html__( 'Select Portfolio project. Position depends on layout.', 'cph-elements' ),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Logo', 'cph-elements' ),
			'param_name'       => 'slot_3_show_logo',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Video', 'cph-elements' ),
			'param_name'       => 'slot_3_show_video',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Excerpt', 'cph-elements' ),
			'param_name'       => 'slot_3_show_excerpt',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Card Label', 'cph-elements' ),
			'param_name'       => 'slot_3_show_card_label',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'              => 'yes',
			'edit_field_class' => 'vc_col-sm-12',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
			'description'      => esc_html__( 'Show the bottom-of-card label (uses the post\'s Card Label, falls back to the post title; or use Custom Location Text below to override per slot).', 'cph-elements' ),
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Content Position', 'cph-elements' ),
			'param_name'       => 'slot_3_content_position',
			'value'            => $content_position_options,
			'std'              => 'bottom-stretch',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Button Type', 'cph-elements' ),
			'param_name'       => 'slot_3_button_type',
			'value'            => $button_type_options,
			'std'              => 'arrow',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Custom Location Text', 'cph-elements' ),
			'param_name'  => 'slot_3_custom_location',
			'description' => esc_html__( 'Override the card label (defaults to the Portfolio post title).', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento', 'homepage-bento-4' ),
			),
		),

		// ── Slot 4 (4-card layout only) ─────────────────────────────────

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Slot 4 - Wide Bottom Right', 'cph-elements' ),
			'param_name'  => 'slot_4',
			'value'       => cph_get_portfolio_posts_dropdown(),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento-4' ),
			),
			'description' => esc_html__( 'Select Portfolio project for the wide bottom-right card.', 'cph-elements' ),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Logo', 'cph-elements' ),
			'param_name'       => 'slot_4_show_logo',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Video', 'cph-elements' ),
			'param_name'       => 'slot_4_show_video',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Excerpt', 'cph-elements' ),
			'param_name'       => 'slot_4_show_excerpt',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-4',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Card Label', 'cph-elements' ),
			'param_name'       => 'slot_4_show_card_label',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'              => 'yes',
			'edit_field_class' => 'vc_col-sm-12',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento-4' ),
			),
			'description'      => esc_html__( 'Show the bottom-of-card label (uses the post\'s Card Label, falls back to the post title; or use Custom Location Text below to override per slot).', 'cph-elements' ),
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Content Position', 'cph-elements' ),
			'param_name'       => 'slot_4_content_position',
			'value'            => $content_position_options,
			'std'              => 'bottom-stretch',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento-4' ),
			),
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Button Type', 'cph-elements' ),
			'param_name'       => 'slot_4_button_type',
			'value'            => $button_type_options,
			'std'              => 'arrow',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento-4' ),
			),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Custom Location Text', 'cph-elements' ),
			'param_name'  => 'slot_4_custom_location',
			'description' => esc_html__( 'Override the card label (defaults to the Portfolio post title).', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento-4' ),
			),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * PROJECTS GRID SETTINGS (featured-2col, masonry, zigzag)
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Projects Grid Settings', 'cph-elements' ),
			'param_name' => 'group_header_projects_grid',
			'dependency' => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col', 'masonry', 'zigzag' ),
			),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Portfolio Category', 'cph-elements' ),
			'param_name'  => 'category',
			'value'       => cph_get_portfolio_categories_dropdown(),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col', 'masonry', 'zigzag' ),
			),
			'description' => esc_html__( 'Filter by one or more categories. Leave all unchecked to show every category.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Project Status', 'cph-elements' ),
			'param_name'  => 'status',
			'value'       => cph_get_project_status_dropdown(),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col', 'masonry', 'zigzag' ),
			),
			'description' => esc_html__( 'Filter by one or more statuses. Leave all unchecked to show every status.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Number of Posts', 'cph-elements' ),
			'param_name'  => 'posts_per_page',
			'value'       => '',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col', 'masonry', 'zigzag' ),
			),
			'description' => esc_html__( 'Leave blank for unlimited. Projects display in menu order.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Logo', 'cph-elements' ),
			'param_name'  => 'show_logo',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col', 'masonry' ),
			),
			'description' => esc_html__( 'Display project logos (Custom Thumbnail) on all cards.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Excerpt', 'cph-elements' ),
			'param_name'  => 'show_excerpt',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col', 'masonry' ),
			),
			'description' => esc_html__( 'Display project excerpts on all cards.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Video (if available)', 'cph-elements' ),
			'param_name'  => 'show_video',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col', 'masonry' ),
			),
			'description' => esc_html__( 'Display video background on cards that have a video set.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Card Label', 'cph-elements' ),
			'param_name'  => 'show_card_label',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col', 'masonry' ),
			),
			'description' => esc_html__( 'Show the bottom-of-card label on every card. Each card uses the post\'s Card Label, falling back to the post title if blank.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Content Position', 'cph-elements' ),
			'param_name'  => 'content_position',
			'value'       => $content_position_options,
			'std'         => 'bottom-stretch',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col', 'masonry' ),
			),
			'description' => esc_html__( 'Position of the title and button within each card.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Button Type', 'cph-elements' ),
			'param_name'  => 'button_type',
			'value'       => $button_type_options,
			'std'         => 'arrow',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col', 'masonry' ),
			),
			'description' => esc_html__( 'Arrow shows the pill-shaped arrow. Text shows a pill button with custom text.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * MASONRY SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Masonry Settings', 'cph-elements' ),
			'param_name' => 'group_header_masonry',
			'dependency' => array(
				'element' => 'layout',
				'value'   => array( 'masonry' ),
			),
		),

		array(
			'type'             => 'dropdown',
			'heading'          => esc_html__( 'Columns', 'cph-elements' ),
			'param_name'       => 'masonry_columns',
			'value'            => array(
				esc_html__( '2 Columns', 'cph-elements' ) => '2',
				esc_html__( '3 Columns', 'cph-elements' ) => '3',
				esc_html__( '4 Columns', 'cph-elements' ) => '4',
			),
			'std'              => '2',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'masonry' ),
			),
			'description'      => esc_html__( 'Number of base columns. Wide items span 2 columns.', 'cph-elements' ),
		),

		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Row Height', 'cph-elements' ),
			'param_name'       => 'masonry_row_height',
			'value'            => '400px',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'masonry' ),
			),
			'description'      => esc_html__( 'Height of each grid row. Tall items span 2 rows. Any CSS value.', 'cph-elements' ),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Filter Bar', 'cph-elements' ),
			'param_name'       => 'masonry_show_filter',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'              => 'yes',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'masonry' ),
			),
			'description'      => esc_html__( 'Show category filter bar above the grid.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * ZIGZAG SETTINGS
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Zigzag Settings', 'cph-elements' ),
			'param_name' => 'group_header_zigzag',
			'dependency' => array(
				'element' => 'layout',
				'value'   => array( 'zigzag' ),
			),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Heading Tag', 'cph-elements' ),
			'param_name'  => 'heading_tag',
			'value'       => array(
				'H1' => 'h1',
				'H2' => 'h2',
				'H3' => 'h3',
				'H4' => 'h4',
				'H5' => 'h5',
				'H6' => 'h6',
			),
			'std'         => 'h3',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'zigzag' ),
			),
			'description' => esc_html__( 'HTML heading tag for project titles.', 'cph-elements' ),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Category Filter', 'cph-elements' ),
			'param_name'       => 'show_filter',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'              => 'yes',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'zigzag' ),
			),
			'description'      => esc_html__( 'Show filter bar to filter projects by category.', 'cph-elements' ),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Show Eyebrow', 'cph-elements' ),
			'param_name'       => 'show_eyebrow',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'              => 'yes',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'zigzag' ),
			),
			'description'      => esc_html__( 'Show project-type taxonomy term above the title.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Button Text', 'cph-elements' ),
			'param_name'  => 'zigzag_button_text',
			'value'       => 'VIEW PROJECT',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'zigzag' ),
			),
			'description' => esc_html__( 'Button label on each zigzag row.', 'cph-elements' ),
		),

		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Row Height', 'cph-elements' ),
			'param_name'       => 'zigzag_row_height',
			'value'            => '500px',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'zigzag' ),
			),
			'description'      => esc_html__( 'Min-height per row. Any CSS value.', 'cph-elements' ),
		),

		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Row Gap', 'cph-elements' ),
			'param_name'       => 'zigzag_row_gap',
			'value'            => '40px',
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'zigzag' ),
			),
			'description'      => esc_html__( 'Vertical space between rows. Any CSS value.', 'cph-elements' ),
		),

		array(
			'type'             => 'checkbox',
			'heading'          => esc_html__( 'Maintain Zigzag on Tablet', 'cph-elements' ),
			'param_name'       => 'zigzag_keep_tablet',
			'value'            => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array(
				'element' => 'layout',
				'value'   => array( 'zigzag' ),
			),
			'description'      => esc_html__( 'Keep the alternating 2-column zigzag layout on tablet instead of stacking.', 'cph-elements' ),
		),

		/*
		 * =====================================================================
		 * TAB: STYLE
		 * =====================================================================
		 */

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * CARD SIZING
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Card Sizing', 'cph-elements' ),
			'param_name' => 'group_header_sizing',
			'group'      => esc_html__( 'Style', 'cph-elements' ),
		),

		// Desktop Card Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Card Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'card_height',
			'value'            => '525px',
			'edit_field_class' => 'vc_col-sm-12 desktop card-height-device-group',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Height for standard cards.', 'cph-elements' ),
		),

		// Tablet Card Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'card_height_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet card-height-device-group',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => '',
		),

		// Phone Card Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'card_height_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone card-height-device-group',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => '',
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * COLORS & TEXT
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Colors & Text', 'cph-elements' ),
			'param_name' => 'group_header_colors',
			'group'      => esc_html__( 'Style', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Text Color', 'cph-elements' ),
			'param_name'  => 'text_color',
			'value'       => '#ffffff',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( 'Location text and excerpt color.', 'cph-elements' ),
		),

		// Desktop Location Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Location Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'location_font_size',
			'value'            => '30px',
			'edit_field_class' => 'vc_col-sm-12 desktop location-font-size-device-group',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => esc_html__( 'Font size for location text.', 'cph-elements' ),
		),

		// Tablet Location Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'location_font_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet location-font-size-device-group',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => '',
		),

		// Phone Location Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'location_font_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone location-font-size-device-group',
			'group'            => esc_html__( 'Style', 'cph-elements' ),
			'description'      => '',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Letter Spacing', 'cph-elements' ),
			'param_name'  => 'location_letter_spacing',
			'value'       => '1.5px',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( 'Letter spacing for location text.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * BUTTON
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Button', 'cph-elements' ),
			'param_name' => 'group_header_button',
			'group'      => esc_html__( 'Style', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Button Text', 'cph-elements' ),
			'param_name'  => 'button_text',
			'value'       => 'Read More',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( 'Text displayed inside the button when Button Type is set to Text.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * LOGO
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Logo', 'cph-elements' ),
			'param_name' => 'group_header_logo',
			'group'      => esc_html__( 'Style', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Logo Width', 'cph-elements' ),
			'param_name'  => 'logo_max_width',
			'value'       => '70%',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( 'Forces all logos to the same width. Any CSS unit: 70%, 250px, 20vw, etc.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Logo Blend Mode', 'cph-elements' ),
			'param_name'  => 'logo_blend_mode',
			'value'       => array(
				esc_html__( 'Normal', 'cph-elements' )     => 'normal',
				esc_html__( 'Screen', 'cph-elements' )     => 'screen',
				esc_html__( 'Multiply', 'cph-elements' )   => 'multiply',
				esc_html__( 'Lighten', 'cph-elements' )    => 'lighten',
				esc_html__( 'Darken', 'cph-elements' )     => 'darken',
				esc_html__( 'Overlay', 'cph-elements' )    => 'overlay',
				esc_html__( 'Soft Light', 'cph-elements' ) => 'soft-light',
				esc_html__( 'Hard Light', 'cph-elements' ) => 'hard-light',
				esc_html__( 'Luminosity', 'cph-elements' ) => 'luminosity',
			),
			'std'         => 'normal',
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( 'Screen makes dark logo backgrounds transparent; Multiply makes light backgrounds transparent.', 'cph-elements' ),
		),

		array(
			'type'        => 'nectar_range_slider',
			'heading'     => esc_html__( 'Logo Brightness', 'cph-elements' ),
			'param_name'  => 'logo_brightness',
			'value'       => '100',
			'options'     => array(
				'min'  => '0',
				'max'  => '2000',
				'step' => '50',
			),
			'group'       => esc_html__( 'Style', 'cph-elements' ),
			'description' => esc_html__( '100 = normal. Values above 500 push logos toward white. 0 = black.', 'cph-elements' ),
		),

		/*
		 * =====================================================================
		 * TAB: OVERLAY
		 * =====================================================================
		 */

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * OVERLAY - DEFAULT STATE
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Default State', 'cph-elements' ),
			'param_name' => 'group_header_overlay_default',
			'group'      => esc_html__( 'Overlay', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Overlay Color', 'cph-elements' ),
			'param_name'  => 'overlay_color',
			'value'       => '#000000',
			'group'       => esc_html__( 'Overlay', 'cph-elements' ),
			'description' => esc_html__( 'Overlay color in default state.', 'cph-elements' ),
		),

		array(
			'type'        => 'nectar_range_slider',
			'heading'     => esc_html__( 'Overlay Opacity', 'cph-elements' ),
			'param_name'  => 'overlay_opacity',
			'value'       => '20',
			'options'     => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '5',
			),
			'group'       => esc_html__( 'Overlay', 'cph-elements' ),
			'description' => esc_html__( 'Overlay opacity in default state (%).', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Blend Mode', 'cph-elements' ),
			'param_name'  => 'overlay_blend_mode',
			'value'       => $blend_mode_options,
			'std'         => 'normal',
			'group'       => esc_html__( 'Overlay', 'cph-elements' ),
			'description' => esc_html__( 'Mix-blend-mode for overlay in default state.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * OVERLAY - HOVER STATE
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Hover State', 'cph-elements' ),
			'param_name' => 'group_header_overlay_hover',
			'group'      => esc_html__( 'Overlay', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Hover Overlay Color', 'cph-elements' ),
			'param_name'  => 'overlay_color_hover',
			'value'       => '',
			'group'       => esc_html__( 'Overlay', 'cph-elements' ),
			'description' => esc_html__( 'Overlay color on hover. Leave empty to use default color.', 'cph-elements' ),
		),

		array(
			'type'        => 'nectar_range_slider',
			'heading'     => esc_html__( 'Hover Overlay Opacity', 'cph-elements' ),
			'param_name'  => 'overlay_opacity_hover',
			'value'       => '30',
			'options'     => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '5',
			),
			'group'       => esc_html__( 'Overlay', 'cph-elements' ),
			'description' => esc_html__( 'Overlay opacity on hover (%).', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Hover Blend Mode', 'cph-elements' ),
			'param_name'  => 'overlay_blend_mode_hover',
			'value'       => $blend_mode_options,
			'std'         => 'normal',
			'group'       => esc_html__( 'Overlay', 'cph-elements' ),
			'description' => esc_html__( 'Mix-blend-mode for overlay on hover.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * LOWER THIRD (Title Hover Effect)
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Title Hover Effect', 'cph-elements' ),
			'param_name' => 'group_header_title_hover',
			'group'      => esc_html__( 'Overlay', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Lower Third Effect', 'cph-elements' ),
			'param_name'  => 'lower_third_enabled',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'group'       => esc_html__( 'Overlay', 'cph-elements' ),
			'description' => esc_html__( 'Animate a background bar behind the title on hover, like a video lower third.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Bar Color', 'cph-elements' ),
			'param_name'  => 'lower_third_bar_color',
			'value'       => '#ffffff',
			'group'       => esc_html__( 'Overlay', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'lower_third_enabled',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Background color of the animated bar.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Text Color on Hover', 'cph-elements' ),
			'param_name'  => 'lower_third_text_color',
			'value'       => '',
			'group'       => esc_html__( 'Overlay', 'cph-elements' ),
			'dependency'  => array(
				'element' => 'lower_third_enabled',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Title text color when bar is shown. Leave empty to keep default.', 'cph-elements' ),
		),

		/*
		 * =====================================================================
		 * TAB: ANIMATION
		 * =====================================================================
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Scroll Animation', 'cph-elements' ),
			'param_name' => 'group_header_animation',
			'group'      => esc_html__( 'Animation', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation Style', 'cph-elements' ),
			'param_name'  => 'animation',
			'value'       => $animation_options,
			'std'         => 'none',
			'group'       => esc_html__( 'Animation', 'cph-elements' ),
			'description' => esc_html__( 'Scroll-triggered animation for cards.', 'cph-elements' ),
		),

		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Animation Stagger', 'cph-elements' ),
			'param_name'       => 'animation_stagger',
			'value'            => '0.1',
			'edit_field_class' => 'vc_col-sm-6',
			'group'            => esc_html__( 'Animation', 'cph-elements' ),
			'dependency'       => array(
				'element'            => 'animation',
				'value_not_equal_to' => array( 'none' ),
			),
			'description'      => esc_html__( 'Delay in seconds between each card. Default: 0.1', 'cph-elements' ),
		),

		array(
			'type'             => 'textfield',
			'heading'          => esc_html__( 'Max Stagger Delay', 'cph-elements' ),
			'param_name'       => 'animation_max_delay',
			'value'            => '0.4',
			'edit_field_class' => 'vc_col-sm-6',
			'group'            => esc_html__( 'Animation', 'cph-elements' ),
			'dependency'       => array(
				'element'            => 'animation',
				'value_not_equal_to' => array( 'none' ),
			),
			'description'      => esc_html__( 'Cap the maximum delay so later cards don\'t wait too long. Default: 0.4s', 'cph-elements' ),
		),

	), // End params.
);
