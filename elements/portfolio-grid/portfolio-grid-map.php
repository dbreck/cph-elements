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

	$options = array(
		esc_html__( 'All Categories', 'cph-elements' ) => '',
	);

	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$options[ $term->name ] = $term->slug;
		}
	}

	return $options;
}

/**
 * Get project statuses for dropdown.
 *
 * @since 1.0.0
 *
 * @return array Statuses formatted for WPBakery dropdown.
 */
function cph_get_project_status_dropdown() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'project-status',
			'hide_empty' => false,
		)
	);

	$options = array(
		esc_html__( 'All Statuses', 'cph-elements' ) => '',
	);

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
	esc_html__( 'Projects Grid', 'cph-elements' )            => 'featured-2col',
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
	esc_html__( 'Fade Up', 'cph-elements' )      => 'fade-up',
	esc_html__( 'Curtain Wipe', 'cph-elements' ) => 'curtain-wipe',
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

// Autocomplete settings for portfolio fields.
$autocomplete_settings = array(
	'multiple'       => false,
	'sortable'       => false,
	'groups'         => false,
	'unique_values'  => true,
	'display_inline' => true,
	'values'         => array(),
);

return array(
	'name'        => esc_html__( 'CPH Portfolio Grid', 'cph-elements' ),
	'base'        => 'cph_portfolio_grid',
	'icon'        => 'icon-wpb-application-icon-large',
	'category'    => esc_html__( 'Clear pH Elements', 'cph-elements' ),
	'description' => esc_html__( 'Display Portfolio projects in configurable grid layouts', 'cph-elements' ),
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
			'heading'     => esc_html__( 'Layout Preset', 'cph-elements' ),
			'param_name'  => 'layout',
			'value'       => $layout_presets,
			'std'         => 'homepage-bento',
			'admin_label' => true,
			'description' => esc_html__( 'Homepage Bento: manually select 3 projects. Projects Grid: auto-populate from post order with featured support.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Grid Gap', 'cph-elements' ),
			'param_name'  => 'gap',
			'value'       => $gap_options,
			'std'         => '20',
			'description' => esc_html__( 'Space between cards.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * CARD ASSIGNMENTS - HOMEPAGE BENTO (3 slots)
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Card Assignments', 'cph-elements' ),
			'param_name' => 'group_header_assignments',
			'dependency' => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento' ),
			),
		),

		array(
			'type'        => 'autocomplete',
			'heading'     => esc_html__( 'Slot 1 - Large Left', 'cph-elements' ),
			'param_name'  => 'slot_1',
			'settings'    => $autocomplete_settings,
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento' ),
			),
			'description' => esc_html__( 'Select Portfolio project for the tall left card.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Logo on Slot 1', 'cph-elements' ),
			'param_name'  => 'slot_1_show_logo',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento' ),
			),
			'description' => esc_html__( 'Display the project logo (Custom Thumbnail) on this card.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Video on Slot 1', 'cph-elements' ),
			'param_name'  => 'slot_1_show_video',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento' ),
			),
			'description' => esc_html__( 'Display video background if project has a Lightbox/Single Project Video set.', 'cph-elements' ),
		),

		array(
			'type'        => 'autocomplete',
			'heading'     => esc_html__( 'Slot 2 - Top Right', 'cph-elements' ),
			'param_name'  => 'slot_2',
			'settings'    => $autocomplete_settings,
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento' ),
			),
			'description' => esc_html__( 'Select Portfolio project for top right card.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Logo on Slot 2', 'cph-elements' ),
			'param_name'  => 'slot_2_show_logo',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento' ),
			),
			'description' => esc_html__( 'Display the project logo (Custom Thumbnail) on this card.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Video on Slot 2', 'cph-elements' ),
			'param_name'  => 'slot_2_show_video',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento' ),
			),
			'description' => esc_html__( 'Display video background if project has a Lightbox/Single Project Video set.', 'cph-elements' ),
		),

		array(
			'type'        => 'autocomplete',
			'heading'     => esc_html__( 'Slot 3 - Bottom Right', 'cph-elements' ),
			'param_name'  => 'slot_3',
			'settings'    => $autocomplete_settings,
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento' ),
			),
			'description' => esc_html__( 'Select Portfolio project for bottom right card.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Logo on Slot 3', 'cph-elements' ),
			'param_name'  => 'slot_3_show_logo',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento' ),
			),
			'description' => esc_html__( 'Display the project logo (Custom Thumbnail) on this card.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Video on Slot 3', 'cph-elements' ),
			'param_name'  => 'slot_3_show_video',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'homepage-bento' ),
			),
			'description' => esc_html__( 'Display video background if project has a Lightbox/Single Project Video set.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * PROJECTS GRID SETTINGS (featured-2col)
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Projects Grid Settings', 'cph-elements' ),
			'param_name' => 'group_header_projects_grid',
			'dependency' => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col' ),
			),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Portfolio Category', 'cph-elements' ),
			'param_name'  => 'category',
			'value'       => cph_get_portfolio_categories_dropdown(),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col' ),
			),
			'description' => esc_html__( 'Filter by category or show all projects.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Project Status', 'cph-elements' ),
			'param_name'  => 'status',
			'value'       => cph_get_project_status_dropdown(),
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col' ),
			),
			'description' => esc_html__( 'Filter by Active or Completed status.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Number of Posts', 'cph-elements' ),
			'param_name'  => 'posts_per_page',
			'value'       => '',
			'dependency'  => array(
				'element' => 'layout',
				'value'   => array( 'featured-2col' ),
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
				'value'   => array( 'featured-2col' ),
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
				'value'   => array( 'featured-2col' ),
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
				'value'   => array( 'featured-2col' ),
			),
			'description' => esc_html__( 'Display video background on cards if a project has a Lightbox/Single Project Video set. Video autoplays muted and loops.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * CARD STYLING
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Card Styling', 'cph-elements' ),
			'param_name' => 'group_header_styling',
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Text Color', 'cph-elements' ),
			'param_name'  => 'text_color',
			'value'       => '#ffffff',
			'description' => esc_html__( 'Location text and excerpt color.', 'cph-elements' ),
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Show Arrow Button', 'cph-elements' ),
			'param_name'  => 'show_arrow',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'std'         => 'yes',
			'description' => esc_html__( 'Display the pill-shaped arrow button on cards.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * OVERLAY - DEFAULT STATE
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Overlay - Default', 'cph-elements' ),
			'param_name' => 'group_header_overlay_default',
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Default Overlay Color', 'cph-elements' ),
			'param_name'  => 'overlay_color',
			'value'       => '#000000',
			'description' => esc_html__( 'Overlay color in default state.', 'cph-elements' ),
		),

		array(
			'type'        => 'nectar_range_slider',
			'heading'     => esc_html__( 'Default Overlay Opacity', 'cph-elements' ),
			'param_name'  => 'overlay_opacity',
			'value'       => '20',
			'options'     => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '5',
			),
			'description' => esc_html__( 'Overlay opacity in default state (%). Cards with logo/excerpt use 64%.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Default Blend Mode', 'cph-elements' ),
			'param_name'  => 'overlay_blend_mode',
			'value'       => $blend_mode_options,
			'std'         => 'normal',
			'description' => esc_html__( 'Mix-blend-mode for overlay in default state.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * OVERLAY - HOVER STATE
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Overlay - Hover', 'cph-elements' ),
			'param_name' => 'group_header_overlay_hover',
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Hover Overlay Color', 'cph-elements' ),
			'param_name'  => 'overlay_color_hover',
			'value'       => '',
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
			'description' => esc_html__( 'Overlay opacity on hover (%). Cards with logo/excerpt use 70%.', 'cph-elements' ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Hover Blend Mode', 'cph-elements' ),
			'param_name'  => 'overlay_blend_mode_hover',
			'value'       => $blend_mode_options,
			'std'         => 'normal',
			'description' => esc_html__( 'Mix-blend-mode for overlay on hover. Leave as None to use default.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * TITLE HOVER EFFECT (Lower Third)
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Title Hover Effect', 'cph-elements' ),
			'param_name' => 'group_header_title_hover',
		),

		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Lower Third Effect', 'cph-elements' ),
			'param_name'  => 'lower_third_enabled',
			'value'       => array( esc_html__( 'Yes', 'cph-elements' ) => 'yes' ),
			'description' => esc_html__( 'Animate a background bar behind the title on hover, like a video lower third.', 'cph-elements' ),
		),

		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Bar Color', 'cph-elements' ),
			'param_name'  => 'lower_third_bar_color',
			'value'       => '#ffffff',
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
			'dependency'  => array(
				'element' => 'lower_third_enabled',
				'value'   => array( 'yes' ),
			),
			'description' => esc_html__( 'Title text color when bar is shown. Leave empty to keep default.', 'cph-elements' ),
		),

		// Desktop Card Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Card Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'card_height',
			'value'            => '525px',
			'edit_field_class' => 'vc_col-sm-12 desktop card-height-device-group',
			'description'      => esc_html__( 'Height for standard cards.', 'cph-elements' ),
		),

		// Tablet Card Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'card_height_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet card-height-device-group',
			'description'      => '',
		),

		// Phone Card Height.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Height', 'cph-elements' ) . '</span>',
			'param_name'       => 'card_height_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone card-height-device-group',
			'description'      => '',
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

		// Desktop Location Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="group-title">' . esc_html__( 'Location Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'location_font_size',
			'value'            => '30px',
			'edit_field_class' => 'vc_col-sm-12 desktop location-font-size-device-group',
			'description'      => esc_html__( 'Font size for location text.', 'cph-elements' ),
		),

		// Tablet Location Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'location_font_size_tablet',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 tablet location-font-size-device-group',
			'description'      => '',
		),

		// Phone Location Font Size.
		array(
			'type'             => 'textfield',
			'heading'          => '<span class="attr-title">' . esc_html__( 'Font Size', 'cph-elements' ) . '</span>',
			'param_name'       => 'location_font_size_phone',
			'value'            => '',
			'edit_field_class' => 'vc_col-sm-12 phone location-font-size-device-group',
			'description'      => '',
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Letter Spacing', 'cph-elements' ),
			'param_name'  => 'location_letter_spacing',
			'value'       => '1.5px',
			'description' => esc_html__( 'Letter spacing for location text.', 'cph-elements' ),
		),

		/*
		 * ─────────────────────────────────────────────────────────────────
		 * ANIMATION
		 * ─────────────────────────────────────────────────────────────────
		 */
		array(
			'type'       => 'nectar_group_header',
			'heading'    => esc_html__( 'Animation', 'cph-elements' ),
			'param_name' => 'group_header_animation',
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation Style', 'cph-elements' ),
			'param_name'  => 'animation',
			'value'       => $animation_options,
			'std'         => 'none',
			'description' => esc_html__( 'Scroll-triggered animation for cards.', 'cph-elements' ),
		),

		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Animation Stagger', 'cph-elements' ),
			'param_name'  => 'animation_stagger',
			'value'       => '0.15',
			'dependency'  => array(
				'element'            => 'animation',
				'value_not_equal_to' => array( 'none' ),
			),
			'description' => esc_html__( 'Delay in seconds between each card animation.', 'cph-elements' ),
		),

	), // End params.
);
